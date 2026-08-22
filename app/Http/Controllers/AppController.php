<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionRequirement;
use App\Models\Blog;
use App\Models\Enterprise;
use App\Models\EnrollmentSchedule;
use App\Models\HistoricalReview;
use App\Models\Image;
use App\Models\JobOffer;
use App\Models\ManagementDocument;
use App\Models\Partner;
use App\Models\Scholarship;
use App\Models\StudentCouncil;
use App\Models\StudyProgram;
use App\Models\User;
use App\Models\UserRoleDetail;
use App\Models\Tupa;
use App\Models\TupaCategory;
use App\Models\TupaProcedure;
use App\Http\Requests\ClaimValidate;
use App\Models\Claim;
use App\Models\ExternalInstitutionalLink;
use App\Models\VisitorCounter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppController extends Controller {

    // inicio
    public function index(): View {
        $programs    = StudyProgram::where('is_active', true)->orderBy('order', 'asc')->orderBy('name', 'asc')->get();
        $partners    = Partner::where('is_active', true)->get();
        $jobOffers   = JobOffer::where('is_active', true)->get();
        $users       = User::where('is_active', true)->get();
        $blogs       = Blog::where('is_published', true)->latest()->take(3)->get();
        $totalVisits = VisitorCounter::getTotalVisits();
        $visitDigits = VisitorCounter::getPaddedDigits($totalVisits, 6);

        return view('home', compact('partners', 'jobOffers', 'users', 'programs', 'blogs', 'totalVisits', 'visitDigits'));
    }

    // admision-y-matricula/cepre-fvc
    public function ceprefvc(): View {
        $exams = Admission::where('process', 'cepre')
            ->where('is_active', true)
            ->with(['admissionDetail.program'])
            ->orderBy('id', 'desc')
            ->get();

        $requirements  = AdmissionRequirement::where('is_active', true)->get();
        $enterprise    = Enterprise::first();
        $cepreImage    = Image::where('imageable_type', 'cepre')->where('imageable_id', 1)->first();

        return view('admission.cepre-fvc', compact('exams', 'requirements', 'enterprise', 'cepreImage'));
    }
    
    // admision-y-matricula/examen-de-admision
    public function admissionExam(): View {
        $exams = Admission::where('process', 'admisión')
            ->where('is_active', true)
            ->with(['admissionDetail.program'])
            ->orderBy('id', 'desc')
            ->get();

        $requirements  = AdmissionRequirement::where('is_active', true)->get();
        $enterprise    = Enterprise::first();
        $admisionImage = Image::where('imageable_type', 'admision')->where('imageable_id', 1)->first();

        return view('admission.admission-exam', compact('exams', 'requirements', 'enterprise', 'admisionImage'));
    }

    // admision-y-matricula/matriculas
    public function enrollments(): View {
        // New dedicated enrollment schedules (ordinaria / extraordinaria)
        $schedules = EnrollmentSchedule::with('details.program')
            ->where('is_active', true)
            ->orderByRaw("FIELD(enrollment_type, 'ordinaria', 'extraordinaria')")
            ->orderBy('start_date', 'asc')
            ->get();

        // Legacy admission records (process = matrícula) — kept for backwards compatibility
        $enrollments = Admission::where('process', 'matrícula')
            ->where('is_active', true)
            ->with(['admissionDetail.program', 'area'])
            ->orderBy('id', 'desc')
            ->get();

        $requirements = AdmissionRequirement::where('is_active', true)->get();
        $enterprise   = Enterprise::first();

        return view('admission.enrollments', compact('enrollments', 'schedules', 'requirements', 'enterprise'));
    }

    // admision-y-matriculas/becas-y-creditos
    public function scholarshipsAndCredits(): View {
        $scholarships = Scholarship::active()->ordered()->get();
        $enterprise   = Enterprise::first();
        return view('admission.scholarships-and-credits', compact('scholarships', 'enterprise'));
    }

    // programas-de-estudios
    public function studyPrograms(): View {
        $programs = StudyProgram::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->with(['modules', 'images', 'meta', 'competencies', 'jobFields', 'requirements'])
            ->get();
        return view('study-programs', compact('programs'));
    }

    // programas-de-estudios/{program:slug}
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

    // transparencia/documentos-de-gestion
    public function documentsManagement(): View {
        $documents  = ManagementDocument::where('is_active', true)
            ->orderBy('id', 'asc')
            ->get();
        $enterprise = Enterprise::first();

        return view('transparency.management-documents', compact('documents', 'enterprise'));
    }

    // transparencia/estadisticas
    public function statistics(): View {
        return view('transparency.statistics');
    }

    // transparencia/inversion-y-gestion
    public function managementReports(): View {
        return view('transparency.investment-and-management');
    }

    // transparencia/licenciamiento
    public function licensment(): View {
        return view('transparency.licensment');
    }

    // transparencia/libro-de-reclamaciones
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

    // tramites/mesa-de-partes
    public function partsTable(): View {
        $enterprise = Enterprise::first() ?? Enterprise::getDefault();
        return view('procedures.parts-table', compact('enterprise'));
    }

    // tramites/tupa
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

    // nosotros/quienes-somos
    public function whoWeAre(): View {
        $enterprise = Enterprise::get();
        return view('aboutus.who-we-are', compact('enterprise'));
    }

    // nosotros/historia
    public function history(): View {
        $histories = HistoricalReview::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('aboutus.history', compact('histories'));
    }

    // nosotros/organigrama-institucional
    public function institutionalOrganizationChart(): View {
        return view('aboutus.institutional-organization-chart');
    }

    // nosotros/plana-jerarquica
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

    // nosotros/plana-de-docentes
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

        // User IDs that have an assigned study program
        $assignedProgramUserIds = $teacherDetails
            ->whereNotNull('program_id')
            ->pluck('user_id')
            ->unique()
            ->filter()
            ->toArray();

        // Docentes sin programa asignado (Formación General y Transversales)
        $transversalTeachers = User::where(function ($q) {
            $q->where('role', 'Docente')
            ->orWhereHas('roles', fn ($r) => $r->where('name', 'Docente'));
        })
        ->where('is_active', true)
        ->whereNotIn('id', $assignedProgramUserIds)
        ->with('primaryRoleDetail')
        ->orderBy('names')
        ->get();

        $unassignedTeachers = $transversalTeachers;

        return view('aboutus.teachers-staff', compact('teacherDetails', 'programs', 'transversalTeachers', 'unassignedTeachers'));
    }

    // nosotros/plana-administrativa
    public function administrativeStaff(): View {
        $enterprise = Enterprise::first();

        $staffs = User::where('is_active', true)
            ->where(function ($q) {
                $q->whereIn('role', ['Administrativo', 'Admin'])
                    ->orWhere('job_position', 'LIKE', '%Administrador%')
                    ->orWhere('job_position', 'LIKE', '%Secretaria%')
                    ->orWhere('job_position', 'LIKE', '%Área%')
                    ->orWhere('job_position', 'LIKE', '%Jefatura%')
                    ->orWhere('job_position', 'LIKE', '%Auxiliar%')
                    ->orWhere('job_position', 'LIKE', '%Seguridad%');
            })
            ->where('role', '=', 'Director')
            ->where('role', '!=', 'estudiante')
            ->where('email', '!=', 'admin@example.com')
            ->orderBy('id', 'asc')
            ->get();

        return view('aboutus.administrative-staff', compact('staffs', 'enterprise'));
    }
    
    // nosotros/consejo-de-estudiantes
    public function studentCouncil(): View {
        $enterprise = Enterprise::first();

        // Obtener períodos académicos disponibles
        $periods = StudentCouncil::where('is_active', true)
            ->select('academic_period')
            ->distinct()
            ->orderBy('academic_period', 'desc')
            ->pluck('academic_period');

        $selectedPeriod = request('period', $periods->first() ?? '2026-2027');

        $members = StudentCouncil::with(['user', 'studyProgram'])
            ->where('is_active', true)
            ->when($selectedPeriod, fn($q) => $q->where('academic_period', $selectedPeriod))
            ->get();

        // Clasificar directiva principal y secretarías
        $board = $members->filter(function ($m) {
            $pos = mb_strtolower($m->position, 'UTF-8');
            return str_contains($pos, 'presidente') || str_contains($pos, 'vicepresidente') || str_contains($pos, 'vice presidente');
        })->values();

        $secretaries = $members->reject(function ($m) {
            $pos = mb_strtolower($m->position, 'UTF-8');
            return str_contains($pos, 'presidente') || str_contains($pos, 'vicepresidente') || str_contains($pos, 'vice presidente');
        })->values();

        return view('aboutus.student-council', compact(
            'enterprise',
            'members',
            'board',
            'secretaries',
            'periods',
            'selectedPeriod'
        ));
    }

    // nosotros/locales
    public function locales(): View {
        $enterprise = Enterprise::first() ?? Enterprise::getDefault();
        $programs   = StudyProgram::where('is_active', true)->get();
        return view('aboutus.locals', compact('enterprise', 'programs'));
    }

    // bolsa-de-trabajo
    public function offers(Request $request): View {
        $enterprise = Enterprise::first();

        $search           = $request->input('search');
        $selectedLocation = $request->input('location');
        $selectedSource   = $request->input('source');

        $jobs = JobOffer::where('is_active', true)
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('company', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($selectedLocation, fn($q, $loc) => $q->where('location', $loc))
            ->when($selectedSource, fn($q, $src) => $q->where('source', $src))
            ->orderBy('id', 'desc')
            ->paginate(9)
            ->withQueryString();

        $locations = JobOffer::where('is_active', true)
            ->whereNotNull('location')
            ->select('location')
            ->distinct()
            ->pluck('location');

        $sources = JobOffer::where('is_active', true)
            ->whereNotNull('source')
            ->select('source')
            ->distinct()
            ->pluck('source');

        return view('job-board.index', compact(
            'jobs',
            'enterprise',
            'locations',
            'sources',
            'search',
            'selectedLocation',
            'selectedSource'
        ));
    }

    public function institutionalLinks(): View {
        $enterprise = Enterprise::first() ?? Enterprise::getDefault();
        $links      = ExternalInstitutionalLink::where('is_active', true)->get();
        return view('services.institutional-links', compact('enterprise', 'links'));
    }
}
