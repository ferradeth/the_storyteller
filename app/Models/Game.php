<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'cover',
        'file_link',
        'count_downloads',
        'count_likes',
        'count_dislikes',
        'language',
        'ban',
        'baned',
        'user_id',
        'access_id',
        'check_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function access(){
        return $this->belongsTo(Access::class);
    }
    public function comments(){
        return $this->hasMany(GameComment::class);
    }
    public function images(){
        return $this->hasMany(GameImage::class);
    }
    public function gameStatuses(){
        return $this->hasMany(GameStatus::class);
    }
    public function likes(){
        return $this->hasMany(Likes::class);
    }
    public function dislikes(){
        return $this->hasMany(Dislike::class);
    }
    public function gameTags(){
        return $this->hasMany(GameTag::class);
    }
    public function check(){
        return $this->belongsTo(CheckGame::class);
    }
    //получение игр по подпискам
    public static function getGamesBySubs(){
        $subs = Sub::where('sub_id', auth()->id())->get()->pluck('user_id');
        return Game::whereIn('user_id', $subs)->orderBy('updated_at', 'desc');
    }
    //количество добавлений в избранное
    public function numberOfFavs(){
        return count(GameStatus::where('game_id', $this->id)->where('status_id', 1)->get());
    }
    //5 популярных игр по количеству скачиваний
    public static function getPopular5Games(){
        return Game::where('baned', false)->orderBy('count_downloads', 'desc')->take(5)->get();
    }
    //дата в нормальном виде
    public function dateClassic($date){
        return $date->format('d.m.Y');
    }
}
