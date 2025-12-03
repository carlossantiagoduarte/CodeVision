<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller


{
    // Redirigir al usuario a Google para autenticar
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

     public function showProfile()
    {
        $user = Auth::user();  // Obtener el usuario autenticado
        return view('Perfil', compact('user'));  // Cambié de 'profile.Perfil' a 'Perfil'
    }

    // Manejar la respuesta de Google
    public function handleGoogleCallback()
    {
        // Obtener la información del usuario desde Google
        $googleUser = Socialite::driver('google')->user();

        // Verificar si el usuario ya existe en la base de datos
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Si no encontramos el usuario, lo creamos sin necesidad de contraseña
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                // No se incluye 'password' porque no es necesario para login con Google
            ]);
        }

        // Iniciamos sesión para el usuario
        Auth::login($user);

        // Redirigimos al usuario a la página principal o al dashboard
        return redirect()->route('dashboard');
    }
}
