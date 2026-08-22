<?php

use App\Http\Controllers\AdmissionsController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentScheduleController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\ExternalInstitutionalLinkController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\ManagementDocumentController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\StudentCouncilController;
use App\Http\Controllers\TeacherRoleController;
use App\Http\Controllers\StudyProgramsController;
use App\Http\Controllers\TupaController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/',         [AppController::class, 'index'])->name('inicio');

// Admisión y matrícula
Route::get('/admision-y-matricula/cepre-fvc',           [AppController::class, 'ceprefvc'])->name('cepre-fvc');
Route::get('/admision-y-matricula/examen-de-admision',  [AppController::class, 'admissionExam'])->name('examen-de-admision');
Route::get('/admision-y-matricula/matriculas',          [AppController::class, 'enrollments'])->name('matriculas');
Route::get('/admision-y-matriculas/becas-y-creditos',   [AppController::class, 'scholarshipsAndCredits'])->name('becas-y-creditos');

// programas de estudio
Route::get('/programas-de-estudios',                [AppController::class, 'studyPrograms'])->name('programas-de-estudio');
Route::get('/programas-de-estudios/{program:slug}', [AppController::class, 'program'])->name('programas-de-estudio.detalle');

// Transparencia
Route::get('/transparencia/documentos-de-gestion',    [AppController::class, 'documentsManagement'])->name('documentos-de-gestion');
Route::get('/transparencia/estadisticas',             [AppController::class, 'statistics'])->name('estadisticas');
Route::get('/transparencia/inversion-y-gestion',      [AppController::class, 'managementReports'])->name('inversion-y-gestion');
Route::get('/transparencia/licenciamiento',           [AppController::class, 'licensment'])->name('licenciamiento');
Route::get('/transparencia/libro-de-reclamaciones',   [AppController::class, 'complaintsBook'])->name('libro-de-reclamaciones');
Route::post('/transparencia/libro-de-reclamaciones',  [AppController::class, 'storeClaim'])->name('libro-de-reclamaciones.store')->middleware('throttle:5,1');

// Trámites
Route::get('/tramites/mesa-de-partes',                [AppController::class, 'partsTable'])->name('mesa-de-partes');
Route::get('/tramites/tupa',                          [AppController::class, 'tupa'])->name('tupa');

// Nosotros
Route::get('/nosotros/quienes-somos',               [AppController::class, 'whoWeAre'])->name('quienes-somos');
Route::get('/nosotros/historia',                    [AppController::class, 'history'])->name('historia');
Route::get('/nosotros/organigrama-institucional',   [AppController::class, 'institutionalOrganizationChart'])->name('organigrama-institucional');
Route::get('/nosotros/plana-jerarquica',            [AppController::class, 'hierarchicalStaff'])->name('plana-jerarquica');
Route::get('/nosotros/plana-de-docentes',           [AppController::class, 'teachersStaff'])->name('plana-de-docentes');
Route::get('/nosotros/plana-administrativa',        [AppController::class, 'administrativeStaff'])->name('plana-administrativa');
Route::get('/nosotros/consejo-de-estudiantes',      [AppController::class, 'studentCouncil'])->name('consejo-de-estudiantes');
Route::get('/nosotros/locales',                     [AppController::class, 'locales'])->name('locales');

// servicios
Route::get('/servicios/bolsa-de-trabajo', [AppController::class, 'offers'])->name('bolsa-de-trabajo');

// Links institucionales
Route::get('/servicios/enlaces-institucionales', [AppController::class, 'institutionalLinks'])->name('enlaces-institucionales');

