<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    public function authorize()
    {
        return true; // muy importante porque authorize() te da error
    }

    public function rules()
    {
        return [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',

            'leader_name' => 'required|string|max:255',
            'leader_email' => 'required|email|max:255',
            'leader_career' => 'required|string|max:255',
            'leader_semester' => 'required|string|max:255',
            'leader_experience' => 'nullable|string',

            'max_members' => 'required|integer|min:1',
            'visibility' => 'required|in:Privado,Público',
            'requirements' => 'nullable|string',

            'invite_code' => 'nullable|string|max:50|unique:teams,invite_code',

            'team_logo' => 'nullable|image|max:2048',

            // miembros manuales (no usuarios)
            'members' => 'array|nullable',
            'members.*.name' => 'required_with:members|string|max:255',
            'members.*.email' => 'required_with:members|email|max:255',
            'members.*.career' => 'required_with:members|string|max:255',
            'members.*.phone' => 'required_with:members|string|max:20',
            'members.*.role' => 'nullable|string|max:255',

            'description' => 'nullable|string',
            'skills_needed' => 'nullable|string',
        ];
    }
}
