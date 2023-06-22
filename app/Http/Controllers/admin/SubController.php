<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Sub;
use Illuminate\Http\Request;

class SubController extends Controller
{
    public function index(){
        return view('admin.subs', ['subs'=>Sub::latest()->get()]);
    }

    public function confirm(Sub $sub){
        $res = $sub->update(['payed'=>true]);
        return $res? to_route('user.profile', $sub->user_id)->with(['success'=>'Оплата подписки подтверждена']):back()->withErrors(['error'=>'Не удалось подтвердить оплату']);
    }

    public function delete(Sub $sub){
        $res = $sub->delete();
        return $res? back()->with(['success'=>'Подписка успешно отменена']):back()->withErrors(['error'=>'Не удалось отменить подписку']);
    }
}
