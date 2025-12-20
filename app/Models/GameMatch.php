<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'tournament_event_id',
        'court_number',
        'title',
        'player1_id',
        'player2_id',
        'match_date',
        'best_of',
        'player1_rounds_won',
        'player2_rounds_won',
        'winner_id',
        'status',
        'match_serial_number',
        'shuttles_used',
        'max_rounds',
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


    /**
     * Relationships
     */

    // Match belongs to one tournament
    public function tournamentEvent()
    {
        return $this->belongsTo(TournamentEvent::class);
    }

    // Player 1 relation
    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    // Player 2 relation
    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }

    // Match winner
    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

    // A match has many rounds
    public function rounds()
    {
        return $this->hasMany(Round::class, 'game_match_id');
    }
}
