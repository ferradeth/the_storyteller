@extends('templates.admin')
@section('title', 'Админ-панель')
@section('content')
    @include('inc.message')
    <h1>Игры с 5+ дизлайков</h1>
    <div class="cont">
        <div class="grid-row head">
            <p>Название игры</p>
            <p>Описание игры</p>
            <p>Автор</p>
            <p>Количество жалоб</p>
            <div>

            </div>
        </div>
        @forelse($games as $game)
            <div class="grid-row">
                <a href="{{ route('games.show', $game->id) }}" class="title">{{ $game->name }}</a>
                <p class="description">{{ $game->description }}</p>
                <p class="game-author">{{ $game->user->username }}</p>
                <p class="count_bans">{{ $game->ban }}</p>
                <div class="btns">
                    <a href="{{ $game->baned ?route('admin.games.unban', $game->id):route('admin.games.ban', $game->id) }}" class="btn ban"></a>
                </div>
            </div>
        @empty
            <p class="empty">Пусто</p>
        @endforelse
    </div>

@endsection
