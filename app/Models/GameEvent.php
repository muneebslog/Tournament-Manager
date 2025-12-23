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

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    // Get event color for both web and PDF
    public function getEventColor()
    {
        return match($this->event_type) {
            'start_round' => '#3b82f6',      // Blue
            'end_round' => '#a855f7',        // Purple
            'point' => '#22c55e',            // Green
            'point_deduction' => '#f97316',  // Orange
            'injury' => '#ef4444',           // Red
            'red_card' => '#dc2626',         // Dark Red
            'yellow_card' => '#facc15',      // Yellow
            'shuttle_change' => '#6366f1',   // Indigo
            'winner' => '#eab308',           // Gold
            default => '#6b7280'             // Gray
        };
    }

    // Get event label
    public function getEventLabel()
    {
        return match($this->event_type) {
            'start_round' => 'START_ROUND',
            'end_round' => 'END_ROUND',
            'point' => 'POINT',
            'point_deduction' => 'POINT_DEDUCTION',
            'injury' => 'INJURY',
            'red_card' => 'RED_CARD',
            'yellow_card' => 'YELLOW_CARD',
            'shuttle_change' => 'SHUTTLE_CHANGE',
            'winner' => 'WINNER',
            default => strtoupper($this->event_type)
        };
    }

}
