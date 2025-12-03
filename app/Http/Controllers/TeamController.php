<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    // ==========================================
    // PARTE 1: CREAR EQUIPOS
    // ==========================================

    // 1. Mostrar el formulario de crear equipo
    public function create($event_id = 1)
    {
        // Pasamos el ID del evento a la vista (por defecto 1 si no hay ninguno)
        return view('Inscription.CrearTeam', compact('event_id'));
    }

    // 2. Guardar el nuevo equipo en la Base de Datos
    public function store(Request $request)
    {
        // Validamos los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'leader_email' => 'required|email',
            'leader_career' => 'required|string',
            'leader_semester' => 'required|string',
            'max_members' => 'required|integer|min:2',
            'visibility' => 'required|in:Privado,Público',
            'event_id' => 'required|integer', // Asegúrate de que tu form envíe esto (hidden input)
        ]);

        // Generamos código único
        $inviteCode = 'ITO-' . strtoupper(Str::random(4)) . '-TEAM';

        // Creamos el equipo
        Team::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'name' => $request->name,
            'leader_name' => $request->leader_name,
            'leader_email' => $request->leader_email,
            'leader_career' => $request->leader_career,
            'leader_semester' => $request->leader_semester,
            'leader_experience' => $request->leader_experience,
            'max_members' => $request->max_members,
            'visibility' => $request->visibility,
            'requirements' => $request->requirements,
            'invite_code' => $inviteCode,
        ]);

        return back()->with('success_code', $inviteCode);
    }

    // ==========================================
    // PARTE 2: UNIRSE A EQUIPOS
    // ==========================================

    // 3. Mostrar la vista de Unirse (y cargar equipos públicos)
    public function join()
    {
        // Traemos solo los equipos que sean 'Público'
        $publicTeams = Team::where('visibility', 'Público')->get();
        
        return view('Inscription.JoinTeam', compact('publicTeams'));
    }

    // 4. Procesar la unión mediante Código (Pestaña Privada)
    public function processJoin(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string',
            'career'      => 'required|string',
            'phone'       => 'required|string',
        ]);

        // Buscamos el equipo
        $team = Team::where('invite_code', $request->invite_code)->first();

        if (!$team) {
            return back()->withErrors(['invite_code' => 'El código no es válido.']);
        }

        // Verificar si está lleno (Líder + Miembros)
        $currentMembers = TeamMember::where('team_id', $team->id)->count();
        // +1 porque el líder también cuenta como integrante
        if (($currentMembers + 1) >= $team->max_members) {
            return back()->withErrors(['invite_code' => 'Este equipo ya está lleno.']);
        }

        // Guardar al miembro
        TeamMember::create([
            'team_id' => $team->id,
            'name'    => Auth::user()->name,
            'email'   => Auth::user()->email,
            'career'  => $request->career,
            'phone'   => $request->phone,
        ]);

        return redirect()->route('dashboard')->with('status', '¡Te has unido al equipo ' . $team->name . ' correctamente!');
    }
}
