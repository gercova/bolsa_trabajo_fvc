<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionRequirement;
use App\Models\Enterprise;
use App\Models\HistoricalReview;
use App\Models\JobOffer;
use App\Models\Partner;
use App\Models\StudyProgram;
use App\Models\User;
use App\Models\Claim;
use App\Http\Requests\ClaimValidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AppController extends Controller {

    public function index(): View {
        $programs   = StudyProgram::where('is_active', true)->get();
        $partners   = Partner::where('is_active', true)->get();
        $jobOffers  = JobOffer::where('is_active', true)->get();
        $users      = User::where('is_active', true)->get();
        return view('home', compact('partners', 'jobOffers', 'users', 'programs'));
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
        $programs = StudyProgram::where('is_active', true)->with(['modules', 'images'])->get();
        return view('study-programs', compact('programs'));
    }

    public function program(StudyProgram $program): View {
        $program->load(['images', 'modules']);
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
        $currentTupa = \App\Models\Tupa::where('is_active', true)
            ->orderBy('effective_start_date', 'desc')
            ->first();

        $tupaHistory = \App\Models\Tupa::where('is_active', true)
            ->orderBy('effective_start_date', 'desc')
            ->get();

        $procedures = [
            [
                'category' => 'Trámites Académicos y Certificación',
                'icon' => 'bi-journal-check',
                'items' => [
                    [
                        'code' => 'P-01',
                        'name' => 'Certificado de Estudios (Por Semestre / Módulo)',
                        'description' => 'Documento oficial que acredita las notas obtenidas por semestre académico o módulo profesional.',
                        'requirements' => [
                            'Formulario Único de Trámite (FUT) completado y dirigido al Director General.',
                            'Comprobante de pago por derecho de trámite.',
                            '02 fotos tamaño carné a color en fondo blanco.',
                            'Constancia de no adeudar libros ni materiales a la institución.'
                        ],
                        'cost' => 'S/. 35.00',
                        'uit_percent' => '0.65%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '5 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-02',
                        'name' => 'Certificado Modular Formal de Capacitación',
                        'description' => 'Otorgamiento del certificado al culminar satisfactoriamente un Módulo Técnico Profesional.',
                        'requirements' => [
                            'FUT dirigido al Director General del instituto.',
                            'Comprobante de pago por derecho de certificado modular.',
                            'Constancia de prácticas preprofesionales / módulo acreditado.',
                            '02 fotografías tamaño pasaporte en fondo blanco con vestimenta formal.'
                        ],
                        'cost' => 'S/. 60.00',
                        'uit_percent' => '1.12%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '7 días hábiles',
                        'office' => 'Área de Unidad Académica'
                    ],
                    [
                        'code' => 'P-03',
                        'name' => 'Expedición de Título Profesional Técnico',
                        'description' => 'Trámite institucional para la emisión del Título Profesional a nombre de la Nación.',
                        'requirements' => [
                            'FUT institucional especificando el Programa de Estudios cursado.',
                            'Certificado de Estudios integrales aprobados (Semestres I al VI).',
                            'Constancias aprobadas de Módulos Profesionales y Prácticas Preprofesionales (EFSRT).',
                            'Constancia de Acreditación de Idioma Extranjero y Computación (si corresponde).',
                            'Comprobante de pago por derecho de Titulación y carpeta de egresado.',
                            '04 fotografías tamaño pasaporte en papel mate con traje formal.'
                        ],
                        'cost' => 'S/. 350.00',
                        'uit_percent' => '6.54%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '30 días hábiles',
                        'office' => 'Dirección General / Secretaría Académica'
                    ],
                    [
                        'code' => 'P-04',
                        'name' => 'Duplicado de Título Profesional o Certificados',
                        'description' => 'Expedición de duplicado oficial en caso de pérdida, deterioro o robo del documento original.',
                        'requirements' => [
                            'FUT dirigido al Director General.',
                            'Denuncia policial original por pérdida o robo (si aplica) o devolución del original deteriorado.',
                            'Publicación en diario de mayor circulación o declaración jurada legalizada.',
                            'Comprobante de pago por emisión de duplicado.',
                            '02 fotografías tamaño pasaporte.'
                        ],
                        'cost' => 'S/. 120.00',
                        'uit_percent' => '2.24%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '15 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-05',
                        'name' => 'Constancias Varias (Estudios, Egresado, Orden de Mérito)',
                        'description' => 'Emisión de constancia oficial de matrícula, condición de egresado, conducta o ponderado académico.',
                        'requirements' => [
                            'FUT solicitando la constancia específica.',
                            'Comprobante de pago correspondiente al tipo de constancia.'
                        ],
                        'cost' => 'S/. 20.00',
                        'uit_percent' => '0.37%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '2 días hábiles',
                        'office' => 'Secretaría Académica'
                    ]
                ]
            ],
            [
                'category' => 'Matrícula, Reincorporación y Traslados',
                'icon' => 'bi-person-badge',
                'items' => [
                    [
                        'code' => 'P-06',
                        'name' => 'Matrícula Regular por Semestre Académico',
                        'description' => 'Inscripción semestral ordinaria para estudiantes regulares de la institución.',
                        'requirements' => [
                            'Ficha de matrícula debidamente completada.',
                            'Comprobante de pago de tasa por concepto de matrícula semestral.',
                            'Estar al día en compromisos administrativos y biblioteca.'
                        ],
                        'cost' => 'S/. 80.00',
                        'uit_percent' => '1.50%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '1 día hábil',
                        'office' => 'Unidad de Admisión y Registro'
                    ],
                    [
                        'code' => 'P-07',
                        'name' => 'Matrícula Extemporánea',
                        'description' => 'Inscripción fuera del cronograma ordinario de matrícula establecido en el calendario académico.',
                        'requirements' => [
                            'FUT justificado indicando motivo extemporáneo.',
                            'Comprobante de pago de recargo por matrícula extemporánea.',
                            'Aprobación previa de la Jefatura de Unidad Académica.'
                        ],
                        'cost' => 'S/. 110.00',
                        'uit_percent' => '2.05%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '2 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-08',
                        'name' => 'Reserva o Licencia de Matrícula',
                        'description' => 'Solicitud de suspensión temporal de estudios hasta por un máximo de 4 semestres académicos consecutivos.',
                        'requirements' => [
                            'FUT solicitando la reserva de matrícula por razones personales, laborales o de salud.',
                            'Documentos sustentatorios del motivo (si aplica).',
                            'Comprobante de pago por derecho de trámite de reserva.'
                        ],
                        'cost' => 'S/. 30.00',
                        'uit_percent' => '0.56%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '3 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-09',
                        'name' => 'Reincorporación a los Estudios',
                        'description' => 'Retorno a la actividad académica tras haber mantenido licencia o reserva de vacante.',
                        'requirements' => [
                            'FUT pidiendo reincorporación al semestre correspondiente.',
                            'Copia del documento que aprobó la reserva o licencia previa.',
                            'Comprobante de pago por derecho de reincorporación.'
                        ],
                        'cost' => 'S/. 40.00',
                        'uit_percent' => '0.75%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '3 días hábiles',
                        'office' => 'Unidad Académica'
                    ],
                    [
                        'code' => 'P-10',
                        'name' => 'Convalidación de Asignaturas / Módulos',
                        'description' => 'Reconocimiento académico de unidades didácticas aprobadas en la misma u otra institución de educación superior.',
                        'requirements' => [
                            'FUT especificando las asignaturas o módulos a convalidar.',
                            'Certificados de Estudios oficiales originales.',
                            'Sílabos oficiales visados y sellados por la institución de origen.',
                            'Comprobante de pago por derecho de convalidación por unidad didáctica o módulo.'
                        ],
                        'cost' => 'S/. 50.00',
                        'uit_percent' => '0.93%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '10 días hábiles',
                        'office' => 'Comisión de Convalidación / Jefatura de Área'
                    ]
                ]
            ],
            [
                'category' => 'Evaluaciones y Exámenes',
                'icon' => 'bi-clipboard-check',
                'items' => [
                    [
                        'code' => 'P-11',
                        'name' => 'Inscripción al Examen de Admisión Ordinario',
                        'description' => 'Derecho a participar en el proceso de admisión general del instituto.',
                        'requirements' => [
                            'Certificado de Estudios de Educación Secundaria aprobados.',
                            'Copia simple de DNI o Carné de Extranjería.',
                            'Partida o Acta de Nacimiento.',
                            'Comprobante de pago por derecho de inscripción al examen de admisión.'
                        ],
                        'cost' => 'S/. 150.00',
                        'uit_percent' => '2.80%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => 'Inmediata',
                        'office' => 'Comisión Institucional de Admisión'
                    ],
                    [
                        'code' => 'P-12',
                        'name' => 'Examen de Subsanación / Recuperación',
                        'description' => 'Rendición de examen de evaluación extraordinaria para subsanar unidades didácticas no aprobadas.',
                        'requirements' => [
                            'FUT dirigido a la Jefatura de Área Académica.',
                            'Boleta de notas indicando la asignatura a subsanar.',
                            'Comprobante de pago por examen de subsanación por curso.'
                        ],
                        'cost' => 'S/. 45.00',
                        'uit_percent' => '0.84%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '3 días hábiles',
                        'office' => 'Jefatura de Unidad Académica'
                    ]
                ]
            ],
            [
                'category' => 'Servicios Complementarios y Carnés',
                'icon' => 'bi-card-heading',
                'items' => [
                    [
                        'code' => 'P-13',
                        'name' => 'Carné Institucional de Estudiante (Expedición / Duplicado)',
                        'description' => 'Identificación oficial del estudiante para el acceso a instalaciones e infraestructura física y digital.',
                        'requirements' => [
                            'Ficha de matrícula vigente.',
                            '01 foto tamaño carné en digital o físico.',
                            'Comprobante de pago por derecho de expedición o duplicado de carné.'
                        ],
                        'cost' => 'S/. 18.00',
                        'uit_percent' => '0.33%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '5 días hábiles',
                        'office' => 'Unidad de Bienestar e Infraestructura'
                    ],
                    [
                        'code' => 'P-14',
                        'name' => 'Autenticación / Visado de Documentos e Imagen Institucional',
                        'description' => 'Certificación de autenticidad de firmas y fotocopias de documentos expedidos por la institución.',
                        'requirements' => [
                            'FUT solicitando la autenticación o copia fiel del original.',
                            'Presentación de los documentos originales y fotocopias legibles.',
                            'Comprobante de pago por folio o documento.'
                        ],
                        'cost' => 'S/. 10.00',
                        'uit_percent' => '0.18%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '1 día hábil',
                        'office' => 'Secretaría General / Dirección'
                    ]
                ]
            ]
        ];

        return view('procedures.tupa', compact('currentTupa', 'tupaHistory', 'procedures'));
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
    public function hierarchicalFlat(): View {
        return view('aboutus.hierarchical-flat');
    }

    // plana de docentes
    public function teachersStaff(): View {
        $teachers = User::where('role', 'Docente')->get();
        return view('aboutus.teachers-staff', compact('teachers'));
    }

    // plana administrativa
    public function administrativeStaff(): View {
        $staffs = User::where('role', 'Administrativo')->get();
        return view('aboutus.administrative-staff', compact('staffs'));
    }
    
    // consejo de estudiantes
    public function studentCouncil(): View {
        return view('aboutus.student-council');
    }

    public function offers(): View {
        $jobs = JobOffer::where('is_active', true)->get();
        return view('job-board.index', compact('jobs'));
    }
}
