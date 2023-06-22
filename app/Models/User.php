<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'birth_date',
        'description',
        'email',
        'password',
        'avatar',
        'ban',
        'card_number'
    ];
    public function subs(){
        return $this->hasMany(Sub::class);
    }
    public function games(){
        return $this->hasMany(Game::class);
    }
    public function comments(){
        return $this->hasMany(GameComment::class);
    }
    public function likes(){
        return $this->hasMany(Likes::class);
    }
    public function dislikes(){
        return $this->hasMany(Dislike::class);
    }
    public function notifies()
    {
        return $this->hasMany(Notification::class);
    }
//  проверка лайка
    public function existLike($game_id){
        return $this->likes->where('game_id', $game_id);
    }
//  проверка дизлайка
    public function existDislike($game_id){
        return $this->dislikes->where('game_id', $game_id);
    }
    //топ 10 авторов
    public static function getTopAuthors(){
        $res = Collection::empty();
        $subs = Sub::selectRaw('count(sub_id) as number_subs, user_id')->groupBy('user_id')->orderBy('number_subs', 'desc')->take(10)->get()->pluck('user_id');
        foreach ($subs as $sub){
            $res->add(User::find($sub));
        }
        return $res;
    }
    //количество подписчиков
    public function numberFollowers(){
        return count(Sub::where('user_id', $this->id)->get());
    }
    //количество подписок
    public function numberSubs(){
        return count(Sub::where('sub_id', $this->id)->get());
    }

//  вытаскивание игр по статусу
    public function filterWorks($status_id){
        if ($status_id == 'all'){
            $subs = Sub::where('sub_id', $this->id)->get()->pluck('user_id');
            return Game::whereIn('user_id', $subs)->where('check_id', 2);
        }else if($status_id == 0){
            if($this->id == auth()->id()){
                return Game::where('user_id', $this->id);
            }
            else{
                return Game::where('user_id', $this->id)->where('check_id', 2);
            }
        }
        else{
            $game_statuses = GameStatus::where('user_id', $this->id)->where('status_id', $status_id)->pluck('game_id');
            return Game::whereIn('id', $game_statuses)->where('check_id', 2);
        }
    }
//  проверка подписки
    public function checkSub($user_id){
        return Sub::where('user_id', $user_id)->where('sub_id', $this->id)->get();
    }
    
    public function checkPaySub($user_id){
        return Sub::where('user_id', $user_id)->where('sub_id', $this->id)->where('payed', true)->get();
    }
    //дата в нормальном виде
    public function dateClassic():Attribute
    {
        return Attribute::make(
            get: fn() => date_format(new \DateTime($this->birth_date), 'd.m.Y'),
        );
    }
}
