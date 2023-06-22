@extends('templates.custom')
@section('title', 'Редактирование')
@section('content')
  
    <main class="media-reg">
        @include('inc.message')
        <a href="{{ route('user.profile') }}">Назад в профиль</a>
        <h1>Редактировать пароль</h1>
        <form action="{{ route('user.update.pass') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="new_pass">Новый пароль</label>
                <input type="password" name="password" id="password" placeholder="От 8 символов, >1 заглавная латинская буква и >1 спецсимвол (!#&?)">
            </div>
            <div class="form-group">
                <div></div>
            @error('password')
                <p class="is-invalid">{{ $message }}</p>
            @enderror
            </div>

            <div class="form-group">
                <label for="old_pass">Старый пароль</label>
                <input type="password" name="old_pass" id="old_pass">
            </div>

            <div class="form-group">
                <label for=""></label>
                <button name="submit" class="btn confirm" id="submit">Обновить</button>
            </div>
        </form>
    </main>
@endsection