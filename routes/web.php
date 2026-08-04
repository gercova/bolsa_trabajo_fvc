<?php

use App\Http\Controllers\AdmissionsController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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
Route::get('/programas-de-estudios/{program:slug}', [AppController::class, 'program']);
Route::redirect('/study-programs', '/programas-de-estudios');

// Transparencia
Route::get('/transparencia/documentos-de-gestion',    [AppController::class, 'documentsManagement'])->name('documentos-de-gestion');
Route::get('/transparencia/estadisticas',             [AppController::class, 'statistics'])->name('estadisticas');
Route::get('/transparencia/inversion-y-gestion',      [AppController::class, 'managementReports'])->name('inversion-y-gestion');
Route::get('/transparencia/licenciamiento',           [AppController::class, 'licensment'])->name('licenciamiento');
Route::get('/transparencia/libro-de-reclamaciones',   [AppController::class, 'complaintsBook'])->name('libro-de-reclamaciones');
Route::post('/transparencia/libro-de-reclamaciones',  [AppController::class, 'storeClaim'])->name('libro-de-reclamaciones.store');

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
Route::get('/servicios/bolsa-de-trabajo',   [AppController::class, 'offers'])->name('bolsa-de-trabajo');

// login y registro
Route::get('/register',                     [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register',                    [RegisteredUserController::class, 'store']);
Route::get('/login',                        [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login',                       [AuthenticatedSessionController::class, 'login'])->middleware('guest');
Route::post('/logout',                      [AuthenticatedSessionController::class, 'logout'])->name('logout');
Route::get('forgot-password',               [NewPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password',              [NewPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Restablecer contraseña
Route::get('reset-password/{token}',        [PasswordResetLinkController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password',               [PasswordResetLinkController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin-dashboard')->name('admin.dashboard.')->group(function () {
        Route::get('/',    [DashboardController::class, 'index'])->name('index');
    });

    Route::prefix('admin-perfil')->name('admin.profile.')->group(function () {
        Route::get('/{user}',   [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/',       [ProfileController::class, 'update'])->name('update');
        Route::delete('/',      [ProfileController::class, 'destroy'])->name('destroy');    
    });

    Route::prefix('admin-exams')->name('admin.exams.')->group(function () {
        Route::get('/',                             [AdmissionsController::class, 'index'])->name('index');
        Route::get('/crear-examen',                 [AdmissionsController::class, 'create'])->name('create');
        Route::post('/guardar',                     [AdmissionsController::class, 'store'])->name('store');
        Route::get('/editar-examen/{admission}',    [AdmissionsController::class, 'edit'])->name('edit');
        Route::put('/editar-examen/{admission}',    [AdmissionsController::class, 'update'])->name('update');
        Route::delete('/{admission}',               [AdmissionsController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{admission}',         [AdmissionsController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('admin-tupa')->name('admin.tupa.')->group(function () {
        // Documentos TUPA
        Route::get('/',                             [TupaController::class, 'index'])->name('index');
        Route::get('/crear-tupa',                   [TupaController::class, 'create'])->name('create');
        Route::post('/guardar',                     [TupaController::class, 'store'])->name('store');
        Route::get('/editar-tupa/{tupa}',           [TupaController::class, 'edit'])->name('edit');
        Route::put('/editar-tupa/{tupa}',           [TupaController::class, 'update'])->name('update');
        Route::delete('/{tupa}',                    [TupaController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{tupa}',              [TupaController::class, 'toggleStatus'])->name('toggle-status');

        // Categorías TUPA
        Route::prefix('categorias')->name('categories.')->group(function () {
            Route::get('/crear',                    [TupaController::class, 'createCategory'])->name('create');
            Route::post('/guardar',                  [TupaController::class, 'storeCategory'])->name('store');
            Route::get('/editar/{category}',        [TupaController::class, 'editCategory'])->name('edit');
            Route::put('/editar/{category}',        [TupaController::class, 'updateCategory'])->name('update');
            Route::delete('/{category}',             [TupaController::class, 'destroyCategory'])->name('destroy');
            Route::patch('/estado/{category}',      [TupaController::class, 'toggleCategoryStatus'])->name('toggle-status');
        });

        // Procedimientos TUPA
        Route::prefix('procedimientos')->name('procedures.')->group(function () {
            Route::get('/crear',                    [TupaController::class, 'createProcedure'])->name('create');
            Route::post('/guardar',                  [TupaController::class, 'storeProcedure'])->name('store');
            Route::get('/editar/{procedure}',      [TupaController::class, 'editProcedure'])->name('edit');
            Route::put('/editar/{procedure}',      [TupaController::class, 'updateProcedure'])->name('update');
            Route::delete('/{procedure}',           [TupaController::class, 'destroyProcedure'])->name('destroy');
            Route::patch('/estado/{procedure}',    [TupaController::class, 'toggleProcedureStatus'])->name('toggle-status');
        });
    });

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
            Route::get('/crear',                    [StudyProgramsController::class, 'createModule'])->name('create');
            Route::post('/guardar',                  [StudyProgramsController::class, 'storeModule'])->name('store');
            Route::get('/editar/{module}',          [StudyProgramsController::class, 'editModule'])->name('edit');
            Route::put('/editar/{module}',          [StudyProgramsController::class, 'updateModule'])->name('update');
            Route::delete('/{module}',               [StudyProgramsController::class, 'destroyModule'])->name('destroy');
            Route::patch('/estado/{module}',        [StudyProgramsController::class, 'toggleModuleStatus'])->name('toggle-status');
        });

        // Competencias (program_competencies)
        Route::prefix('competencias')->name('competencies.')->group(function () {
            Route::get('/crear',                    [StudyProgramsController::class, 'createCompetency'])->name('create');
            Route::post('/guardar',                  [StudyProgramsController::class, 'storeCompetency'])->name('store');
            Route::get('/editar/{competency}',      [StudyProgramsController::class, 'editCompetency'])->name('edit');
            Route::put('/editar/{competency}',      [StudyProgramsController::class, 'updateCompetency'])->name('update');
            Route::delete('/{competency}',           [StudyProgramsController::class, 'destroyCompetency'])->name('destroy');
            Route::patch('/estado/{competency}',    [StudyProgramsController::class, 'toggleCompetencyStatus'])->name('toggle-status');
        });

        // Campo Laboral (program_job_fields)
        Route::prefix('campo-laboral')->name('job-fields.')->group(function () {
            Route::get('/crear',                    [StudyProgramsController::class, 'createJobField'])->name('create');
            Route::post('/guardar',                  [StudyProgramsController::class, 'storeJobField'])->name('store');
            Route::get('/editar/{jobField}',        [StudyProgramsController::class, 'editJobField'])->name('edit');
            Route::put('/editar/{jobField}',        [StudyProgramsController::class, 'updateJobField'])->name('update');
            Route::delete('/{jobField}',             [StudyProgramsController::class, 'destroyJobField'])->name('destroy');
            Route::patch('/estado/{jobField}',      [StudyProgramsController::class, 'toggleJobFieldStatus'])->name('toggle-status');
        });

        // Metadata de Presentación (program_metas)
        Route::prefix('metadatos')->name('meta.')->group(function () {
            Route::get('/crear',                    [StudyProgramsController::class, 'createMeta'])->name('create');
            Route::post('/guardar',                  [StudyProgramsController::class, 'storeMeta'])->name('store');
            Route::get('/editar/{meta}',            [StudyProgramsController::class, 'editMeta'])->name('edit');
            Route::put('/editar/{meta}',            [StudyProgramsController::class, 'updateMeta'])->name('update');
            Route::delete('/{meta}',                 [StudyProgramsController::class, 'destroyMeta'])->name('destroy');
        });

        // Requisitos (program_requirements)
        Route::prefix('requisitos')->name('requirements.')->group(function () {
            Route::get('/crear',                    [StudyProgramsController::class, 'createRequirement'])->name('create');
            Route::post('/guardar',                  [StudyProgramsController::class, 'storeRequirement'])->name('store');
            Route::get('/editar/{requirement}',     [StudyProgramsController::class, 'editRequirement'])->name('edit');
            Route::put('/editar/{requirement}',     [StudyProgramsController::class, 'updateRequirement'])->name('update');
            Route::delete('/{requirement}',          [StudyProgramsController::class, 'destroyRequirement'])->name('destroy');
            Route::patch('/estado/{requirement}',   [StudyProgramsController::class, 'toggleRequirementStatus'])->name('toggle-status');
        });
    });

    Route::prefix('admin-trabajos')->name('admin.works.')->group(function () {
        Route::get('/',                         [JobsController::class, 'index'])->name('index');
        Route::get('/convocatorias-internas',   [JobsController::class, 'internalCalls'])->name('internal-calls');
        Route::get('/crear-oferta',             [JobsController::class, 'create'])->name('create');
        Route::post('/guardar',                 [JobsController::class, 'store'])->name('store');
        Route::get('/{offer}/editar-oferta',    [JobsController::class, 'edit'])->name('edit');
        Route::put('/{offer}',                  [JobsController::class, 'update'])->name('update');
        Route::delete('/{offer}',               [JobsController::class, 'destroy'])->name('destroy');
        Route::patch('/estado/{offer}',         [JobsController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    Route::prefix('admin-usuarios')->name('admin.users.')->group(function () {
        Route::get('/',                 [UsersController::class, 'index'])->name('index');
        Route::get('/crear',            [UsersController::class, 'create'])->name('create');
        Route::get('/{user}/editar/',   [UsersController::class, 'edit'])->name('edit');
        Route::post('/',                [UsersController::class, 'store'])->name('store');
        Route::put('/{user}',           [UsersController::class, 'update'])->name('update');
        Route::patch('/estado/{user}',  [UsersController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}',        [UsersController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin-roles')->name('admin.roles.')->group(function () {
        Route::get('/',                 [RoleController::class, 'index'])->name('index');
        Route::get('/crear',            [RoleController::class, 'create'])->name('create');
        Route::post('/guardar',         [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/editar',    [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}',           [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}',        [RoleController::class, 'destroy'])->name('destroy');
    });

    // Teacher role details
    Route::prefix('admin-docentes-roles')->name('admin.teacher-roles.')->group(function () {
        Route::get('/',                            [TeacherRoleController::class, 'index'])->name('index');
        Route::post('/guardar',                    [TeacherRoleController::class, 'store'])->name('store');
        Route::put('/{teacherRole}',               [TeacherRoleController::class, 'update'])->name('update');
        Route::patch('/estado/{teacherRole}',      [TeacherRoleController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{teacherRole}',            [TeacherRoleController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin-reclamos')->name('admin.claims.')->group(function () {
        Route::get('/',                 [ClaimsController::class, 'index'])->name('index');
        Route::get('/{claim}',          [ClaimsController::class, 'show'])->name('show');
        Route::post('/estado/{claim}',  [ClaimsController::class, 'status'])->name('status');
    });

    Route::prefix('admin-socios')->name('admin.partners.')->group(function () {
        Route::get('/',                 [PartnersController::class, 'index'])->name('index');
        Route::get('/crear',            [PartnersController::class, 'create'])->name('create');
        Route::post('/guardar',         [PartnersController::class, 'store'])->name('store');
        Route::get('/{partner}/editar', [PartnersController::class, 'edit'])->name('edit');
        Route::put('/{partner}',        [PartnersController::class, 'update'])->name('update');
        Route::post('/estado/{partner}', [PartnersController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{partner}',     [PartnersController::class, 'destroy'])->name('destroy');
    });

    // Rutas para gestión de empresa
    Route::prefix('admin-empresa')->name('admin.enterprise.')->group(function () {
        Route::get('/editar',       [EnterpriseController::class, 'edit'])->name('edit');
        Route::put('/{enterprise}', [EnterpriseController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php';
