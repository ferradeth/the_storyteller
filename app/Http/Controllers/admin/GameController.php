<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CheckGame;
use App\Models\Game;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index($user_id = null)
    {
        return view('admin.games', ['games' => $user_id ? Game::where('user_id', $user_id)->orderBy('count_likes', 'desc')->latest()->get() : Game::orderBy('count_likes', 'desc')->latest()->get(), 'user' => $user_id ? User::find($user_id)->username : null]);
    }

    public function show(Game $game)
    {
        return view('admin.show', ['game' => $game]);
    }

    public function check_games()
    {
        return view('admin.check', ['games' => Game::where('check_id', 1)->latest()->get(), 'status' => CheckGame::find(1)]);
    }

    public function canceled_games()
    {
        return view('admin.check', ['games' => Game::where('check_id', 3)->latest()->get(), 'status' => CheckGame::find(3)]);
    }

    public function confirm(Request $request)
    {
        $game = Game::find($request->game_id);
        $res = $game->update(['check_id' => 2]);
        if ($res) {
            Notification::create(['user_id' => $game->user_id, 'message' => "Ваша игра $game->name была опубликована."]);
        }
        return $res ? back()->with(['success' => 'Игра успешно одобрена']) : back()->withErrors(['error' => 'Произошла ошибка при подтверждении']);
    }

    public function cancel(Request $request)
    {
        $game = Game::find($request->game_id);
        $res = $game->update(['check_id' => 3]);
        if ($res) {
            Notification::create(['user_id' => $game->user_id, 'message' => "Ваша игра $game->name была отклонена. Вы можете отредактировать её в соотвествии с правилами сайта и отправить на повторное рассмотрение."]);
        }
        return $res ? back()->with(['success' => 'Игра отклонена']) : back()->withErrors(['error' => 'Произошла ошибка при отклонении']);
    }

    public function ban(Request $request)
    {
        $game = Game::find($request->game_id);
        $res = $game->update(['baned' => true]);
        if ($res) {
            Notification::create(['user_id' => $game->user_id, 'message' => "Ваша игра $game->name была заблокирована из-за большого количество жалоб."]);
        }
        return $res ? back()->with(['success' => 'Игра успешно заблокирована']) : back()->withErrors(['error' => 'Произошла ошибка, блокировка не удалась']);
    }

    public function unban(Request $request)
    {
        $game = Game::find($request->game_id);
        $res = $game->update(['baned' => false]);
        if ($res) {
            Notification::create(['user_id' => $game->user_id, 'message' => "Ваша игра $game->name была разблокирована."]);
        }
        return $res ? back()->with(['success' => 'Игра успешно разблокирована']) : back()->withErrors(['error' => 'Произошла ошибка, блокировка не удалась']);
    }

}
