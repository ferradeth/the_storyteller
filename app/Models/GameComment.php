<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'game_id',
        'message',
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function getCommentsByGame(Game $game){
        return GameComment::where('game_id', $game->id)->latest();
    }
    public function dateClassic(){
        return $this->created_at->format('d.m.Y');
    }
}
