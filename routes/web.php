<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

// Ruta para la página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Ruta de logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Ruta para el login (usando la vista personalizada)
Route::get('/login', function () {
    return view('Inscription.login');  // Asegúrate de que la vista 'login.blade.php' esté en 'resources/views/Inscription'
})->name('login');

// Rutas para la autenticación con Google
Route::get('login/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->middleware('web');

// Ruta para el registro (usando la vista personalizada)
Route::get('/register', function () {
    return view('Inscription.register');  // Asegúrate de que la vista 'register.blade.php' esté en 'resources/views/Inscription'
})->name('register');

// Ruta para el Dashboard, solo accesible para usuarios autenticados y verificados
Route::get('/dashboard', function () {
    $user = Auth::user();  // Obtener el usuario autenticado
    return view('Dashboard', compact('user'));  // Pasa el usuario a la vista 'Dashboard'
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por autenticación para el perfil del usuario
Route::middleware('auth')->group(function () {
    // Ruta para editar el perfil (usando la vista personalizada)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Ruta para actualizar el perfil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Ruta para eliminar el perfil
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de autenticación (Login y Register) si no usas Laravel Breeze o Laravel UI
require __DIR__.'/auth.php';  // Asegúrate de que este archivo 'auth.php' esté presente y contenga las rutas de autenticación
