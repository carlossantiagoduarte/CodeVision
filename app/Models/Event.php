<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Importante para saber quién creó el evento
        'title',
        'organizer',
        'location',
        'description',
        'email',
        'phone',
        'max_participants',
        'requirements',
        'start_date',
        'end_date',
        'image_url',
        'documents_info',
        'start_time',
        'end_time',
    ];

    // Relación: Un evento pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
