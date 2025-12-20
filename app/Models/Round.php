<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
      protected $fillable = [
        'game_match_id',
        'round_number',
        'player1_score',
        'player2_score',
        'winner_id',
        'status',
        'ended_at',
    ];

    // Relationships

    public function gameMatch()
    {
        return $this->belongsTo(GameMatch::class, 'game_match_id');
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'round_id');
    }
}
