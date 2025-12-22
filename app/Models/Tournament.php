<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
