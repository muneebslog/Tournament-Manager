<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'tournament_id',
        'name',
        'description',
        'logo_path'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
