<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $skills = Skill::all();
        $categories = Category::all();

        return view('profile.edit', compact('user','skills','categories'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'profile_picture' => 'nullable|image',
            'career' => 'nullable|string|max:255',
            'semester' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'skills' => 'array',
            'skills.*' => 'integer',
            'interests' => 'array',
            'interests.*' => 'integer',
        ]);

        // foto de perfil
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user->career = $request->career;
        $user->semester = $request->semester;
        $user->bio = $request->bio;
        $user->save();

        // skills
        if ($request->skills) {
            $user->skills()->sync($request->skills);
        }

        // intereses
        if ($request->interests) {
            $user->interests()->sync($request->interests);
        }

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }
}
