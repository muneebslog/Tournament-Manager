<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
     protected $fillable = [
        'event_id',
        'round_id',
        'name',
        'team1_id',
        'team2_id',
        'winner_team_id',
        'status',
        'is_doubles',
        'bestof',
        'service_judge_name',
        'empire_name',
        'expected_start_time',
        'start_time',
        'end_time',
        'match_date',
        'team1_points',
        'team2_points',
        'is_published',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function winnerTeam()
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    public function scores()
    {
        return $this->hasMany(GameScore::class);
    }

    public function events()
    {
        return $this->hasMany(GameEvent::class);
    }
}
