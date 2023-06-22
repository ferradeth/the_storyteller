<?php

namespace App\Http\Controllers\admin;


    use App\Http\Controllers\Controller;
    use App\Http\Requests\LoginRequest;
    use App\Models\Game;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Gate;

class LoginController extends Controller
{
    public function login(){
        return view('admin.login');
    }

    public function loginCheck(Request $request){
        if(auth('admin')->attempt($request->only(['login', 'password']))){
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }
        return back()->withErrors(['error'=>'Ошибка входа']);
    }

    public function logout(){
        auth('admin')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return to_route('admin.login');
    }

    public function index(){
        return view('admin.index', ['games'=>Game::where('count_dislikes', '>', '5')->latest()->get()]);
    }
}
