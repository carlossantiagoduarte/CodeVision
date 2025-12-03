<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Mostrar el formulario
    public function create()
    {
        return view('Inscription.Newevent'); // Asegúrate que este sea el nombre correcto de tu archivo Blade
    }

    // Guardar el evento en la BD
    public function store(Request $request)
    {
        // 1. Validar los datos
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'max_participants' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image_url' => 'nullable|url',
            'documents_info' => 'nullable|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // 2. Agregar el ID del usuario logueado al array de datos
        $validated['user_id'] = Auth::id();

        // 3. Crear el evento
        Event::create($validated);

        // 4. Redireccionar al Dashboard con mensaje de éxito
        // (Asegúrate de tener una ruta llamada 'dashboard')
        return redirect()->route('dashboard')->with('status', '¡Evento creado exitosamente!');
    }
}
