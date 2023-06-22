<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassRequest extends FormRequest
{

    public function rules()
    {
        return [
            'password'=>['regex:/^(?=.*[0-9])(?=.*[!#&?])(?=.*[A-Z])[0-9a-zA-Z!#&?]{8,}/'],
        ];
    }

    public function messages(){
        return [
            'regex'=>'Поле ":attribute" не соответствует шаблону',
        ];
    }

    public function attributes(){
        return [
            'password'=>'Новый пароль',
        ];
    }
}