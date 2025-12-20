<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
      /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'tournament_event_id',
        'name',
        'team',
        'phone',
        'ranking',
        'image'
    ];

    /**
     * Relationships
     */

    // A player belongs to one tournament
    public function tournamentEvent()
    {
        return $this->belongsTo(TournamentEvent::class);
    }

    // A player can be Player 1 in many matches
    public function matchesAsPlayer1()
    {
        return $this->hasMany(GameMatch::class, 'player1_id');
    }

    // A player can be Player 2 in many matches
    public function matchesAsPlayer2()
    {
        return $this->hasMany(GameMatch::class, 'player2_id');
    }

    // A player can win many matches
    public function matchesWon()
    {
        return $this->hasMany(GameMatch::class, 'winner_id');
    }

    // A player can win many rounds
    public function roundsWon()
    {
        return $this->hasMany(Round::class, 'winner_id');
    }

    // A player can be linked to many events (cards, points, etc.)
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