// Rutas de autenticación (definidas canónicamente en routes/auth.php)

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin-dashboard')->name('admin.dashboard.')->group(function () {
        Route::get('/',    [DashboardController::class, 'index'])->name('index');
    });

    Route::prefix('admin-perfil')->name('admin.profile.')->group(function () {
        Route::get('/{user}',   [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/',       [ProfileController::class, 'update'])->name('update');
        Route::delete('/',      [ProfileController::class, 'destroy'])->name('destroy');    
    });

    // blogs
    Route::prefix('admin-blogs')->name('admin.blogs.')->group(function () {
        Route::get('/',                             [BlogController::class, 'index'])->name('index');
        Route::get('/crear-blog',                   [BlogController::class, 'create'])->name('create');
        Route::post('/guardar',                     [BlogController::class, 'store'])->name('store');
        Route::get('/editar-blog/{blog}',           [BlogController::class, 'edit'])->name('edit');
        Route::put('/editar-blog/{blog}',           [BlogController::class, 'update'])->name('update');
        Route::delete('/{blog}',                    [BlogController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{blog}',              [BlogController::class, 'toggleStatus'])->name('toggle-status');
    });

    // examenes, matrículas
    Route::prefix('admin-exams')->name('admin.exams.')->group(function () {
        Route::get('/',                             [AdmissionsController::class, 'index'])->name('index');
        Route::get('/crear-examen',                 [AdmissionsController::class, 'create'])->name('create');
        Route::post('/guardar',                     [AdmissionsController::class, 'store'])->name('store');
        Route::get('/editar-examen/{admission}',    [AdmissionsController::class, 'edit'])->name('edit');
        Route::put('/editar-examen/{admission}',    [AdmissionsController::class, 'update'])->name('update');
        Route::delete('/{admission}',               [AdmissionsController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{admission}',         [AdmissionsController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/imagen-cepre',                [AdmissionsController::class, 'updateCepreImage'])->name('update-cepre-image');
        Route::post('/imagen-admision',             [AdmissionsController::class, 'updateAdmissionImage'])->name('update-admission-image');
    });

    // becas
    Route::prefix('admin-scholarships')->name('admin.scholarships.')->group(function () {
        Route::get('/',                             [ScholarshipController::class, 'index'])->name('index');
        Route::get('/crear-beca',                   [ScholarshipController::class, 'create'])->name('create');
        Route::post('/guardar',                     [ScholarshipController::class, 'store'])->name('store');
        Route::get('/editar-beca/{scholarship}',    [ScholarshipController::class, 'edit'])->name('edit');
        Route::put('/editar-beca/{scholarship}',    [ScholarshipController::class, 'update'])->name('update');
        Route::delete('/{scholarship}',             [ScholarshipController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{scholarship}',       [ScholarshipController::class, 'toggleStatus'])->name('toggle-status');
    });

    // tupa
    Route::prefix('admin-tupa')->name('admin.tupa.')->group(function () {
        // Documentos TUPA
        Route::get('/',                     [TupaController::class, 'index'])->name('index');
        Route::get('/crear-tupa',           [TupaController::class, 'create'])->name('create');
        Route::post('/guardar',             [TupaController::class, 'store'])->name('store');
        Route::get('/editar-tupa/{tupa}',   [TupaController::class, 'edit'])->name('edit');
        Route::put('/editar-tupa/{tupa}',   [TupaController::class, 'update'])->name('update');
        Route::delete('/{tupa}',            [TupaController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{tupa}',      [TupaController::class, 'toggleStatus'])->name('toggle-status');

        // Categorías TUPA
        Route::prefix('categorias')->name('categories.')->group(function () {
            Route::get('/crear',                [TupaController::class, 'createCategory'])->name('create');
            Route::post('/guardar',             [TupaController::class, 'storeCategory'])->name('store');
            Route::get('/editar/{category}',    [TupaController::class, 'editCategory'])->name('edit');
            Route::put('/editar/{category}',    [TupaController::class, 'updateCategory'])->name('update');
            Route::delete('/{category}',        [TupaController::class, 'destroyCategory'])->name('destroy');
            Route::patch('/estado/{category}',  [TupaController::class, 'toggleCategoryStatus'])->name('toggle-status');
        });

        // Procedimientos TUPA
        Route::prefix('procedimientos')->name('procedures.')->group(function () {
            Route::get('/crear',                [TupaController::class, 'createProcedure'])->name('create');
            Route::post('/guardar',             [TupaController::class, 'storeProcedure'])->name('store');
            Route::get('/editar/{procedure}',   [TupaController::class, 'editProcedure'])->name('edit');
            Route::put('/editar/{procedure}',   [TupaController::class, 'updateProcedure'])->name('update');
            Route::delete('/{procedure}',       [TupaController::class, 'destroyProcedure'])->name('destroy');
            Route::patch('/estado/{procedure}', [TupaController::class, 'toggleProcedureStatus'])->name('toggle-status');
        });
    });

    // documentos de gestión
    Route::prefix('admin-documentos')->name('admin.documents.')->group(function () {
        Route::get('/',                             [ManagementDocumentController::class, 'index'])->name('index');
        Route::get('/crear',                        [ManagementDocumentController::class, 'create'])->name('create');
        Route::post('/guardar',                     [ManagementDocumentController::class, 'store'])->name('store');
        Route::get('/editar/{managementDocument}',  [ManagementDocumentController::class, 'edit'])->name('edit');
        Route::put('/editar/{managementDocument}',  [ManagementDocumentController::class, 'update'])->name('update');
        Route::delete('/{managementDocument}',      [ManagementDocumentController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{managementDocument}', [ManagementDocumentController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Áreas Institucionales
    Route::prefix('admin-areas')->name('admin.areas.')->group(function () {
        Route::get('/',                 [AreaController::class, 'index'])->name('index');
        Route::get('/crear',            [AreaController::class, 'create'])->name('create');
        Route::post('/guardar',         [AreaController::class, 'store'])->name('store');
        Route::get('/editar/{area}',    [AreaController::class, 'edit'])->name('edit');
        Route::put('/editar/{area}',    [AreaController::class, 'update'])->name('update');
        Route::delete('/{area}',        [AreaController::class, 'destroy'])->name('destroy');
    });

    // programas de estudio
    Route::prefix('admin-programas')->name('admin.programs.')->group(function () {
        // Programas de Estudio
        Route::get('/',                             [StudyProgramsController::class, 'index'])->name('index');
        Route::get('/crear-programa',               [StudyProgramsController::class, 'create'])->name('create');
        Route::post('/guardar',                     [StudyProgramsController::class, 'store'])->name('store');
        Route::get('/editar-programa/{program}',    [StudyProgramsController::class, 'edit'])->name('edit');
        Route::put('/editar-programa/{program}',    [StudyProgramsController::class, 'update'])->name('update');
        Route::delete('/{program}',                 [StudyProgramsController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{program}',           [StudyProgramsController::class, 'toggleStatus'])->name('toggle-status');

        // Certificaciones Modulares (modular_certification)
        Route::prefix('modulos')->name('modules.')->group(function () {
            Route::get('/crear',                [StudyProgramsController::class, 'createModule'])->name('create');
            Route::post('/guardar',             [StudyProgramsController::class, 'storeModule'])->name('store');
            Route::get('/editar/{module}',      [StudyProgramsController::class, 'editModule'])->name('edit');
            Route::put('/editar/{module}',      [StudyProgramsController::class, 'updateModule'])->name('update');
            Route::delete('/{module}',          [StudyProgramsController::class, 'destroyModule'])->name('destroy');
            Route::patch('/estado/{module}',    [StudyProgramsController::class, 'toggleModuleStatus'])->name('toggle-status');
        });

        // Competencias (program_competencies)
        Route::prefix('competencias')->name('competencies.')->group(function () {
            Route::get('/crear',                    [StudyProgramsController::class, 'createCompetency'])->name('create');
            Route::post('/guardar',                 [StudyProgramsController::class, 'storeCompetency'])->name('store');
            Route::get('/editar/{competency}',      [StudyProgramsController::class, 'editCompetency'])->name('edit');
            Route::put('/editar/{competency}',      [StudyProgramsController::class, 'updateCompetency'])->name('update');
            Route::delete('/{competency}',          [StudyProgramsController::class, 'destroyCompetency'])->name('destroy');
            Route::patch('/estado/{competency}',    [StudyProgramsController::class, 'toggleCompetencyStatus'])->name('toggle-status');
        });

        // Campo Laboral (program_job_fields)
        Route::prefix('campo-laboral')->name('job-fields.')->group(function () {
            Route::get('/crear',                [StudyProgramsController::class, 'createJobField'])->name('create');
            Route::post('/guardar',             [StudyProgramsController::class, 'storeJobField'])->name('store');
            Route::get('/editar/{jobField}',    [StudyProgramsController::class, 'editJobField'])->name('edit');
            Route::put('/editar/{jobField}',    [StudyProgramsController::class, 'updateJobField'])->name('update');
            Route::delete('/{jobField}',        [StudyProgramsController::class, 'destroyJobField'])->name('destroy');
            Route::patch('/estado/{jobField}',  [StudyProgramsController::class, 'toggleJobFieldStatus'])->name('toggle-status');
        });

        // Metadata de Presentación (program_metas)
        Route::prefix('metadatos')->name('meta.')->group(function () {
            Route::get('/crear',            [StudyProgramsController::class, 'createMeta'])->name('create');
            Route::post('/guardar',         [StudyProgramsController::class, 'storeMeta'])->name('store');
            Route::get('/editar/{meta}',    [StudyProgramsController::class, 'editMeta'])->name('edit');
            Route::put('/editar/{meta}',    [StudyProgramsController::class, 'updateMeta'])->name('update');
            Route::delete('/{meta}',        [StudyProgramsController::class, 'destroyMeta'])->name('destroy');
        });

        // Requisitos (program_requirements)
        Route::prefix('requisitos')->name('requirements.')->group(function () {
            Route::get('/crear',                [StudyProgramsController::class, 'createRequirement'])->name('create');
            Route::post('/guardar',             [StudyProgramsController::class, 'storeRequirement'])->name('store');
            Route::get('/editar/{requirement}', [StudyProgramsController::class, 'editRequirement'])->name('edit');
            Route::put('/editar/{requirement}', [StudyProgramsController::class, 'updateRequirement'])->name('update');
            Route::delete('/{requirement}',     [StudyProgramsController::class, 'destroyRequirement'])->name('destroy');
            Route::patch('/estado/{requirement}', [StudyProgramsController::class, 'toggleRequirementStatus'])->name('toggle-status');
        });
    });

    // bolsa de trabajo
    Route::prefix('admin-trabajos')->name('admin.works.')->group(function () {
        Route::get('/',                         [JobsController::class, 'index'])->name('index');
        Route::get('/convocatorias-internas',   [JobsController::class, 'internalCalls'])->name('internal-calls');
        Route::get('/crear-oferta',             [JobsController::class, 'create'])->name('create');
        Route::post('/guardar',                 [JobsController::class, 'store'])->name('store');
        Route::get('/{offer}/editar-oferta',    [JobsController::class, 'edit'])->name('edit');
        Route::put('/{offer}',                  [JobsController::class, 'update'])->name('update');
        Route::delete('/{offer}',               [JobsController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{offer}',         [JobsController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/buscar-automatico',        [JobsController::class, 'fetchJobs'])->name('fetch-jobs');
    });
    
    // usuarios
    Route::prefix('admin-usuarios')->name('admin.users.')->group(function () {
        Route::get('/',                 [UsersController::class, 'index'])->name('index');
        Route::get('/crear',            [UsersController::class, 'create'])->name('create');
        Route::get('/{user}/editar/',   [UsersController::class, 'edit'])->name('edit');
        Route::post('/',                [UsersController::class, 'store'])->name('store');
        Route::put('/{user}',           [UsersController::class, 'update'])->name('update');
        Route::patch('/estado/{user}',  [UsersController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}',        [UsersController::class, 'destroy'])->name('destroy');
    });

    // Teacher role details
    Route::prefix('admin-docentes-roles')->name('admin.teacher-roles.')->group(function () {
        Route::get('/',                 [TeacherRoleController::class, 'index'])->name('index');
        Route::post('/guardar',         [TeacherRoleController::class, 'store'])->name('store');
        Route::put('/{teacherRole}',    [TeacherRoleController::class, 'update'])->name('update');
        Route::patch('/estado/{teacherRole}', [TeacherRoleController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{teacherRole}', [TeacherRoleController::class, 'destroy'])->name('destroy');
    });

    // consejo estudiantil
    Route::prefix('admin-consejo-estudiantil')->name('admin.student-council.')->group(function () {
        Route::get('/',                 [StudentCouncilController::class, 'index'])->name('index');
        Route::post('/guardar',         [StudentCouncilController::class, 'store'])->name('store');
        Route::put('/{studentCouncil}', [StudentCouncilController::class, 'update'])->name('update');
        Route::patch('/estado/{studentCouncil}', [StudentCouncilController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{studentCouncil}', [StudentCouncilController::class, 'destroy'])->name('destroy');
    });

    // enlaces institucionales
    Route::prefix('admin-enlaces')->name('admin.links.')->group(function () {
        Route::get('/',                 [ExternalInstitutionalLinkController::class, 'index'])->name('index');
        Route::get('/crear',            [ExternalInstitutionalLinkController::class, 'create'])->name('create');
        Route::post('/guardar',         [ExternalInstitutionalLinkController::class, 'store'])->name('store');
        Route::get('/{link}/editar',    [ExternalInstitutionalLinkController::class, 'edit'])->name('edit');
        Route::put('/{link}',           [ExternalInstitutionalLinkController::class, 'update'])->name('update');
        Route::patch('/estado/{link}',  [ExternalInstitutionalLinkController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{link}',        [ExternalInstitutionalLinkController::class, 'destroy'])->name('destroy');
    });

    // roles
    Route::prefix('admin-roles')->name('admin.roles.')->group(function () {
        Route::get('/',                 [RoleController::class, 'index'])->name('index');
        Route::get('/crear',            [RoleController::class, 'create'])->name('create');
        Route::post('/guardar',         [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/editar',    [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}',           [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}',        [RoleController::class, 'destroy'])->name('destroy');
    });

    // reclamos
    Route::prefix('admin-reclamos')->name('admin.claims.')->group(function () {
        Route::get('/',                 [ClaimsController::class, 'index'])->name('index');
        Route::get('/{claim}',          [ClaimsController::class, 'show'])->name('show');
        Route::patch('/estado/{claim}',  [ClaimsController::class, 'status'])->name('status');
    });

    // partners
    Route::prefix('admin-socios')->name('admin.partners.')->group(function () {
        Route::get('/',                 [PartnersController::class, 'index'])->name('index');
        Route::get('/crear',            [PartnersController::class, 'create'])->name('create');
        Route::post('/guardar',         [PartnersController::class, 'store'])->name('store');
        Route::get('/{partner}/editar', [PartnersController::class, 'edit'])->name('edit');
        Route::put('/{partner}',        [PartnersController::class, 'update'])->name('update');
        Route::patch('/estado/{partner}', [PartnersController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{partner}',     [PartnersController::class, 'destroy'])->name('destroy');
    });

    // Rutas para gestión de empresa
    Route::prefix('admin-empresa')->name('admin.enterprise.')->group(function () {
        Route::get('/editar',       [EnterpriseController::class, 'edit'])->name('edit');
        Route::put('/{enterprise}', [EnterpriseController::class, 'update'])->name('update');
    });

    // Cronogramas de matrícula
    Route::prefix('admin-matriculas')->name('admin.enrollments.')->group(function () {
        Route::get('/',                      [EnrollmentScheduleController::class, 'index'])->name('index');
        Route::get('/crear',                 [EnrollmentScheduleController::class, 'create'])->name('create');
        Route::post('/guardar',              [EnrollmentScheduleController::class, 'store'])->name('store');
        Route::get('/editar/{schedule}',     [EnrollmentScheduleController::class, 'edit'])->name('edit');
        Route::put('/editar/{schedule}',     [EnrollmentScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}',         [EnrollmentScheduleController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{schedule}',   [EnrollmentScheduleController::class, 'toggleStatus'])->name('toggle-status');
    });
});

require __DIR__.'/auth.php';
