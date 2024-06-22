<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Blitzvideo\UserController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('dashboard', function () {
    return 'You are logged in!';
})->middleware('auth');

Route::prefix('blitzvideo')->group(function () {
    Route::get('/users', [UserController::class, 'listarTodosLosUsuarios']);
    Route::get('/users/{id}', [UserController::class, 'listarUsuarioPorId']);
    Route::get('/users/nombre/{nombre}', [UserController::class, 'listarUsuariosPorNombre']);
});
