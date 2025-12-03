<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
        'banner_url',
        'modality',
        'registration_link',
        'main_category',
    ];

public function organizerUser()
{
    return $this->belongsTo(User::class, 'user_id');
}


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_category');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
    
}
