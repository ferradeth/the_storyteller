<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username'=>['required', 'regex:/^[а-яёa-z0-9\s-]+$/iu'],
            'password'=>['required', 'regex:/^(?=.*[0-9])(?=.*[!#&?])(?=.*[A-Z])[0-9a-zA-Z!#&?]{8,}/', 'confirmed'],
            'email'=>['required', 'unique:users', 'email'],
            'birth_date' => ['required']
        ];
    }

    public function messages(){
        return [
            'required'=>'Поле ":attribute" необходимо заполнить',
            'unique'=>'Поле ":attribute" должно быть уникальным',
            'regex'=>'Поле ":attribute" не соответствует шаблону',
            'email'=>'":attribute" должен быть валидным',
            'confirmed'=>'Пароли не совпадают'
        ];
    }

    public function attributes(){
        return [
            'username'=>'Имя',
            'password'=>'Пароль',
            'birth_date' => 'Дата рождения'
        ];
    }
}
