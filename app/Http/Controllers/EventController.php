<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipo; // Mantengo la importación que tenías, si es Team, cámbiala.

class EventController extends Controller
{
    // READ: Muestra la lista de eventos o equipos (Si es usada)
    public function index()
    {
        // Asumiendo que Equipo es tu modelo de equipo o debe ser Team
        $equipos = Equipo::all(); 

        return view('equipos.index', compact('equipos'));
    }

    // CREATE: Mostrar el formulario de creación
    public function create()
    {
        return view('Inscription.Newevent');
    }

    // STORE: Guardar el evento en la BD
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

        // 4. Redireccionar al Dashboard con mensaje de éxito (Resuelve el problema de la página en blanco)
        return redirect()->route('dashboard')->with('status', '¡Evento creado exitosamente!');
    }

    // *************************************************************************************
    // READ: Muestra la información detallada de un evento (events.show)
    // *************************************************************************************
    public function show(Event $event) // Usa Route Model Binding
    {
        // 🛑 Protección de Acceso
        if (!auth()->user()->can('ver eventos')) {
            abort(403, 'No tienes permiso para ver los detalles del evento.');
        }

        // Asumo que la vista está en 'EventInformation.blade.php'
        return view('EventInformation', compact('event'));
    }

    // *************************************************************************************
    // READ: Muestra formulario de edición del último evento (events.edit.last)
    // *************************************************************************************
    public function editLast(Request $request)
    {
        // 🛑 Protección de Acceso: Solo quien puede crear eventos (Admin)
        if (!auth()->user()->can('crear eventos')) {
            abort(403, 'No tienes permiso para acceder a la edición de eventos.');
        }
        
        // Obtenemos el último evento.
        $event = Event::latest()->first();
        
        // ✅ CORRECCIÓN CLAVE: Si no hay eventos, redirigir y TERMINAR la ejecución
        if (!$event) {
            return redirect()->route('dashboard')->with('error', 'No hay eventos para editar. Crea uno primero.');
        }

        // Si SÍ hay un evento, $event está definido y lo pasamos a la vista.
        // NOTA: Si usas esta ruta para un formulario de edición, esta vista debe ser 'edit-event'
        // Si la usas para ver la información del último evento, usa 'EventInformation'
        return view('EventInformation', compact('event'));
    }


    // *************************************************************************************
    // UPDATE: Actualizar evento en la base de datos (events.update)
    // *************************************************************************************
    public function update(Request $request, Event $event)
    {
        // 🛑 Protección de Acceso: Solo quien puede crear eventos (Admin)
        if (!auth()->user()->can('crear eventos')) {
            abort(403, 'No tienes permiso para actualizar eventos.');
        }
        
        // Validar los datos
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

        // Actualizar el evento
        $event->update($validated);

        // Redirigir al evento actualizado
        return redirect()->route('events.show', $event->id)->with('status', '¡Evento actualizado exitosamente!');
    }

    // NOTA: El método 'info' que tenías en tu código no se usa con la estructura actual
    // y ha sido reemplazado por la funcionalidad de 'show' y 'editLast'.
}
