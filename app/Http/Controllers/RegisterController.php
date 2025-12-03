<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    // Muestra el formulario (GET)
    public function create()
    {
        return view('Inscription.Register'); // Asegúrate de que la ruta a tu vista sea correcta
    }

    // Guarda los datos (POST)
    public function store(Request $request)
    {
        // 1. Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' busca password_confirmation
        ]);

        // 2. Crear el Usuario
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // ¡Encriptar contraseña!
        ]);

        // 3. Enviar correo de verificación (si está habilitado)
        event(new Registered($user)); // Este evento envía el correo de verificación si la verificación está habilitada

        // 4. Iniciar sesión automáticamente
        Auth::login($user);

        // 5. Redirigir al usuario, pero solo al Dashboard si el correo está verificado
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        } else {
            // Si no está verificado, redirigir a la página de verificación
            return redirect()->route('verification.notice');
        }
    }
}
