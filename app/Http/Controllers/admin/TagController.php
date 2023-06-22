<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return view('admin.tags', ['tags'=>Tag::orderBy('name', 'asc')->get()]);
    }
    public function create(Request $request)
    {
        if(!Tag::where('name', $request->name)->first())
            $res = Tag::create($request->only('name'));
        else{
            $res = false;
            $message = 'Такой тэг уже существует';
        }
        return $res? back()->with(['success'=>'Тэг успешно добавлен']):back()->withErrors(['error'=>$message ?? 'Не удалось добавить тэг']);
    }
    public function update(Request $request)
    {
        $tag = Tag::find($request->id);
        if(Tag::where('name', $request->name)->first() == $tag ||Tag::where('name', $request->name)->first()==null)
            $res = $tag->update($request->only('name'));
        else{
            $res = false;
            $message = 'Такой тэг уже существует';
        }
        return $res? back()->with(['success'=>'Тэг успешно обновлён']):back()->withErrors(['error'=>$message ?? 'Не удалось обновить тэг']);
    }
    public function delete(Tag $tag)
    {
       $res = $tag->delete();
        return $res? back()->with(['success'=>'Тэг успешно удалён']):back()->withErrors(['error'=>'Не удалось удалить тэг']);
    }
}
