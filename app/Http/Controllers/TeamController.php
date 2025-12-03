<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Models\Event;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function create()
    {
        $events = Event::orderBy('start_date','desc')->get();
        return view('teams.create', compact('events'));
    }

    public function store(StoreTeamRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('team_logo')) {
            $data['team_logo'] = $request->file('team_logo')->store('team_logos', 'public');
        }

        if (empty($data['invite_code'])) {
            $data['invite_code'] = Str::upper(Str::random(8));
        }

        $team = Team::create($data);

        if (!empty($data['members'])) {
            foreach ($data['members'] as $m) {
                TeamMember::create([
                    'team_id' => $team->id,
                    'name' => $m['name'],
                    'email' => $m['email'],
                    'career' => $m['career'],
                    'phone' => $m['phone'],
                    'role' => $m['role'] ?? null,
                ]);
            }
        }

        $team->users()->attach(auth()->id(), [
            'role' => 'leader',
            'status' => 'accepted'
        ]);

        return redirect()->route('teams.show', $team)->with('success','Equipo creado.');
    }

    public function show(Team $team)
    {
        $team->load('members','users','event','leader');
        return view('teams.show', compact('team'));
    }

    public function join(Request $request, Team $team)
    {
        $user = auth()->user();

        if ($team->visibility === 'Privado' && $request->invite_code !== $team->invite_code) {
            return back()->withErrors(['invite_code' => 'Código incorrecto.']);
        }

        if (!$team->users()->where('user_id', $user->id)->exists()) {
            $team->users()->attach($user->id, [
                'role' => 'member',
                'status' => 'pending'
            ]);
        }

        return back()->with('success', 'Solicitud enviada.');
    }

    public function acceptMember(Request $request, Team $team)
    {
        $team->users()->updateExistingPivot($request->user_id, [
            'status' => 'accepted'
        ]);

        return back()->with('success','Miembro aceptado.');
    }

    public function destroy(Team $team)
    {
        if ($team->team_logo && Storage::disk('public')->exists($team->team_logo)) {
            Storage::disk('public')->delete($team->team_logo);
        }

        $team->delete();

        return redirect()->route('dashboard')->with('success','Equipo eliminado.');
    }
}
