<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'team_members'; // Aseguramos que use tu tabla

    protected $fillable = [
        'team_id',
        'name',
        'email',
        'career',
        'phone',
    ];

    // Relación: Un miembro pertenece a un equipo
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
