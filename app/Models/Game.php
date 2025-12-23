<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
     protected $fillable = [
        'event_id',
        'shuttles_used',
        'match_serial_number',
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
    protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'updated_at' => 'datetime',
];

     public static function generateSerialNumber($eventId)
    {
        $year = date('Y');
        $lastMatch = self::where('tournament_event_id', $eventId)
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastMatch && preg_match('/(\d{3})$/', $lastMatch->match_serial_number, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return sprintf('%s-E%s-%03d', $year, $eventId, $lastNumber + 1);
    }

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

    public function gameevents()
    {
        return $this->hasMany(GameEvent::class);
    }
}
