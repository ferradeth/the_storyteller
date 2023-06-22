<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GameComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(){
        return view('admin.comments', ['comments'=>GameComment::latest()->get()]);
    }

//    public function delete(GameComment $comment){
//        $res = $comment->delete();
//        return $res?back()->with(['success'=>'Удаление успешно']): back()->withErrors(['error'=>"Не удалось удалить"]);
//    }

    public function delete(Request $request){
//        dd ($request->all());
        foreach ($request->data as $comment_id)
            $res = GameComment::find($comment_id)->delete();
        return response()->json($res);
    }
}
