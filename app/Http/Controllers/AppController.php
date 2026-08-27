<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionRequirement;
use App\Models\Blog;
use App\Models\DegreeRecord;
use App\Models\Enterprise;
use App\Models\EnrollmentSchedule;
use App\Models\HistoricalReview;
use App\Models\Image;
use App\Models\InstitutionalCarousel;
use App\Models\JobOffer;
use App\Models\LicensingPhase;
use App\Models\ManagementDocument;
use App\Models\Partner;
use App\Models\Scholarship;
use App\Models\StudentCouncil;
use App\Models\StudentRecord;
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
use Illuminate\Support\Facades\DB;

class AppController extends Controller {

    // inicio
    public function index(): View {
        $programs    = StudyProgram::where('is_active', true)->orderBy('order', 'asc')->orderBy('name', 'asc')->get();
        $partners    = Partner::where('is_active', true)->get();
        $jobOffers   = JobOffer::where('is_active', true)->get();
        $users       = User::where('is_active', true)->get();
        $blogs       = Blog::where('is_published', true)->latest()->take(3)->get();
        $carousels   = InstitutionalCarousel::with('image')->active()->ordered()->get();
        $totalVisits = VisitorCounter::getTotalVisits();
        $visitDigits = VisitorCounter::getPaddedDigits($totalVisits, 6);

        return view('home', compact('partners', 'jobOffers', 'users', 'programs', 'blogs', 'carousels', 'totalVisits', 'visitDigits'));
    }

