<?php

namespace App\Http\Controllers;

use App\Http\Requests\DegreeRecordImportRequest;
use App\Imports\DegreeRecordImport;
use App\Models\DegreeRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DegreeRecordsController extends Controller
{
    // ── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $search       = $request->input('search');
        $studyProgram = $request->input('study_program');
        $department   = $request->input('department');
        $diplomaType  = $request->input('diploma_type');

        $query = DegreeRecord::query()
            ->when($search, function ($q, $s) {
                $q->where(function ($sub) use ($s) {
                    $sub->where('full_names',      'LIKE', "%{$s}%")
                        ->orWhere('document_number', 'LIKE', "%{$s}%")
                        ->orWhere('study_program',   'LIKE', "%{$s}%")
                        ->orWhere('file_number',     'LIKE', "%{$s}%")
                        ->orWhere('generated_title_code', 'LIKE', "%{$s}%");
                });
            })
            ->when($studyProgram, fn($q, $v) => $q->where('study_program', $v))
            ->when($department,   fn($q, $v) => $q->where('department',    $v))
            ->when($diplomaType,  fn($q, $v) => $q->where('diploma_type',  $v));

        $records = $query->latest('id')->paginate(15)->withQueryString();

        // KPIs
        $totalRecords   = DegreeRecord::count();
        $totalPrograms  = DegreeRecord::whereNotNull('study_program')->distinct('study_program')->count('study_program');
        $totalDepts     = DegreeRecord::whereNotNull('department')->distinct('department')->count('department');
        $totalThisYear  = DegreeRecord::whereYear('diploma_issue_date', now()->year)->count();

        // Filter option lists
        $programs    = DegreeRecord::whereNotNull('study_program')->select('study_program')->distinct()->orderBy('study_program')->pluck('study_program');
        $departments = DegreeRecord::whereNotNull('department')->select('department')->distinct()->orderBy('department')->pluck('department');
        $diplomaTypes = DegreeRecord::whereNotNull('diploma_type')->select('diploma_type')->distinct()->orderBy('diploma_type')->pluck('diploma_type');

        return view('admin.degree-records.index', compact(
            'records', 'search', 'studyProgram', 'department', 'diplomaType',
            'totalRecords', 'totalPrograms', 'totalDepts', 'totalThisYear',
            'programs', 'departments', 'diplomaTypes'
        ));
    }

    // ── Create / Store ───────────────────────────────────────────────────────

    public function create(): View
    {
        return view('admin.degree-records.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        DegreeRecord::create($validated);

        return redirect()->route('admin.degree-records.index')
            ->with('success', 'Registro de grado/título creado exitosamente.');
    }

    // ── Show ─────────────────────────────────────────────────────────────────

    public function show(DegreeRecord $degreeRecord): View
    {
        return view('admin.degree-records.show', compact('degreeRecord'));
    }

    // ── Edit / Update ────────────────────────────────────────────────────────

    public function edit(DegreeRecord $degreeRecord): View
    {
        return view('admin.degree-records.edit', compact('degreeRecord'));
    }

    public function update(Request $request, DegreeRecord $degreeRecord): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $degreeRecord->update($validated);

        return redirect()->route('admin.degree-records.index')
            ->with('success', 'Registro actualizado exitosamente.');
    }

    // ── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(DegreeRecord $degreeRecord): RedirectResponse
    {
        $degreeRecord->delete();

        return redirect()->route('admin.degree-records.index')
            ->with('success', 'Registro eliminado exitosamente.');
    }

    // ── Bulk Import ──────────────────────────────────────────────────────────

    /**
     * Import Degree Records from MINEDU Excel report (cols B-X, skip A/G/W).
     * Rows 1-4 are title/headers; data starts at row 5.
     * Uses bulk DB::insert per 500-row chunk for maximum performance.
     */
    public function import(DegreeRecordImportRequest $request): RedirectResponse
    {
        try {
            $importer = new DegreeRecordImport();
            Excel::import($importer, $request->file('file'));

            $msg = "Importación completada: {$importer->importedCount} registros importados";
            if ($importer->skippedCount > 0) {
                $msg .= ", {$importer->skippedCount} filas omitidas (sin datos).";
            } else {
                $msg .= '.';
            }

            return redirect()->route('admin.degree-records.index')->with('success', $msg);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())->map(
                fn ($f) => "Fila {$f->row()}: " . implode(', ', $f->errors())
            )->take(10)->implode(' | ');

            return redirect()->route('admin.degree-records.index')
                ->with('error', "Error de validación en el archivo: {$failures}");
        } catch (\Exception $e) {
            return redirect()->route('admin.degree-records.index')
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    // ─── Shared validation rules ─────────────────────────────────────────────

    private function rules(): array
    {
        return [
            'modular_code'                      => ['nullable', 'string', 'max:20'],
            'institution_name'                  => ['required', 'string', 'max:255'],
            'management_type'                   => ['nullable', 'string', 'max:50'],
            'department'                        => ['nullable', 'string', 'max:100'],
            'study_program'                     => ['required', 'string', 'max:255'],
            'mention'                           => ['nullable', 'string'],
            'formative_level'                   => ['nullable', 'string', 'max:255'],
            'productive_family'                 => ['nullable', 'string', 'max:255'],
            'document_type'                     => ['nullable', 'string', 'max:20'],
            'document_number'                   => ['nullable', 'string', 'max:30'],
            'full_names'                        => ['required', 'string', 'max:255'],
            'birth_date'                        => ['nullable', 'date'],
            'gender'                            => ['nullable', 'string', 'max:20'],
            'graduation_date'                   => ['nullable', 'date'],
            'institutional_registration_number' => ['nullable', 'string', 'max:255'],
            'diploma_issue_date'                => ['nullable', 'date'],
            'minedu_registration_date'          => ['nullable', 'date'],
            'generated_title_code'              => ['nullable', 'string', 'max:255'],
            'file_number'                       => ['nullable', 'string', 'max:255'],
            'registration_type'                 => ['nullable', 'string', 'max:50'],
            'specialist_user'                   => ['nullable', 'string', 'max:255'],
            'diploma_type'                      => ['nullable', 'string', 'max:50'],
        ];
    }
}
