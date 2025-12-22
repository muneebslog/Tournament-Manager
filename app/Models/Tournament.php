<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'location',
        'slogan',
        'status',
        'logo_path',
        'created_by'
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function getLogoUrlAttribute()
    // {
    //     return $this->logo_path
    //         ? Storage::url($this->logo_path)
    //         : '/images/default-logo.png'; // optional default image
    // }
}
