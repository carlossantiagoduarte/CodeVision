<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'name',
        'leader_name',
        'leader_email',
        'leader_career',
        'leader_semester',
        'leader_experience',
        'max_members',
        'visibility',
        'requirements',
        'invite_code',
        'team_logo',
        'description',
        'skills_needed',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // miembros invitados del formulario (no usuarios del sistema)
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    // usuarios registrados que se unen al equipo
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')
                    ->withPivot(['role', 'status'])
                    ->withTimestamps();
    }
}
