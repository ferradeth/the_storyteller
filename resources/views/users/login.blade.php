@extends('templates.custom')
@section('title', 'Вход')
@section('content')

<main class="media-reg">
    @include('inc.message')
    <form action="{{route('login.check')}}" method="post" class="reg">
        @csrf
        <h1>Авторизация</h1>

        <input type="email" class="form-control" name="email" id="email"
               value="{{ old('email') }}" placeholder="Email">
        @error('email')
        <span  class="is-invalid">{{ $message }}</span>
        @enderror

        <input type="password" class="form-control " name="password"
               id="password" placeholder="Пароль">
        @error('password')
        <span  class="is-invalid">{{ $message }}</span>
        @enderror

        <button id="btn-signup" class="btn register" style="margin-left: 0">Войти</button>
        <p>Ещё нет аккаунта? Тогда <a href="{{ route('user.reg') }}">зарегистрируйтесь</a>!</p>
    </form>
</main>

@endsection
