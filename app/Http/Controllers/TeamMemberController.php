<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamMemberRequest;
use App\Models\TeamMember;

class TeamMemberController extends Controller
{
    public function store(StoreTeamMemberRequest $request)
    {
        TeamMember::create($request->validated());
        return redirect()->back()->with('success','Miembro agregado.');
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return redirect()->back()->with('success','Miembro eliminado.');
    }
}
