<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model
{
    protected $fillable = [
        'game_id',
        'event_type',
        'description',
        'tag',
        'team1_points_at_event',
        'team2_points_at_event',
        'player_id',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

}
