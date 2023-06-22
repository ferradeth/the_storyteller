@extends('templates.admin')
@section('title', 'Блокировка пользователей')
@section('content')
    @include('inc.message')
    <h1>Пользователи</h1>
    <div class="cont">
        <div class="grid-row head">
            <p>Никнейм</p>
            <p>Почта</p>
            <p>Количество подписок</p>
            <p>Количество подписчиков</p>
            <div>
            </div>
        </div>
        @forelse($users as $user)
            <div class="grid-row">
                <a href="{{ route('user.profile', $user->id) }}" class="title">{{ $user->username }}</a>
                <p>{{ $user->email }}</p>
                <a href="{{ route('admin.users.subscribes', $user->id) }}">{{ $user->numberSubs()}}</a>
                <a href="{{ route('admin.users.followers', $user->id) }}">{{ $user->numberFollowers() }}</a>
                <div class="btns">
                    <a href="{{ $user->ban ?route('admin.users.unban', $user->id):route('admin.users.ban', $user->id) }}" class="btn ban">{{ $user->ban ?"Разблокировать":"Заблокировать" }}</a>
                </div>
            </div>
        @empty
            <p class="empty">Пусто</p>
        @endforelse
    </div>

@endsection
