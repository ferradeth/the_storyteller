<?php

namespace App\Http\Controllers;

use App\Models\Sub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class SubController extends Controller
{
    public function sub(User $user, Request $request)
    {
        $res = false;

        if ($user->id != auth()->id()){
            $res = Sub::create(['user_id'=>$user->id, 'sub_id'=>auth()->id(), 'period'=>$request->period, 'payed'=>false]);
            $mailData = [
                'title'=>'Подписка на платформе The storyteller',
                'body'=> "Вами была оформлена подписка на {$user->username}. Для активации подписки перейдите по ссылке ниже: \n a0828483.xsph.ru/admin/subs/confirm/{$res->id}"
                ];
            Mail::to(auth()->user()->email)->send(new SendMail($mailData));
        }

        return $res ? to_route('user.profile', $user->id)->with(['success'=>'Подписка успешно оформлена. На вашу почту пришло письмо с дальнейшими инструкциями.']) : to_route('user.profile', $user->id)->withErrors(['error'=>'Возникла ошибка при оформлении. Попробуйте позже.']);
    }

    public function unsub(User $user)
    {
        $res = Sub::where(['user_id'=>$user->id, 'sub_id'=>auth()->id()])->delete();
        return $res ? to_route('user.profile', $user->id)->with(['success'=>'Подписка отменена.']) : to_route('user.profile', $user->id)->withErrors(['error'=>'Невозможно отменить подписку']);
    }
}
