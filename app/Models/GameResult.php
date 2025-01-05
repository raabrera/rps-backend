<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    protected $fillable = ['game_id', 'player1_move', 'player2_move', 'winner'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
