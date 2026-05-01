<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/',         [AppController::class, 'index'])->name('inicio');
Route::get('/ofertas',  [AppController::class, 'offers'])->name('ofertas');
Route::get('/nosotros', [AppController::class, 'aboutus'])->name('nosotros');

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
    Route::get('/dashboard',    [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile/{user}',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',        [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',       [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/trabajos',         [JobController::class, 'index'])->name('admin.trabajos');
    Route::get('/internal-calls',   [JobController::class, 'internalCalls'])->name('admin.convocatorias-internas');
    
    Route::get('/usuarios',                         [UsersController::class, 'index'])->name('admin.usuarios');
    Route::get('/usuarios/create',                  [UsersController::class, 'create'])->name('admin.usuarios.create');
    Route::get('/usuarios/{user}/edit/',            [UsersController::class, 'edit'])->name('admin.usuarios.edit');
    Route::post('/usuarios',                        [UsersController::class, 'store'])->name('admin.usuarios.store');
    Route::put('/usuarios/{user}',                  [UsersController::class, 'update'])->name('admin.usuarios.update');
    Route::post('/usuarios/toggleStatus/{user}',    [UsersController::class, 'toggleStatus'])->name('admin.usuarios.toggle-status');
    Route::delete('/usuarios/{user}',               [UsersController::class, 'destroy'])->name('admin.usuarios.destroy');

    // Rutas para gestión de partners
    Route::get('/partners',                 [PartnersController::class, 'index'])->name('admin.partners.index');
    Route::get('/partners/create',          [PartnersController::class, 'create'])->name('admin.partners.create');
    Route::get('/partners/{partner}/edit',  [PartnersController::class, 'edit'])->name('admin.partners.edit');
    Route::put('/partners/{partner}',       [PartnersController::class, 'update'])->name('admin.partners.update');
    Route::post('/partners/toggleStatus/{user}',    [PartnersController::class, 'toggleStatus'])->name('admin.partners.toggle-status');
    Route::delete('/partners/{user}',               [PartnersController::class, 'destroy'])->name('admin.partners.destroy');

    // Rutas para gestión de empresa
    Route::get('/enterprise',       [EnterpriseController::class, 'edit'])->name('admin.enterprise.edit');
    Route::put('/enterprise',       [EnterpriseController::class, 'update'])->name('admin.enterprise.update');
});

require __DIR__.'/auth.php';
