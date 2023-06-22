<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'username'=>['required', 'regex:/^[а-яёa-z0-9\s-]+$/iu'],
        ];
    }

    public function messages(){
        return [
            'required'=>'Поле ":attribute" необходимо заполнить',
            'regex'=>'Поле ":attribute" не соответствует шаблону',
        ];
    }

    public function attributes(){
        return [
            'username'=>'Имя',
        ];
    }
}
