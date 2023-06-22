@extends('templates.admin')
@section('title', 'Блокировка пользователей')
@section('content')
    @include('inc.message')
    <a href="{{ route('admin.users.index') }}">Вернуться к списку пользователей</a>
    @if($followers??false)
        <h1>Подписчики {{ $user }}</h1>
    @elseif($subscribes)
        <h1>Подписки {{ $user }}</h1>
    @endif

    <div class="cont">
        <div class="grid-row head">
            <p>Никнейм</p>
            <p>Почта</p>
            <p>Количество подписок</p>
            <p>Количество подписчиков</p>
            <div>
            </div>
        </div>
        @if($followers??false)
            @forelse($followers as $follow)
                <div class="grid-row">
                    <p class="title">{{ $follow->sub->username  }}</p>
                    <p>{{ $follow->sub->email }}</p>
                    <p>{{ $follow->sub->numberSubs()}}</p>
                    <p>{{ $follow->sub->numberFollowers() }}</p>
                    <div class="btns">
                        <a href="{{ $follow->sub->ban ?route('admin.users.unban', $follow->sub->id):route('admin.users.ban', $follow->sub->id) }}" class="btn ban">{{ $follow->sub->ban ?"Разблокировать":"Заблокировать" }}</a>
                    </div>
                </div>
            @empty
                <p class="empty">Пусто</p>
            @endforelse
        @elseif($subscribes)
            @forelse($subscribes as $follow)
                <div class="grid-row">
                    <p class="title">{{ $follow->user->username  }}</p>
                    <p>{{ $follow->user->email }}</p>
                    <p>{{ $follow->user->numberSubs()}}</p>
                    <p>{{ $follow->user->numberFollowers() }}</p>
                    <div class="btns">
                        <a href="{{ $follow->user->ban ?route('admin.users.unban', $follow->user->id):route('admin.users.ban', $follow->user->id) }}" class="btn ban">{{ $follow->user->ban ?"Разблокировать":"Заблокировать" }}</a>
                    </div>
                </div>
            @empty
                <p class="empty">Пусто</p>
            @endforelse
        @endif
    </div>

@endsection
