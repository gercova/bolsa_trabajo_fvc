<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\StudyProgram;
use App\Http\Requests\AdmissionRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdmissionsController extends Controller
{
    public function index(Request $request): View {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'process' => 'nullable|string|in:admisión,cepre',
            'type' => 'nullable|string|in:ordinario,extraordinario',
            'status' => 'nullable|string|in:active,inactive',
            'date' => 'nullable|string',
            'sort_by' => 'nullable|string|in:id,period,total_vacancies,exam_date,price,type,process,is_active,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Admission::query();

        // Search
        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function($q) use ($search) {
                $q->where('period', 'LIKE', "%{$search}%")
                  ->orWhere('observation', 'LIKE', "%{$search}%");
            });
        }

        // Process filter
        if (!empty($validated['process'])) {
            $query->where('process', $validated['process']);
        }

        // Type filter
        if (!empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        // Status filter
        if (!empty($validated['status'])) {
            $status = $validated['status'] === 'active';
            $query->where('is_active', $status);
        }

        // Date range filter
        if (!empty($validated['date'])) {
            $dates = explode(' - ', $validated['date']);
            if (count($dates) === 2) {
                $query->whereBetween('exam_date', [$dates[0], $dates[1]]);
            }
        }

        // Sorting
        $sortBy = $validated['sort_by'] ?? 'created_at';
        $sortOrder = $validated['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $admission = $query->paginate($validated['per_page'] ?? 10);
        return view('admin.admission.index', compact('admission'));
    }

    public function create(): View {
        $programs = StudyProgram::where('is_active', true)->get();
        return view('admin.admission.create', compact('programs'));
    }

    public function edit(Admission $admission): View {
        $allPrograms = StudyProgram::where('is_active', true)->get();
        $existingDetails = $admission->admissionDetail->keyBy('program_id');

        $programs = $allPrograms->map(function ($program) use ($existingDetails) {
            $detail = $existingDetails->get($program->id);
            return (object) [
                'id' => $program->id,
                'name' => $program->name,
                'vacancies' => $detail ? $detail->vacancies : 0,
            ];
        });

        return view('admin.admission.edit', compact('admission', 'programs'));
    }

    public function store(AdmissionRequest $request): RedirectResponse {
        $validated = $request->validated();

        if ($request->hasFile('url_pdf')) {
            $path = $request->file('url_pdf')->store('admissions', 'public');
            $validated['url_pdf'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $admission = Admission::create($validated);

        if ($request->has('programs')) {
            foreach ($request->input('programs') as $programData) {
                if (!empty($programData['program_id'])) {
                    $admission->admissionDetail()->create([
                        'program_id' => $programData['program_id'],
                        'vacancies'  => $programData['vacancies'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.exams.index')->with('success', 'Examen de admisión / CEPRE creado correctamente');
    }

    public function update(AdmissionRequest $request, Admission $admission): RedirectResponse {
        $validated = $request->validated();

        if ($request->hasFile('url_pdf')) {
            $path = $request->file('url_pdf')->store('admissions', 'public');
            $validated['url_pdf'] = $path;
        } else {
            unset($validated['url_pdf']);
        }

        $validated['is_active'] = $request->has('is_active');

        $admission->update($validated);

        if ($request->has('programs')) {
            $admission->admissionDetail()->delete();
            foreach ($request->input('programs') as $programData) {
                if (!empty($programData['program_id'])) {
                    $admission->admissionDetail()->create([
                        'program_id' => $programData['program_id'],
                        'vacancies'  => $programData['vacancies'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.exams.index')->with('success', 'Examen de admisión / CEPRE actualizado correctamente');
    }

    public function show(Admission $admission): JsonResponse{
        return response()->json($admission);
    }

    public function destroy(Admission $admission): RedirectResponse {
        $admission->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Examen de admisión eliminado correctamente');
    }

    public function toggleStatus(Admission $admission): RedirectResponse {
        $admission->update([
            'is_active' => !$admission->is_active
        ]);
        return redirect()->back()->with('success', 'Estado del examen de admisión actualizado correctamente');
    }
}
