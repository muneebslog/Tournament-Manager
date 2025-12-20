<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentEvent extends Model
{
    protected $table = 'tournament_events';

    protected $fillable = [
        'tournament_id',
        'title',
        'logo',
    ];

    /**
     * Relationships
     */

    // Each event belongs to a tournament
  
     public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class); // or belongsToMany if using pivot
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'tournament_event_id');
    }

     
}