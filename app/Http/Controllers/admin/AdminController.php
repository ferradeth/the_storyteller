<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(){
        return view('admin.admins', ['admins'=>Admin::get()]);
    }
//  добавление администратора
    public function create(Request $request){
        $admin = Admin::create(array_merge(
            ['password' => Hash::make($request->password), ],
            $request->only('login')
        ));

        return $admin? back()->with(['success'=>'Администратор успешно создан']):back()->withErrors(['error'=>'Не удалось добавить администратора']);
    }
//  редактирование администратора
    public function update(Request $request){
        $res = Admin::find($request->id)->update(['name'=>$request->name]);
        return $res ? back()->with(['success'=>'Администратор успешно обновлён']):back()->withErrors(['error'=>'Не удалось обновить администратора']);
    }
//  удаление администратора
    public function delete(Admin $admin){

        if($admin->id === auth('admin')->id()){
            $res = false;
        }
        else{
            $res = $admin->delete();
        }
        return $res ? back()->with(['success'=>'Администратор удалён']):back()->withErrors(['error'=>'Не удалось удалить администратора']);
    }
}
