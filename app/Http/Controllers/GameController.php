<?php

namespace App\Http\Controllers;

use App\Http\FileService;
use App\Models\Dislike;
use App\Models\Game;
use App\Models\GameImage;
use App\Models\GameStatus;
use App\Models\GameTag;
use App\Models\Likes;
use App\Models\Notification;
use App\Models\Status;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function index()
    {
        return view('index', ['games' => Game::where('check_id', 2)->where('baned', false)->orderBy('updated_at', 'desc')->take(15)->get(), 'games_sub' => Game::getGamesBySubs()->where('check_id', 2)->where('baned', false)->take(15)->get(), 'popular' => Game::getPopular5Games()->where('check_id', 2), 'top_authors' => User::getTopAuthors(), 'tags' => Tag::orderBy('name', 'desc')->get(), 'notifications'=>auth()->check()? Notification::where('user_id', auth()->id())->get():null]);
    }

    public function catalog($id = null)
    {
        return view('games.catalog', ['games' => $id?Game::whereIn('id',GameTag::where('tag_id', $id)->get()->pluck('game_id'))->where('check_id', 2)->orderBy('updated_at', 'desc')->get():Game::where('check_id', 2)->orderBy('updated_at', 'desc')->get(), 'tags' => Tag::get(), 'filter_tag'=>$id? Tag::find($id)->name : null]);
    }

    public function getTab(User $user, $tab_id){
        $games =$user->filterWorks($tab_id)->get();
        return view('users.index-copy', ['user'=>$user??auth()->user(), 'tags'=>Tag::get(), 'statuses'=>Status::get(), 'games'=>$games]);
    }

    public function searchResults(Request $request){
        $word = htmlspecialchars($request->search);
        return view('games.catalog', ['games'=>Game::whereRaw('name LIKE ?', ["%{$word}%"])->where('check_id', 2)->get(), 'tags' => Tag::get(), 'title'=>"Результаты поиска по запросу: {$word}", 'filter_tag'=>null]);
    }

    public function filter(Request $request)
    {
//        dd($request->all());
        if (isset($request->user) && isset($request->tab)) {
            $games = User::find($request->user)->filterWorks($request->tab);
            if ($request->tags) {
                $res = GameTag::whereIn('tag_id', $request->tags)->get();
                $games = $games->whereIn('id', $res->pluck('game_id'));
            }
            $games = $games->orderBy($request->sort ?? 'created_at', $request->orderSort ?? 'desc');
        } else {
            $games = Game::where('baned', false)->where('check_id', 2);
            if ($request->all()) {
                if ($request->tags) {
                    $res = GameTag::whereIn('tag_id', $request->tags)->get();
                    $games = $games->whereIn('id', $res->pluck('game_id'));
                }
                $games = $games->orderBy($request->sort ?? 'created_at', $request->orderSort ?? 'desc');
            }
        }
        return back()->withInput(['filter_games' => $games->get(), 'filters'=>$request->all()]);
    }

    public function create()
    {
        return view('games.create', ['tags' => Tag::get()]);
    }

    public function store(Request $request)
    {
        $path = FileService::upload($request->file('cover'), '/covers');
        $res = Game::create($request->except('screens', 'cover', '_token', 'tags', 'files') + ['cover' => $path, 'count_downloads' => 0, 'count_likes' => 0, 'count_dislikes' => 0, 'baned'=> false, 'user_id' => auth()->id(), 'access_id' => 1, 'check_id' => 1]);
        if ($request->screens) {
            foreach ($request->files->all()['screens'] as $file) {
                $path2 = FileService::uploadNew($file, '/screens');
                GameImage::create(['game_id' => $res->id, 'image' => $path2]);
            }
        }
        foreach ($request->tags as $tag) {
            GameTag::create(['game_id' => $res->id, 'tag_id' => $tag]);
        }
        return response()->json($res);
    }

    public function show(Game $game)
    {
        return view('games.show', ['game' => $game, 'statuses' => Status::get()]);
    }

    public function addLike(Request $request)
    {
        $game_likes = Likes::where('game_id', $request->data)->where('user_id', auth()->id())->first();
        $game = Game::find($request->data);
        if ($game_likes != null){
            $game_likes->delete();
            $game->update(['count_likes'=>$game->count_likes-1]);
            $res = false;
        }
        else{
            $res = Likes::create(['user_id' => auth()->id(), 'game_id' => $request->data]);
            $game->update(['count_likes'=>$game->count_likes+1]);
        }


        return response()->json($res);
    }

    public function dislike(Request $request)
    {
        $game_dislikes = Dislike::where('game_id', $request->data)->where('user_id', auth()->id())->first();
        $game = Game::find($request->data);
        if ($game_dislikes != null){
            $game_dislikes->delete();
            $game->update(['count_dislikes'=>$game->count_dislikes-1]);
            $res = false;
        }
        else{
            $res = Dislike::create(['user_id' => auth()->id(), 'game_id' => $request->data]);
            $game->update(['count_dislikes'=>$game->count_dislikes+1]);
        }


        return response()->json($res);
    }

    public function changeStatus(Request $request)
    {
        $game = GameStatus::where('game_id', $request->data['id'])->where('user_id', auth()->id())->first();
        if ($game != null) {
            $res = $game->update(['status_id' => $request->data['status_id']]);
        } else {
            $res = GameStatus::create(['game_id' => $request->data['id'], 'user_id' => auth()->id(), 'status_id' => $request->data['status_id']]);
        }
        return response()->json($res);
    }

    public function hide(Game $game)
    {
        $res = $game->update(['access_id' => 3]);
        return $res ? back()->with(['success' => "Игра $game->name успешно скрыта"]) : back()->withErrors(['error' => 'Не удалось скрыть игру']);
    }

    public function open(Game $game)
    {
        $res = $game->update(['access_id' => 1]);
        return $res ? back()->with(['success' => "Игре $game->name присвоен 2 уровень доступа (только по подписке). Поменять уровень можно на странице редактирования"]) : back()->withErrors(['error' => 'Не удалось скрыть игру']);
    }

    public function edit(Game $game)
    {
        return view('games.edit', ['game' => $game, 'tags' => Tag::get()]);
    }

    public function delScreen(Request $request)
    {
        $image = GameImage::find($request->data);
        $res = false;

        if($image){
            FileService::delete($image->image, '/screens/');
            $res = $image->delete();
        }

        return $res;
    }

    public function delTag(Request $request)
    {
        return GameTag::where('tag_id', $request->data['tag_id'])->where('game_id', $request->data['game_id'])->delete();
    }

    public function update(Request $request)
    {
        $game = Game::find($request->id);
        $path = FileService::update($game->cover, '/covers', $request->file('cover'));

        if ($path) {
//            ДОБАВИТЬ ПРОВЕРКУ НА CHECK_ID ЕСЛИ 3 ТО 1 ЕСЛИ 2 ТО 2
            if ($game->check_id == 3)
                $res = $game->update($request->except('screens', 'cover', '_token', 'tags', 'files') + ['cover' => $path, 'check_id'=> 1]);
            else
                $res = $game->update($request->except('screens', 'cover', '_token', 'tags', 'files') + ['cover' => $path]);
        } else {
            if ($game->check_id == 3)
                $res = $game->update($request->except('screens', 'cover', '_token', 'tags', 'files') + ['check_id'=>1]);
            else
                $res = $game->update($request->except('screens', 'cover', '_token', 'tags', 'files'));
        }
        if ($request->screens) {
            foreach ($request->files->all()['screens'] as $file) {
                $path2 = FileService::uploadNew($file, '/screens');
                GameImage::create(['game_id' => $game->id, 'image' => $path2]);
            }
        }
        if ($request->tags) {
            foreach ($request->tags as $tag) {
                GameTag::create(['game_id' => $res->id, 'tag_id' => $tag]);
            }
        }

        return response()->json($res);
    }

    public function download(Request $request){
        $game = Game::find($request->data);
        $res = $game->update(['count_downloads'=>$game->count_downloads+1]);
        return response()->json($game->count_downloads);
    }

    public function delete(Game $game)
    {
        $res = $game->delete();
        return $res ? back()->with(['success' => 'Работа успешно удалена']) : back()->withErrors(['error' => 'Произошла ощибка при удалении']);
    }
}