    // admision-y-matricula/cepre-fvc
    public function ceprefvc(): View {
        $currentYear     = now()->year;
        $projectedYear   = $currentYear + 1;
        $projectedPeriod = $projectedYear . '-I';

        // Filter projected CEPRE schedule (e.g. 2027-I when in 2026)
        $exams = Admission::where('process', 'cepre')
            ->where('is_active', true)
            ->where(function ($q) use ($projectedPeriod, $projectedYear) {
                $q->where('period', $projectedPeriod)
                  ->orWhere('period', 'LIKE', "{$projectedYear}%");
            })
            ->with(['admissionDetail.program'])
            ->orderBy('id', 'desc')
            ->get();

        // Fallback to active CEPRE records if no projected period records found yet
        if ($exams->isEmpty()) {
            $exams = Admission::where('process', 'cepre')
                ->where('is_active', true)
                ->where(function ($q) use ($currentYear) {
                    $q->where('period', '>=', "{$currentYear}-I")
                      ->orWhereNull('period');
                })
                ->with(['admissionDetail.program'])
                ->orderBy('period', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        $requirements  = AdmissionRequirement::where('is_active', true)->get();
        $enterprise    = Enterprise::first();
        $cepreImage    = Image::where('imageable_type', 'cepre')->where('imageable_id', 1)->first();

        return view('admission.cepre-fvc', compact('exams', 'requirements', 'enterprise', 'cepreImage', 'projectedPeriod'));
    }
    
    // admision-y-matricula/examen-de-admision
    public function admissionExam(): View {
        // Dinámica de período proyectado (ej. para el año 2026 proyecta 2027-I)
        $projectedPeriod = (now()->year + 1) . '-I';

        // 1. Vacantes de CEPRE activas en el período proyectado para realizar el descuento
        $cepreExams = Admission::where('process', 'cepre')
            ->where('is_active', true)
            ->where('period', $projectedPeriod)
            ->with('admissionDetail')
            ->get();

        if ($cepreExams->isEmpty()) {
            // Fallback si aún no hay CEPRE registrado con el período proyectado
            $cepreExams = Admission::where('process', 'cepre')
                ->where('is_active', true)
                ->with('admissionDetail')
                ->get();
        }

        $cepreTotalVacancies = $cepreExams->sum('total_vacancies');
        $cepreVacanciesByProgram = [];
        foreach ($cepreExams as $cepreExam) {
            foreach ($cepreExam->admissionDetail as $detail) {
                $cepreVacanciesByProgram[$detail->program_id] = ($cepreVacanciesByProgram[$detail->program_id] ?? 0) + $detail->vacancies;
            }
        }

        // 2. Consulta de exámenes de admisión para el ciclo proyectado ordenando: Extraordinario primero, luego Ordinario
        $exams = Admission::where('process', 'admisión')
            ->where('is_active', true)
            ->where('period', $projectedPeriod)
            ->with(['admissionDetail.program'])
            ->orderByRaw("FIELD(type, 'extraordinario', 'ordinario')")
            ->orderBy('id', 'asc')
            ->get();

        if ($exams->isEmpty()) {
            // Fallback si no hay exámenes para el ciclo proyectado
            $exams = Admission::where('process', 'admisión')
                ->where('is_active', true)
                ->with(['admissionDetail.program'])
                ->orderByRaw("FIELD(type, 'extraordinario', 'ordinario')")
                ->orderBy('id', 'desc')
                ->get();
        }

        // Cálculo de vacantes totales brutas y disponibles descontando las vacantes de CEPRE
        $totalGrossVacancies = $exams->sum('total_vacancies');
        $totalAvailableVacancies = max(0, $totalGrossVacancies - $cepreTotalVacancies);

        // 3. Último examen de admisión con publicación de resultados (PDF)
        $lastExamResults = Admission::where('process', 'admisión')
            ->whereNotNull('results_url_pdf')
            ->orderBy('exam_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $requirements  = AdmissionRequirement::where('is_active', true)->get();
        $enterprise    = Enterprise::first();
        $admisionImage = Image::where('imageable_type', 'admision')->where('imageable_id', 1)->first();

        return view('admission.admission-exam', compact(
            'exams',
            'projectedPeriod',
            'cepreTotalVacancies',
            'cepreVacanciesByProgram',
            'totalGrossVacancies',
            'totalAvailableVacancies',
            'lastExamResults',
            'requirements',
            'enterprise',
            'admisionImage'
        ));
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
        $scholarships              = Scholarship::active()->ordered()->get();
        $totalScholarshipVacancies = $scholarships->sum('vacancies');
        $enterprise                = Enterprise::first();
        return view('admission.scholarships-and-credits', compact('scholarships', 'totalScholarshipVacancies', 'enterprise'));
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
        $enterprise = Enterprise::first() ?? Enterprise::getDefault();

        // ── 1. Indicadores Generales (KPIs) ──────────────────────────
        $totalMatriculas = StudentRecord::where('record_type', 'MATRICULA')->count();
        $totalAdmisiones = StudentRecord::where('record_type', 'ADMISION')->count();
        $totalTitulos    = DegreeRecord::count();
        $totalProgramas  = StudyProgram::where('is_active', true)->count();
        if ($totalProgramas === 0) {
            $totalProgramas = StudentRecord::whereNotNull('study_program')->distinct('study_program')->count('study_program');
        }
        $totalPeriodos   = StudentRecord::whereNotNull('academic_period')->distinct('academic_period')->count('academic_period');
        $totalAniosTitulacion = DegreeRecord::whereNotNull('diploma_issue_date')
            ->select(DB::raw('COUNT(DISTINCT YEAR(diploma_issue_date)) as c'))
            ->value('c') ?? 0;

        // Distribución por género en matrícula
        $totalMalesMatricula   = StudentRecord::where('record_type', 'MATRICULA')->where('gender', 'MASCULINO')->count();
        $totalFemalesMatricula = StudentRecord::where('record_type', 'MATRICULA')->where('gender', 'FEMENINO')->count();

        // Distribución por género en grados y títulos
        $totalMalesTitulos   = DegreeRecord::where('gender', 'MASCULINO')->count();
        $totalFemalesTitulos = DegreeRecord::where('gender', 'FEMENINO')->count();

        // ── 2. Estadísticas de Matrícula (StudentRecord) ─────────────
        $enrollmentRaw = StudentRecord::where('record_type', 'MATRICULA')
            ->whereNotNull('academic_period')
            ->select(
                'academic_period',
                'study_program',
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females'),
                DB::raw('SUM(CASE WHEN cycle = "I" THEN 1 ELSE 0 END) as cycle_i'),
                DB::raw('SUM(CASE WHEN cycle = "II" THEN 1 ELSE 0 END) as cycle_ii'),
                DB::raw('SUM(CASE WHEN cycle = "III" THEN 1 ELSE 0 END) as cycle_iii'),
                DB::raw('SUM(CASE WHEN cycle = "IV" THEN 1 ELSE 0 END) as cycle_iv'),
                DB::raw('SUM(CASE WHEN cycle = "V" THEN 1 ELSE 0 END) as cycle_v'),
                DB::raw('SUM(CASE WHEN cycle = "VI" THEN 1 ELSE 0 END) as cycle_vi')
            )
            ->groupBy('academic_period', 'study_program')
            ->orderBy('academic_period', 'desc')
            ->orderBy('study_program', 'asc')
            ->get();

        $enrollmentByPeriod = $enrollmentRaw->groupBy('academic_period');

        // Resumen Consolidado por Periodo de Matrícula
        $enrollmentPeriodSummary = StudentRecord::where('record_type', 'MATRICULA')
            ->whereNotNull('academic_period')
            ->select(
                'academic_period',
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females'),
                DB::raw('COUNT(DISTINCT study_program) as programs_count')
            )
            ->groupBy('academic_period')
            ->orderBy('academic_period', 'desc')
            ->get();

        // Resumen Histórico de Matrícula por Programa de Estudios
        $enrollmentProgramSummary = StudentRecord::where('record_type', 'MATRICULA')
            ->whereNotNull('study_program')
            ->select(
                'study_program',
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females'),
                DB::raw('MAX(academic_period) as last_period'),
                DB::raw('COUNT(DISTINCT academic_period) as periods_count')
            )
            ->groupBy('study_program')
            ->orderBy('total', 'desc')
            ->get();

        // ── 3. Estadísticas de Admisión (StudentRecord) ──────────────
        $admissionRaw = StudentRecord::where('record_type', 'ADMISION')
            ->whereNotNull('academic_period')
            ->select(
                'academic_period',
                'study_program',
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females')
            )
            ->groupBy('academic_period', 'study_program')
            ->orderBy('academic_period', 'desc')
            ->orderBy('study_program', 'asc')
            ->get();

        $admissionByPeriod = $admissionRaw->groupBy('academic_period');

        $admissionPeriodSummary = StudentRecord::where('record_type', 'ADMISION')
            ->whereNotNull('academic_period')
            ->select(
                'academic_period',
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females')
            )
            ->groupBy('academic_period')
            ->orderBy('academic_period', 'desc')
            ->get();

        // ── 4. Estadísticas de Grados y Títulos (DegreeRecord) ────────
        $degreesRaw = DegreeRecord::whereNotNull('diploma_issue_date')
            ->select(
                DB::raw('YEAR(diploma_issue_date) as year'),
                'study_program',
                DB::raw('MAX(formative_level) as formative_level'),
                DB::raw('MAX(productive_family) as productive_family'),
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females')
            )
            ->groupBy(DB::raw('YEAR(diploma_issue_date)'), 'study_program')
            ->orderBy('year', 'desc')
            ->orderBy('study_program', 'asc')
            ->get();

        $degreesByYear = $degreesRaw->groupBy('year');

        // Resumen Consolidado Anual de Grados y Títulos
        $degreesYearSummary = DegreeRecord::whereNotNull('diploma_issue_date')
            ->select(
                DB::raw('YEAR(diploma_issue_date) as year'),
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females'),
                DB::raw('COUNT(DISTINCT study_program) as programs_count')
            )
            ->groupBy(DB::raw('YEAR(diploma_issue_date)'))
            ->orderBy('year', 'desc')
            ->get();

        // Resumen Histórico de Grados y Títulos por Programa de Estudios
        $degreesProgramSummary = DegreeRecord::whereNotNull('study_program')
            ->select(
                'study_program',
                DB::raw('MAX(formative_level) as formative_level'),
                DB::raw('MAX(productive_family) as productive_family'),
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females'),
                DB::raw('MIN(YEAR(diploma_issue_date)) as first_year'),
                DB::raw('MAX(YEAR(diploma_issue_date)) as last_year')
            )
            ->groupBy('study_program')
            ->orderBy('total', 'desc')
            ->get();

        // Resumen por Familia Productiva
        $degreesFamilySummary = DegreeRecord::whereNotNull('productive_family')
            ->select(
                'productive_family',
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN gender = "MASCULINO" THEN 1 ELSE 0 END) as males'),
                DB::raw('SUM(CASE WHEN gender = "FEMENINO" THEN 1 ELSE 0 END) as females')
            )
            ->groupBy('productive_family')
            ->orderBy('total', 'desc')
            ->get();

        return view('transparency.statistics', compact(
            'enterprise',
            'totalMatriculas',
            'totalAdmisiones',
            'totalTitulos',
            'totalProgramas',
            'totalPeriodos',
            'totalAniosTitulacion',
            'totalMalesMatricula',
            'totalFemalesMatricula',
            'totalMalesTitulos',
            'totalFemalesTitulos',
            'enrollmentByPeriod',
            'enrollmentPeriodSummary',
            'enrollmentProgramSummary',
            'admissionByPeriod',
            'admissionPeriodSummary',
            'degreesByYear',
            'degreesYearSummary',
            'degreesProgramSummary',
            'degreesFamilySummary'
        ));
    }

    // transparencia/inversion-y-gestion
    public function managementReports(): View {
        return view('transparency.investment-and-management');
    }

    // transparencia/licenciamiento
    public function licensment(): View {
        $enterprise = Enterprise::first();
        $phases = LicensingPhase::active()->ordered()->get();
        $currentPhase = LicensingPhase::currentStage();
        $cbcPhase = $phases->firstWhere('phase_number', 1) ?? $phases->first();
        
        $totalPhases = $phases->count();
        $completedPhases = $phases->where('status', 'completed')->count();
        $globalProgress = $totalPhases > 0 
            ? round($phases->avg('progress_percentage')) 
            : 0;

        return view('transparency.licensment', compact(
            'enterprise',
            'phases',
            'currentPhase',
            'cbcPhase',
            'totalPhases',
            'completedPhases',
            'globalProgress'
        ));
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

        // Graduates / Students directory with CVs for recruiting companies
        $graduates = User::where('is_active', true)
            ->where(function ($q) {
                $q->whereIn('role', ['Estudiante', 'Egresado', 'Titulado'])
                  ->orWhere('email', 'LIKE', 'estudiante_%');
            })
            ->with(['studentCouncils.studyProgram', 'primaryRoleDetail.program'])
            ->orderBy('id', 'asc')
            ->get();

        $studyPrograms = StudyProgram::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('job-board.index', compact(
            'jobs',
            'enterprise',
            'locations',
            'sources',
            'search',
            'selectedLocation',
            'selectedSource',
            'graduates',
            'studyPrograms'
        ));
    }

    public function institutionalLinks(): View {
        $enterprise = Enterprise::first() ?? Enterprise::getDefault();
        $links      = ExternalInstitutionalLink::where('is_active', true)->get();
        return view('services.institutional-links', compact('enterprise', 'links'));
    }
}
