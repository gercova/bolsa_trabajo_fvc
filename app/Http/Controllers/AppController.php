<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionRequirement;
use App\Models\Blog;
use App\Models\Enterprise;
use App\Models\HistoricalReview;
use App\Models\JobOffer;
use App\Models\Partner;
use App\Models\StudyProgram;
use App\Models\User;
use App\Models\UserRoleDetail;
use App\Models\Tupa;
use App\Models\TupaCategory;
use App\Models\TupaProcedure;
use App\Http\Requests\ClaimValidate;
use App\Models\Claim;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AppController extends Controller {

    public function index(): View {
        $programs   = StudyProgram::where('is_active', true)->get();
        $partners   = Partner::where('is_active', true)->get();
        $jobOffers  = JobOffer::where('is_active', true)->get();
        $users      = User::where('is_active', true)->get();
        $blogs      = Blog::where('is_published', true)->latest()->take(3)->get();
        return view('home', compact('partners', 'jobOffers', 'users', 'programs', 'blogs'));
    }

    // cepre fvc
    public function ceprefvc(): View {
        $exams = Admission::where('process', 'cepre')
            ->where('is_active', true)
            ->with(['admissionDetail.program'])
            ->orderBy('id', 'desc')
            ->get();

        $requirements = AdmissionRequirement::where('is_active', true)->get();
        $enterprise = Enterprise::first();

        return view('admission.cepre-fvc', compact('exams', 'requirements', 'enterprise'));
    }
    
    // examen de admisión
    public function admissionExam(): View {
        $exams = Admission::where('process', 'admisión')
            ->where('is_active', true)
            ->with(['admissionDetail.program'])
            ->orderBy('id', 'desc')
            ->get();

        $requirements = AdmissionRequirement::where('is_active', true)->get();
        $enterprise = Enterprise::first();

        return view('admission.admission-exam', compact('exams', 'requirements', 'enterprise'));
    }

    // matrículas
    public function enrollments(): View {
        $enrollments = Admission::where('process', 'matrícula')
            ->where('is_active', true)
            ->with(['admissionDetail.program', 'area'])
            ->orderBy('id', 'desc')
            ->get();

        $requirements = AdmissionRequirement::where('is_active', true)->get();
        $enterprise = Enterprise::first();
        return view('admission.enrollments', compact('enrollments', 'requirements', 'enterprise'));
    }

    // becas y créditos
    public function scholarshipsAndCredits(): View {
        return view('admission.scholarships-and-credits');
    }

    // programas de estudio
    public function studyPrograms(): View {
        $programs = StudyProgram::where('is_active', true)
            ->with(['modules', 'images', 'meta', 'competencies', 'jobFields', 'requirements'])
            ->get();
        return view('study-programs', compact('programs'));
    }

    public function program(StudyProgram $program): View {
        $program->load([
            'images',
            'modules' => fn($q) => $q->where('is_active', true),
            'meta',
            'competencies' => fn($q) => $q->where('is_active', true)->orderBy('order'),
            'jobFields' => fn($q) => $q->where('is_active', true)->orderBy('order'),
            'requirements' => fn($q) => $q->where('is_active', true)->orderBy('order'),
        ]);
        return view('study-program', compact('program'));
    }

    // transparencia
    public function documentsManagement(): View {
        return view('transparency.management-documents');
    }

    public function statistics(): View {
        return view('transparency.statistics');
    }

    public function managementReports(): View {
        return view('transparency.investment-and-management');
    }

    public function licensment(): View {
        return view('transparency.licensment');
    }

    // libro de reclamaciones
    public function complaintsBook(): View {
        $enterprise = Enterprise::first();
        return view('transparency.complaints-book', compact('enterprise'));
    }

    public function storeClaim(ClaimValidate $request): RedirectResponse {
        $validated = $request->validated();

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('claims', 'public');
            $validated['file_path'] = $filePath;
        }

        Claim::create($validated);

        return back()->with('success', 'Su reclamo o queja ha sido registrado con éxito.');
    }

    // Trámites
    public function partsTable(): View {
        $enterprise = Enterprise::first();
        return view('procedures.parts-table', compact('enterprise'));
    }

    // tupa
    public function tupa(): View {
        $enterprise = Enterprise::first();

        $currentTupa = Tupa::where('is_active', true)
            ->orderBy('effective_start_date', 'desc')
            ->first();

        $tupaHistory = Tupa::where('is_active', true)
            ->orderBy('effective_start_date', 'desc')
            ->get();

        $categories = TupaCategory::where('is_active', true)
            ->with(['procedures' => function ($query) use ($currentTupa) {
                $query->where('is_active', true);
                if ($currentTupa) {
                    $query->where('tupa_id', $currentTupa->id);
                }
            }])
            ->get();

        $procedures = $categories->map(function ($cat) {
            return [
                'category' => $cat->name,
                'icon'     => $cat->icon ?? 'bi-journal-check',
                'items'    => $cat->procedures->map(function ($proc) {
                    return [
                        'code'          => $proc->code,
                        'name'          => $proc->name,
                        'description'   => $proc->description,
                        'requirements'  => is_array($proc->requirements)
                            ? $proc->requirements
                            : (json_decode($proc->requirements, true) ?? []),
                        'cost'          => $proc->cost,
                        'uit_percent'   => $proc->uit_percent,
                        'qualification' => $proc->qualification,
                        'duration'      => $proc->duration,
                        'office'        => $proc->office,
                    ];
                })->toArray(),
            ];
        })->filter(fn($group) => !empty($group['items']))->values()->toArray();

        return view('procedures.tupa', compact('enterprise', 'currentTupa', 'tupaHistory', 'procedures'));
    }

    // quienes somos
    public function whoWeAre(): View {
        $enterprise = Enterprise::get();
        return view('aboutus.who-we-are', compact('enterprise'));
    }

    // historia
    public function history(): View {
        $histories = HistoricalReview::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('aboutus.history', compact('histories'));
    }

    // organigrama institucional
    public function institutionalOrganizationChart(): View {
        return view('aboutus.institutional-organization-chart');
    }

    // plana jerarquica
    public function hierarchicalStaff(): View {
        $enterprise = Enterprise::first();

        // 1. Alta Dirección (Director General)
        $director = User::where('is_active', true)
            ->where(function ($q) {
                $q->where('job_position', 'LIKE', '%Director General%')
                  ->orWhere('role', 'Admin');
            })
            ->where('email', '!=', 'admin@example.com')
            ->first();

        // 2. Jefaturas y Unidades de Gestión
        $managementStaff = User::where('is_active', true)
            ->where(function ($q) {
                $q->where('job_position', 'LIKE', '%Administrador%')
                  ->orWhere('job_position', 'LIKE', '%Jefe%')
                  ->orWhere('job_position', 'LIKE', '%Jefatura%')
                  ->orWhere('job_position', 'LIKE', '%Área de calidad%')
                  ->orWhere('job_position', 'LIKE', '%Secretaria de Dirección%');
            })
            ->where('id', '!=', $director?->id)
            ->get();

        // 3. Coordinadores Académicos de Carrera
        $coordinators = UserRoleDetail::with(['user', 'program'])
            ->where('is_active', true)
            ->where('is_coordinator', true)
            ->whereHas('user', fn($q) => $q->where('is_active', true))
            ->get();

        $allHierarchicalIds = collect([$director?->id])
            ->concat($managementStaff->pluck('id'))
            ->concat($coordinators->pluck('user_id'))
            ->filter()
            ->unique();

        $allHierarchical = User::whereIn('id', $allHierarchicalIds)->get();

        return view('aboutus.hierarchical-flat', compact('director', 'managementStaff', 'coordinators', 'allHierarchical', 'enterprise'));
    }

    // plana de docentes
    public function teachersStaff(): View {
        $teacherDetails = UserRoleDetail::with(['user', 'program'])
            ->where('is_active', true)
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        $programs = StudyProgram::where('is_active', true)
            ->orderBy('name')
            ->get();

        $assignedUserIds = $teacherDetails->pluck('user_id')->unique()->filter()->toArray();

        $unassignedTeachers = User::where(function ($q) {
                $q->where('role', 'Docente')
                  ->orWhereHas('roles', fn ($r) => $r->where('name', 'Docente'));
            })
            ->where('is_active', true)
            ->whereNotIn('id', $assignedUserIds)
            ->orderBy('names')
            ->get();

        return view('aboutus.teachers-staff', compact('teacherDetails', 'programs', 'unassignedTeachers'));
    }

    // plana administrativa
    public function administrativeStaff(): View {
        $enterprise = Enterprise::first();

        $staffs = User::where('is_active', true)
            ->where(function ($q) {
                $q->whereIn('role', ['Administrativo', 'Admin'])
                  ->orWhere('job_position', 'LIKE', '%Director%')
                  ->orWhere('job_position', 'LIKE', '%Administrador%')
                  ->orWhere('job_position', 'LIKE', '%Secretaria%')
                  ->orWhere('job_position', 'LIKE', '%Área%')
                  ->orWhere('job_position', 'LIKE', '%Jefatura%')
                  ->orWhere('job_position', 'LIKE', '%Auxiliar%')
                  ->orWhere('job_position', 'LIKE', '%Seguridad%');
            })
            ->where('email', '!=', 'admin@example.com')
            ->orderBy('id', 'asc')
            ->get();

        return view('aboutus.administrative-staff', compact('staffs', 'enterprise'));
    }
    
    // consejo de estudiantes
    public function studentCouncil(): View {
        return view('aboutus.student-council');
    }

    public function locales(): View {
        $enterprise = Enterprise::first() ?? Enterprise::getDefault();
        $programs   = StudyProgram::where('is_active', true)->get();
        return view('aboutus.locals', compact('enterprise', 'programs'));
    }

    public function offers(): View {
        $jobs = JobOffer::where('is_active', true)->get();
        return view('job-board.index', compact('jobs'));
    }
}
