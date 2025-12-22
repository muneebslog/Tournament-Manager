<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
     protected $fillable = [
        'event_id',
        'name',
        'ranking',
        'subtext',
        'phone',
        'pic',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_players');
    }
}
