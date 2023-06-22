<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameImage extends Model
{
    protected $fillable = [
        'game_id',
        'image'
    ];

    use HasFactory;
    public $timestamps = false;

    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function getImagesByGame(Game $game){
        return GameImage::where('game_id', $game->id)->latest();
    }
}
