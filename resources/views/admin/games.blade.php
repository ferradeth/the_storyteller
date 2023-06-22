@extends('templates.admin')
@section('title', 'Администрирование игр')
@section('content')
    @include('inc.message')
    @if($user)
        <a href="{{ route('admin.games.index') }}">Вернуться к общему списку</a>
    @endif
    <h1>Список игр {{ $user??'' }}</h1>
    <div class="cont">
        <div class="grid-row head">
            <p>Название игры</p>
            <p>Количество лайков</p>
            <p>Автор</p>
            <p>Количество дизлайков</p>
            <div></div>
        </div>
        @foreach($games as $game)
            <div class="grid-row">
                <a href="{{ route('games.show', $game->id) }}" class="title">{{ $game->name }}</a>
                <p>{{ $game->count_likes }}</p>
                <a href="{{ route('admin.games.index', $game->user_id) }}">{{ $game->user->username }}</a>
                <p class="count_bans">{{ $game->count_dislikes }}</p>
                <div class="btns">
                    <button
                        class="btn ban"
                        data-baned="{{ $game->baned }}" data-id="{{ $game->id }}">{{ $game->baned ?"Разблокировать":"Заблокировать" }}</button>
                </div>
            </div>
        @endforeach
    </div>

    <div id="modalWrapper" class="modal-wrapper">
        <div class="modal-window checking">
            <span id="closeBtn" class="close">&times;</span>
            <form id="actForm" action="">
                <h3>Подтвердите действие</h3>
                <input type="hidden" id="gameId" name="game_id">
                <div class="btns">
                    <button class="view btn-adm">Подтвердить</button>
                    <button class="close btn-adm">Отменить</button>
                </div>

            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.querySelectorAll('.ban').forEach(item => {
            item.addEventListener('click', e => {
                modalWrapper.style.display = 'flex'
                gameId.value = e.target.dataset.id
                actForm.action = e.target.dataset.baned == 1 ?"{{ route('admin.games.unban') }}":"{{ route('admin.games.ban') }}"
            })
        })

        document.querySelectorAll('.close').forEach(item => {
            item.addEventListener('click', e => {
                e.preventDefault();
                modalWrapper.style.display = 'none'
            })
        })
    </script>
@endpush
