<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
     protected $fillable = [
        'event_id',
        'name',
        'round_number',
        'matches'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
