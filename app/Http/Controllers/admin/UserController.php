<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Sub;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return view('admin.users', ['users'=>User::latest()->get()]);
    }

    public function ban(User $user){
        $res = $user->update(['ban'=>true]);
        if ($res){
//            foreach (Sub::where('user_id',$user->id)->get() as $sub){
//                $sub->delete();
//            }
            Notification::create(['user_id'=>$user->id, 'message'=>'Вы были заблокированы. Вам запрещено выкладывать новые игры, а также на вас невозможно оформить подписку. Все действующие подписки были удалены.']);
        }
        return $res? back()->with(['success'=>'Пользователь успешно заблокирован']):back()->withErrors(['error'=>'Не удалось заблокировать пользователя']);
    }

    public function unban(User $user){
        $res = $user->update(['ban'=>false]);
        if ($res){
            Notification::create(['user_id'=>$user->id, 'message'=>'Вы были разблокированы. Теперь вам снова доступен полный функционал']);
        }
        return $res? back()->with(['success'=>'Пользователь успешно разблокирован']):back()->withErrors(['error'=>'Не удалось заблокировать пользователя']);
    }

    public function followers(User $user){
        return view('admin.user_subs', ['followers'=>Sub::where('user_id', $user->id)->get(), 'user'=>$user->username]);
    }

    public function subscribes(User $user){
        return view('admin.user_subs', ['subscribes'=>Sub::where('sub_id', $user->id)->get(), 'user'=>$user->username]);
    }
}
