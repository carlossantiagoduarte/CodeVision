<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    // Muestra la vista de login (GET)
    public function showLoginForm()
    {
        return view('Inscription.Login');  // Asegúrate de que esta vista exista
    }

    // Procesa el inicio de sesión (POST)
    public function login(Request $request)
    {
        // 1. Validamos los datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentamos iniciar sesión (Auth::attempt encripta y compara automáticamente)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Seguridad extra

            // 3. Verificamos si el usuario está verificado
            if (Auth::user()->hasVerifiedEmail()) {
                return redirect()->intended('dashboard'); // Redirigimos al Dashboard si está verificado
            } else {
                // Si no está verificado, redirigir a la página de verificación
                Auth::logout();
                return redirect()->route('verification.notice');
            }
        }

        // 4. Si falla (contraseña incorrecta o email no existe):
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Cerrar sesión (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Redirigir a Google para autenticarse
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Manejar la respuesta de Google y registrar/autorizar al usuario
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Verificar si el usuario ya existe en la base de datos
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Si el usuario existe, loguearlo
            Auth::login($user);
        } else {
            // Si el usuario no existe, crear uno nuevo
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
            ]);
            Auth::login($user);
        }

        // Redirigir al usuario a la página principal
        return redirect()->to('/home');
    }
}
