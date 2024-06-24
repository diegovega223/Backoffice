<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Blitzvideo\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('blitzvideo')->group(function () {
        Route::get('/users', [UserController::class, 'listarTodosLosUsuarios']);
        Route::get('/users/{id}', [UserController::class, 'listarUsuarioPorId']);
        Route::get('/users/nombre/{nombre}', [UserController::class, 'listarUsuariosPorNombre']);
    });
});
