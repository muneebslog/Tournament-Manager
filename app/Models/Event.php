<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
     protected $fillable = [
        'round_id',
        'player_id',
        'event_type',
        'description',
        'player1_score',
        'player2_score',
        'timestamp',
    ];

    // Relationships

    public function round()
    {
        return $this->belongsTo(Round::class, 'round_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
