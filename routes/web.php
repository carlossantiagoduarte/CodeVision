<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\EventController; 
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RatingController; // Asumo que tienes o tendrás un controlador para guardar ratings.

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas Públicas / Invitado
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/login', function () {
    return view('Inscription.login');
})->name('login');
Route::get('/register', function () {
    return view('Inscription.register');
})->name('register');
Route::get('/evento/editar-ultimo', [EventController::class, 'editLast'])->name('events.edit.last')->middleware('can:crear eventos');
// Rutas para la autenticación con Google
Route::get('login/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->middleware('web');


// Ruta para el Dashboard (Solo usuarios verificados)
Route::get('/dashboard', function () {
    $user = Auth::user();
    $events = Event::latest()->get(); 
    return view('Dashboard', compact('user', 'events'));
})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTAS PROTEGIDAS (Requieren inicio de sesión) ---
Route::middleware('auth')->group(function () {
    
    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de EVENTOS (Protegidas por permiso)
    Route::get('/crear-evento', [EventController::class, 'create'])->name('events.create')->middleware('can:crear eventos');
    Route::post('/eventos', [EventController::class, 'store'])->name('events.store')->middleware('can:crear eventos');
    Route::get('/evento/editar/{id}', [EventController::class, 'edit'])->name('events.edit')->middleware('can:crear eventos');
    
    // *******************************************************************
    // 🛑 RUTA AÑADIDA: Detalle del evento (Carga EventInformation.blade.php)
    // *******************************************************************
    Route::get('/eventos/{event}', [EventController::class, 'show'])->name('events.show');


    // Rutas de EQUIPOS
    Route::get('/unirse-equipo', [TeamController::class, 'join'])->name('teams.join');
    Route::post('/unirse-equipo', [TeamController::class, 'processJoin'])->name('teams.processJoin');
    Route::get('/mis-equipos', [TeamController::class, 'myTeams'])->name('teams.index');
    Route::get('/crear-equipo/{event_id?}', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/equipos', [TeamController::class, 'store'])->name('teams.store');

    // RUTA PARA EL ENVÍO DE PROYECTOS (Protegida por permiso)
    Route::get('/envio', function () {
        return view('Dashboard.Envio');
    })->name('envio')->middleware('can:enviar proyecto');

    // RUTA DE CALIFICACIÓN (Protegida por permiso)
    Route::get('/calificar-equipo', [TeamController::class, 'calificarEquipo'])
        ->name('teams.calificar')
        ->middleware('can:calificar'); 
});

// Rutas de autenticación base de Laravel
require __DIR__.'/auth.php';
