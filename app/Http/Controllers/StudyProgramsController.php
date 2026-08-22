<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModularCertificationRequest;
use App\Http\Requests\ProgramCompetencyRequest;
use App\Http\Requests\ProgramJobFieldRequest;
use App\Http\Requests\ProgramMetaRequest;
use App\Http\Requests\ProgramRequirementRequest;
use App\Http\Requests\StudyProgramValidate;
use App\Models\ModularCertification;
use App\Models\ProgramCompetency;
use App\Models\ProgramJobField;
use App\Models\ProgramMeta;
use App\Models\ProgramRequirement;
use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudyProgramsController extends Controller
{
    /**
     * Display a listing of the resources across tabs.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search'           => 'nullable|string|max:255',
            'status'           => 'nullable|string|in:active,inactive',
            'study_program_id' => 'nullable|integer',
            'tab'              => 'nullable|string|in:programs,modules,competencies,job_fields,meta,requirements',
            'per_page'         => 'nullable|integer|min:1|max:100',
        ]);

        $activeTab = $validated['tab'] ?? 'programs';
        $perPage = $validated['per_page'] ?? 10;
        $search = $validated['search'] ?? null;
        $statusFilter = $validated['status'] ?? null;
        $programFilter = $validated['study_program_id'] ?? null;

        // 1. Study Programs
        $progQuery = StudyProgram::query();
        if ($search && $activeTab === 'programs') {
            $progQuery->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
        }
        if ($statusFilter && $activeTab === 'programs') {
            $progQuery->where('is_active', $statusFilter === 'active');
        }
        $programs = $progQuery->orderBy('name', 'asc')
                              ->paginate($perPage, ['*'], 'programs_page')
                              ->withQueryString();

        // 2. Modular Certifications
        $modQuery = ModularCertification::with('studyProgram');
        if ($search && $activeTab === 'modules') {
            $modQuery->where('module', 'LIKE', "%{$search}%");
        }
        if ($statusFilter && $activeTab === 'modules') {
            $modQuery->where('is_active', $statusFilter === 'active');
        }
        if ($programFilter && $activeTab === 'modules') {
            $modQuery->where('program_id', $programFilter);
        }
        $modules = $modQuery->orderBy('id', 'desc')
                            ->paginate($perPage, ['*'], 'modules_page')
                            ->withQueryString();

        // 3. Program Competencies
        $compQuery = ProgramCompetency::with('studyProgram');
        if ($search && $activeTab === 'competencies') {
            $compQuery->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        if ($statusFilter && $activeTab === 'competencies') {
            $compQuery->where('is_active', $statusFilter === 'active');
        }
        if ($programFilter && $activeTab === 'competencies') {
            $compQuery->where('study_program_id', $programFilter);
        }
        $competencies = $compQuery->orderBy('order', 'asc')
                                  ->paginate($perPage, ['*'], 'competencies_page')
                                  ->withQueryString();

        // 4. Program Job Fields
        $jobQuery = ProgramJobField::with('studyProgram');
        if ($search && $activeTab === 'job_fields') {
            $jobQuery->where('description', 'LIKE', "%{$search}%");
        }
        if ($statusFilter && $activeTab === 'job_fields') {
            $jobQuery->where('is_active', $statusFilter === 'active');
        }
        if ($programFilter && $activeTab === 'job_fields') {
            $jobQuery->where('study_program_id', $programFilter);
        }
        $jobFields = $jobQuery->orderBy('order', 'asc')
                              ->paginate($perPage, ['*'], 'job_fields_page')
                              ->withQueryString();

        // 5. Program Metas
        $metaQuery = ProgramMeta::with('studyProgram');
        if ($search && $activeTab === 'meta') {
            $metaQuery->whereHas('studyProgram', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
        if ($programFilter && $activeTab === 'meta') {
            $metaQuery->where('study_program_id', $programFilter);
        }
        $metas = $metaQuery->orderBy('id', 'desc')
                           ->paginate($perPage, ['*'], 'meta_page')
                           ->withQueryString();

        // 6. Program Requirements
        $reqQuery = ProgramRequirement::with('studyProgram');
        if ($search && $activeTab === 'requirements') {
            $reqQuery->where('description', 'LIKE', "%{$search}%");
        }
        if ($statusFilter && $activeTab === 'requirements') {
            $reqQuery->where('is_active', $statusFilter === 'active');
        }
        if ($programFilter && $activeTab === 'requirements') {
            $reqQuery->where('study_program_id', $programFilter);
        }
        $requirements = $reqQuery->orderBy('order', 'asc')
                                 ->paginate($perPage, ['*'], 'requirements_page')
                                 ->withQueryString();

        $allProgramsList = StudyProgram::where('is_active', true)->orderBy('name')->get();

        return view('admin.programs.index', compact(
            'programs',
            'modules',
            'competencies',
            'jobFields',
            'metas',
            'requirements',
            'activeTab',
            'allProgramsList'
        ));
    }

    // =========================================================================
    // STUDY PROGRAMS
    // =========================================================================

    public function create(): View
    {
        return view('admin.programs.create');
    }

    public function store(StudyProgramValidate $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo_path')) {
            $path = $request->file('logo_path')->store('programs', 'public');
            $validated['logo_path'] = $path;
        }

        StudyProgram::create($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'programs'])->with('success', 'Programa de estudio creado correctamente.');
    }

    public function edit(StudyProgram $program): View
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(StudyProgramValidate $request, StudyProgram $program): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo_path')) {
            if ($program->logo_path && Storage::disk('public')->exists($program->logo_path)) {
                Storage::disk('public')->delete($program->logo_path);
            }
            $path = $request->file('logo_path')->store('programs', 'public');
            $validated['logo_path'] = $path;
        }

        $program->update($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'programs'])->with('success', 'Programa de estudio actualizado correctamente.');
    }

    public function toggleStatus(StudyProgram $program): RedirectResponse
    {
        $program->update([
            'is_active' => !$program->is_active
        ]);

        return redirect()->back()->with('success', 'Estado del programa de estudio actualizado correctamente.');
    }

    public function destroy(StudyProgram $program): RedirectResponse
    {
        if ($program->logo_path && Storage::disk('public')->exists($program->logo_path)) {
            Storage::disk('public')->delete($program->logo_path);
        }
        $program->delete();

        return redirect()->route('admin.programs.index', ['tab' => 'programs'])->with('success', 'Programa de estudio eliminado correctamente.');
    }

    // =========================================================================
    // MODULAR CERTIFICATION (modular_certification)
    // =========================================================================

    public function createModule(): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.modules.create', compact('programs'));
    }

    public function storeModule(ModularCertificationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['model_type'] = StudyProgram::class;
        $validated['is_active']  = $request->has('is_active');

        ModularCertification::create($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'modules'])->with('success', 'Módulo de certificación creado correctamente.');
    }

    public function editModule(ModularCertification $module): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.modules.edit', compact('module', 'programs'));
    }

    public function updateModule(ModularCertificationRequest $request, ModularCertification $module): RedirectResponse
    {
        $validated = $request->validated();
        $validated['model_type'] = StudyProgram::class;
        $validated['is_active']  = $request->has('is_active');

        $module->update($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'modules'])->with('success', 'Módulo de certificación actualizado correctamente.');
    }

    public function toggleModuleStatus(ModularCertification $module): RedirectResponse
    {
        $module->update([
            'is_active' => !$module->is_active
        ]);

        return redirect()->back()->with('success', 'Estado del módulo actualizado correctamente.');
    }

    public function destroyModule(ModularCertification $module): RedirectResponse
    {
        $module->delete();

        return redirect()->route('admin.programs.index', ['tab' => 'modules'])->with('success', 'Módulo de certificación eliminado correctamente.');
    }

    // =========================================================================
    // PROGRAM COMPETENCIES (program_competencies)
    // =========================================================================

    public function createCompetency(): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.competencies.create', compact('programs'));
    }

    public function storeCompetency(ProgramCompetencyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        ProgramCompetency::create($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'competencies'])->with('success', 'Competencia creada correctamente.');
    }

    public function editCompetency(ProgramCompetency $competency): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.competencies.edit', compact('competency', 'programs'));
    }

    public function updateCompetency(ProgramCompetencyRequest $request, ProgramCompetency $competency): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $competency->update($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'competencies'])->with('success', 'Competencia actualizada correctamente.');
    }

    public function toggleCompetencyStatus(ProgramCompetency $competency): RedirectResponse
    {
        $competency->update([
            'is_active' => !$competency->is_active
        ]);

        return redirect()->back()->with('success', 'Estado de la competencia actualizado correctamente.');
    }

    public function destroyCompetency(ProgramCompetency $competency): RedirectResponse
    {
        $competency->delete();

        return redirect()->route('admin.programs.index', ['tab' => 'competencies'])->with('success', 'Competencia eliminada correctamente.');
    }

    // =========================================================================
    // PROGRAM JOB FIELDS (program_job_fields)
    // =========================================================================

    public function createJobField(): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.job_fields.create', compact('programs'));
    }

    public function storeJobField(ProgramJobFieldRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        ProgramJobField::create($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'job_fields'])->with('success', 'Campo laboral registrado correctamente.');
    }

    public function editJobField(ProgramJobField $jobField): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.job_fields.edit', compact('jobField', 'programs'));
    }

    public function updateJobField(ProgramJobFieldRequest $request, ProgramJobField $jobField): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $jobField->update($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'job_fields'])->with('success', 'Campo laboral actualizado correctamente.');
    }

    public function toggleJobFieldStatus(ProgramJobField $jobField): RedirectResponse
    {
        $jobField->update([
            'is_active' => !$jobField->is_active
        ]);

        return redirect()->back()->with('success', 'Estado del campo laboral actualizado correctamente.');
    }

    public function destroyJobField(ProgramJobField $jobField): RedirectResponse
    {
        $jobField->delete();

        return redirect()->route('admin.programs.index', ['tab' => 'job_fields'])->with('success', 'Campo laboral eliminado correctamente.');
    }

    // =========================================================================
    // PROGRAM METAS (program_metas)
    // =========================================================================

    public function createMeta(): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.meta.create', compact('programs'));
    }

    public function storeMeta(ProgramMetaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ProgramMeta::create($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'meta'])->with('success', 'Metadatos de presentación guardados correctamente.');
    }

    public function editMeta(ProgramMeta $meta): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.meta.edit', compact('meta', 'programs'));
    }

    public function updateMeta(ProgramMetaRequest $request, ProgramMeta $meta): RedirectResponse
    {
        $validated = $request->validated();

        $meta->update($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'meta'])->with('success', 'Metadatos de presentación actualizados correctamente.');
    }

    public function destroyMeta(ProgramMeta $meta): RedirectResponse
    {
        $meta->delete();

        return redirect()->route('admin.programs.index', ['tab' => 'meta'])->with('success', 'Metadatos eliminados correctamente.');
    }

    // =========================================================================
    // PROGRAM REQUIREMENTS (program_requirements)
    // =========================================================================

    public function createRequirement(): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.requirements.create', compact('programs'));
    }

    public function storeRequirement(ProgramRequirementRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        ProgramRequirement::create($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'requirements'])->with('success', 'Requisito registrado correctamente.');
    }

    public function editRequirement(ProgramRequirement $requirement): View
    {
        $programs = StudyProgram::orderBy('name')->get();
        return view('admin.programs.requirements.edit', compact('requirement', 'programs'));
    }

    public function updateRequirement(ProgramRequirementRequest $request, ProgramRequirement $requirement): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $requirement->update($validated);

        return redirect()->route('admin.programs.index', ['tab' => 'requirements'])->with('success', 'Requisito actualizado correctamente.');
    }

    public function toggleRequirementStatus(ProgramRequirement $requirement): RedirectResponse
    {
        $requirement->update([
            'is_active' => !$requirement->is_active
        ]);

        return redirect()->back()->with('success', 'Estado del requisito actualizado correctamente.');
    }

    public function destroyRequirement(ProgramRequirement $requirement): RedirectResponse
    {
        $requirement->delete();

        return redirect()->route('admin.programs.index', ['tab' => 'requirements'])->with('success', 'Requisito eliminado correctamente.');
    }
}
