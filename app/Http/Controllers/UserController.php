<?php

namespace App\Http\Controllers;

use App\Http\FileService;
use App\Http\Requests\EditRequest;
use App\Http\Requests\PassRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\Status;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

//  вывод информации профиля пользователя
    public function index(User $user = null)
    {
        return view('users.index-copy', ['user'=>$user??auth()->user(), 'tags'=>Tag::get(), 'statuses'=>Status::get(), 'games'=>$user? $user->filterWorks(0)->get():auth()->user()->filterWorks(0)->get()]);
    }

    public function create()
    {
        return view('users.reg');
    }
//  регистрация пользователя
    public function store(UserRequest $request)
    {
        $user = User::create(array_merge(
            ['password' => Hash::make($request->password), 'avatar'=>'/storage/default.jpg', 'ban'=>false],
            $request->except('password')
        ));

        auth()->login($user);

        return to_route('user.profile');
    }

    public function login(){
        return view('users.login');
    }

    public function edit()
    {
        return view('users.edit', ['user'=>auth()->user()]);
    }
    public function editPass()
    {
        return view('users.edit-pass', ['user'=>auth()->user()]);
    }
//  проверка входа
    public function loginCheck(LoginRequest $request){
        if (Auth::attempt($request->only(['email', 'password']))){
            $request->session()->regenerate();
            return to_route('user.profile');
        }
        return back()->withErrors(['error'=>'Пользователь не найден']);
    }

//  выход из аккаунта
    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('games.index');
    }

//  редактирование информации пользователя
    public function update(EditRequest $request){
        // dd($request->all());
        $path = FileService::update(auth()->user()->avatar,'/avatars', $request->file('avatar'));

        if ($path)
            $res = auth()->user()->update(['avatar'=>$path]+$request->except('avatar', 'password', 'email'));
        else
            $res = auth()->user()->update($request->except('avatar', 'password', 'email'));


        if (User::where('email', $request->email)->first()->id == auth()->id()){
            $res = auth()->user()->update(['email'=>$request->email]);
        }
        else{
            return back()->withErrors(['error'=>'Такой email уже зарегистрирован.']);
        }

        return $res? to_route('user.profile')->with(['success'=>'Запись успешно обновлена']): back()->withErrors(['error'=>'Запись невозможно обновить']);
    }

    public function updatePass(PassRequest $request){
        if ($request->password != null && $request->old_pass != null){
                if(Hash::check($request->old_pass, auth()->user()->getAuthPassword())){
                    $res = auth()->user()->update(['password'=>Hash::make($request->password)]);
                }
                else{
                    return back()->withErrors(['error'=>'Старый пароль не совпадает!']);
                }
            }
            else{
                return back()->withErrors(['error'=>'Необходимо ввести старый пароль!']);
            }
            
            return $res? to_route('user.profile')->with(['success'=>'Пароль успешно обновлён']): back()->withErrors(['error'=>'Пароль невозможно обновить']);
    }
}

