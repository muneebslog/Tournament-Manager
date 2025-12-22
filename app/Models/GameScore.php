<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
     protected $fillable = [
        'game_id',
        'set_number',
        'team1_score',
        'team2_score',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
