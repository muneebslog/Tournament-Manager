<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
     protected $fillable = [
        'title',
        'location',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'logo',
        'slogan'
    ];

    /**
     * Relationships
     */

       // 🔗 Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A tournament has many players
   

    // A tournament has many matches (game_matches)
     public function events()
    {
        return $this->hasMany(TournamentEvent::class);
    }
}
