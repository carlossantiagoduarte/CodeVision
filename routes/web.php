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
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
*/

// Ruta para la página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('welcome');  // Página de bienvenida

// Ruta de logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Rutas para el login y registro personalizadas
Route::get('/login', function () {
    return view('Inscription.login');
})->name('login');

Route::get('/register', function () {
    return view('Inscription.register');
})->name('register');

// Rutas para la autenticación con Google
Route::get('login/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->middleware('web');

// Rutas de Dashboard (Solo usuarios verificados)
Route::get('/dashboard', function () {
    $user = Auth::user();
    // Traemos los eventos de la base de datos
    $events = Event::latest()->get(); 
    // Enviamos tanto al usuario como a los eventos ('events') a la vista
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

    // Ruta para ver MIS EQUIPOS
    Route::get('/mis-equipos', [TeamController::class, 'myTeams'])->name('teams.index');
    
    // --- RUTAS PARA CREAR EQUIPOS (NUEVAS) ---
    // 1. Mostrar el formulario (acepta un ID de evento opcional)
    Route::get('/crear-equipo/{event_id?}', [TeamController::class, 'create'])->name('teams.create');
    
    // 2. Recibir y guardar los datos del formulario en la BD
    Route::post('/equipos', [TeamController::class, 'store'])->name('teams.store');
});

// Rutas de eventos
// Ruta para crear un evento
Route::get('/crear-evento', [EventController::class, 'create'])->name('events.create');
// Ruta para almacenar el evento
Route::get('/eventos', [EventController::class, 'store'])->name('events.store');
// Ruta correcta para el detalle del evento (usando GET)
Route::get('/event/{id}', [EventController::class, 'show'])->name('event.details');


// Ruta para mostrar el formulario de creación de evento
Route::get('/event/create', [EventController::class, 'create'])->name('event.create');

// Ruta para almacenar un evento
Route::get('/event', [EventController::class, 'store'])->name('event.store');

Route::get('/informacion_evento', function () {
    // 1. Obtén el evento (Ejemplo: el primer evento encontrado)
    $event = Event::first(); // O usa otra lógica para obtener el evento

    // 2. Pasa la variable a la vista
    return view('Dashboard.EventInformation', compact('event')); 

})->name('informacion.evento');

// --- RUTAS DE AUTENTICACIÓN BASE DE LARAVEL ---
require __DIR__.'/auth.php';
