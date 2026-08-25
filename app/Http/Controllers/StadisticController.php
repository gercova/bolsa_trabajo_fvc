<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRecordImportRequest;
use App\Http\Requests\StudentRecordRequest;
use App\Imports\StudentRecordImport;
use App\Models\DocumentType;
use App\Models\StudentRecord;
use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StadisticController extends Controller
{
    /**
     * Display a listing of the resource with statistics & filters.
     */
    public function index(Request $request): View
    {
        $search         = $request->input('search');
        $recordType     = $request->input('record_type');
        $academicPeriod = $request->input('academic_period');
        $studyProgram   = $request->input('study_program');

        $query = StudentRecord::with('documentType')
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('document', 'LIKE', "%{$search}%")
                        ->orWhere('names', 'LIKE', "%{$search}%")
                        ->orWhere('last_name_father', 'LIKE', "%{$search}%")
                        ->orWhere('last_name_mother', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('institution_name_ie', 'LIKE', "%{$search}%");
                });
            })
            ->when($recordType, fn($q, $type) => $q->where('record_type', $type))
            ->when($academicPeriod, fn($q, $period) => $q->where('academic_period', $period))
            ->when($studyProgram, fn($q, $prog) => $q->where('study_program', $prog));

        $studentRecords = $query->latest('id')->paginate(15)->withQueryString();

        // High-level KPI Statistics
        $totalRecords     = StudentRecord::count();
        $totalAdmission   = StudentRecord::where('record_type', 'ADMISION')->count();
        $totalEnrollment  = StudentRecord::where('record_type', 'MATRICULA')->count();
        $totalPrograms    = StudentRecord::whereNotNull('study_program')->distinct('study_program')->count('study_program');

        // Distribution by program
        $programStats = StudentRecord::selectRaw('study_program, COUNT(*) as total, SUM(CASE WHEN record_type = "ADMISION" THEN 1 ELSE 0 END) as admision_count, SUM(CASE WHEN record_type = "MATRICULA" THEN 1 ELSE 0 END) as matricula_count')
            ->whereNotNull('study_program')
            ->groupBy('study_program')
            ->orderByDesc('total')
            ->get();

        // Filter options
        $academicPeriods = StudentRecord::whereNotNull('academic_period')
            ->select('academic_period')
            ->distinct()
            ->orderByDesc('academic_period')
            ->pluck('academic_period');

        $studyProgramsList = StudyProgram::where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        return view('admin.statistics.index', compact(
            'studentRecords',
            'totalRecords',
            'totalAdmission',
            'totalEnrollment',
            'totalPrograms',
            'programStats',
            'academicPeriods',
            'studyProgramsList',
            'search',
            'recordType',
            'academicPeriod',
            'studyProgram'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $documentTypes = DocumentType::where('is_active', true)->get();
        $studyPrograms = StudyProgram::where('is_active', true)->orderBy('name')->get();

        return view('admin.statistics.create', compact('documentTypes', 'studyPrograms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRecordRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        StudentRecord::create($validated);

        return redirect()->route('admin.statistics.index')
            ->with('success', 'Registro académico creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentRecord $studentRecord): View|JsonResponse
    {
        $studentRecord->load('documentType');

        if (request()->wantsJson()) {
            return response()->json($studentRecord);
        }

        return view('admin.statistics.show', compact('studentRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentRecord $studentRecord): View
    {
        $documentTypes = DocumentType::where('is_active', true)->get();
        $studyPrograms = StudyProgram::where('is_active', true)->orderBy('name')->get();

        return view('admin.statistics.edit', compact('studentRecord', 'documentTypes', 'studyPrograms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRecordRequest $request, StudentRecord $studentRecord): RedirectResponse
    {
        $validated = $request->validated();
        $studentRecord->update($validated);

        return redirect()->route('admin.statistics.index')
            ->with('success', 'Registro académico actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRecord $studentRecord): RedirectResponse
    {
        $studentRecord->delete();

        return redirect()->route('admin.statistics.index')
            ->with('success', 'Registro académico eliminado exitosamente.');
    }

    /**
     * Import student records in bulk from an Excel (.xlsx/.xls) or CSV file.
     * Columns H–AF of the MINEDU-format report are mapped to StudentRecord fields.
     */
    public function import(StudentRecordImportRequest $request): RedirectResponse
    {
        $academicPeriod = strtoupper(trim($request->input('academic_period')));
        $recordType     = strtoupper(trim($request->input('record_type', 'AUTO')));

        // If "AUTO" the importer will detect per-row based on cycle presence
        if ($recordType === 'AUTO') {
            $recordType = 'ADMISION'; // default; overridden per-row inside the importer
        }

        try {
            $importer = new StudentRecordImport($academicPeriod, $recordType);
            Excel::import($importer, $request->file('file'));

            $msg = "Importación completada: {$importer->importedCount} registros importados";
            if ($importer->skippedCount > 0) {
                $msg .= ", {$importer->skippedCount} filas omitidas (sin datos).";
            } else {
                $msg .= '.';
            }

            return redirect()->route('admin.statistics.index')->with('success', $msg);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())->map(
                fn ($f) => "Fila {$f->row()}: " . implode(', ', $f->errors())
            )->take(10)->implode(' | ');

            return redirect()->route('admin.statistics.index')
                ->with('error', "Error de validación en el archivo: {$failures}");
        } catch (\Exception $e) {
            return redirect()->route('admin.statistics.index')
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }
}
