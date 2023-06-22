<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>Вход в админ-панель</title>
</head>
<body>
    <form action="{{ route('admin.login.check') }}" method="post" class="login-form">
    @csrf
        <label for="login">Логин:
            <input type="text" name="login" id="login" class="form-control">
        </label>
        @error('login')
        <span>{{ $message }}</span>
        @enderror
        <label for="password">Пароль:
            <input type="password" name="password" id="password" class="form-control">
        </label>
        @error('password')
        <span>{{ $message }}</span>
        @enderror
        <br>
        @error('errorLogin')
        <span>{{ $message }}</span>
        @enderror
        <button class="btn-signup btn btn-primary">Войти</button>
    </form>
</body>
</html>
