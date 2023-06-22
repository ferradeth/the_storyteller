<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameStatus extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable =[
        'game_id',
        'user_id',
        'status_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
}
