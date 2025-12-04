<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'password',
        'google_id',
        'facebook_id',
        'profile_picture',
        'career',
        'semester',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones existentes
    
    // Eventos creados por el usuario
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    // Equipos donde es líder
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    // Equipos donde participa (pivot team_user)
    public function memberOfTeams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
                    ->withPivot(['role', 'status'])
                    ->withTimestamps();
    }

    // Habilidades del usuario
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skill')
                    ->withTimestamps();
    }

    // Intereses del usuario (categorías)
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'user_interests')
                    ->withTimestamps();
    }
}
