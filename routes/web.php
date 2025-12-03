<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\EventController; 
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta para la página de inicio
// Ruta para la página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('welcome');  // <--- ¡ESTA ES LA PARTE QUE FALTA!

// Ruta de logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Rutas para el login y registro personalizadas
Route::get('/login', function () {
    return view('Inscription.login');
})->name('login');

Route::get('/register', function () {
    return view('Inscription.register');
})->name('register');
Route::get('/crear-evento', [EventController::class, 'create'])->name('events.create');
Route::post('/eventos', [EventController::class, 'store'])->name('events.store');
// Rutas para la autenticación con Google
Route::get('login/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->middleware('web');

// Ruta para el Dashboard (Solo usuarios verificados)
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // 2. Traemos los eventos de la base de datos
    $events = Event::latest()->get(); 

    // 3. Enviamos TANTO al usuario COMO a los eventos ('events') a la vista
    return view('Dashboard', compact('user', 'events'));

})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTAS PROTEGIDAS (Requieren inicio de sesión) ---
Route::middleware('auth')->group(function () {
    
    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para UNIRSE A EQUIPO
    Route::get('/unirse-equipo', [TeamController::class, 'join'])->name('teams.join');
    Route::post('/unirse-equipo', [TeamController::class, 'processJoin'])->name('teams.processJoin');
    // --- RUTAS PARA CREAR EQUIPOS (NUEVAS) ---
    
    // 1. Mostrar el formulario (acepta un ID de evento opcional)
    Route::get('/crear-equipo/{event_id?}', [TeamController::class, 'create'])->name('teams.create');
    
    // 2. Recibir y guardar los datos del formulario en la BD
    Route::post('/equipos', [TeamController::class, 'store'])->name('teams.store');

});

// Rutas de autenticación base de Laravel
require __DIR__.'/auth.php';
