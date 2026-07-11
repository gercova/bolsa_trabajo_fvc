<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudyProgramsController;
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
Route::get('/nosotros/plana-jerarquica',            [AppController::class, 'teachersStaff'])->name('plana-jerarquica');
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

    Route::prefix('admin-programas')->name('admin.programs.')->group(function () {
        Route::get('/',                             [StudyProgramsController::class, 'index'])->name('index');
        Route::get('/crear-programa',               [StudyProgramsController::class, 'create'])->name('create');
        Route::post('/guardar',                     [StudyProgramsController::class, 'store'])->name('store');
        Route::get('/editar-programa/{program}',    [StudyProgramsController::class, 'edit'])->name('edit');
        Route::get('/{program}',                    [StudyProgramsController::class, 'update'])->name('update');
        Route::post('/estado/{program}',            [StudyProgramsController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{program}',                    [StudyProgramsController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin-trabajos')->name('admin.works.')->group(function () {
        Route::get('/',                         [JobsController::class, 'index'])->name('index');
        Route::get('/convocatorias-internas',   [JobsController::class, 'internalCalls'])->name('internal-calls');
        Route::get('/crear-oferta',             [JobsController::class, 'create'])->name('create');
        Route::post('/guardar',                 [JobsController::class, 'store'])->name('store');
        Route::get('/{offer}/editar-oferta',    [JobsController::class, 'edit'])->name('edit');
        Route::put('/{offer}',                  [JobsController::class, 'update'])->name('update');
        Route::delete('/{offer}',               [JobsController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('admin-usuarios')->name('admin.users.')->group(function () {
        Route::get('/',                 [UsersController::class, 'index'])->name('index');
        Route::get('/crear',            [UsersController::class, 'create'])->name('create');
        Route::get('/{user}/editar/',   [UsersController::class, 'edit'])->name('edit');
        Route::post('/',                [UsersController::class, 'store'])->name('store');
        Route::put('/{user}',           [UsersController::class, 'update'])->name('update');
        Route::post('/estado/{user}',   [UsersController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}',        [UsersController::class, 'destroy'])->name('destroy');
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
