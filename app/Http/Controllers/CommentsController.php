<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameComment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request, Game $game){
        $pattern = "/хуй|жопа|пизда|хуесос|мразь|пидор|уёбок|уебок|блять|блядь|ебать|говно|шлюха|даун|гандон|долбоёб|сука|дура|дурак|дибил|идиот|имбицил/";
//        dd(preg_match_all($pattern, $request->message));
        if (preg_match_all($pattern, $request->message)==0){
            GameComment::create($request->except('_token')+['game_id'=>$game->id, 'user_id'=>auth()->id()]);
            $res = false;
        }
        else
            $res = true;

        return $res ?back()->withErrors(['error'=>'Комментарий некорректный! Избегайте использования оскорбительных и нецензурных слов.']): back();
    }

    public function destroy(GameComment $comment){
        $res = $comment->delete();
        return $res ? back()->with(['success'=>'Комментарий успешно удалён']): back()->withErrors(['error'=>'Произошла ошибка при удалении']);
    }
}
