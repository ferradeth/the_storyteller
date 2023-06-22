@extends('templates.admin')
@section('title', 'Администрирование игр')
@section('content')
    @include('inc.message')
    <h1>Игры со статусом {{ $status->name }}</h1>
    <div class="cont">
        <div class="grid-row head">
            <p>Название игры</p>
            <p>Автор</p>
            <p></p>
            <p></p>
            <p></p>
        </div>
        @forelse($games as $game)
            <div class="grid-row">
                <p class="title">{{ $game->name }}</p>
                <p>{{ $game->user->username }}</p>
                <a class="view" href="{{ route('admin.games.show', $game->id) }}">Посмотреть</a>
                @if($status->id == 1)
                    <button class="confirm" data-id="{{ $game->id }}">Одобрить</button>
                    <button class="cancel" data-id="{{ $game->id }}">Отклонить</button>
                @elseif($status->id == 3)
                    <button class="confirm" data-id="{{ $game->id }}">Одобрить</button>
                @endif
            </div>
        @empty
            <p class="empty">Пусто :(</p>
        @endforelse
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
        document.querySelectorAll('.confirm').forEach(item => {
            item.addEventListener('click', e => {
                modalWrapper.style.display = 'flex'
                gameId.value = e.target.dataset.id
                actForm.action = "{{ route('admin.games.confirm') }}"
            })
        })

        document.querySelectorAll('.cancel').forEach(item => {
            item.addEventListener('click', e => {
                modalWrapper.style.display = 'flex'
                gameId.value = e.target.dataset.id
                actForm.action = "{{ route('admin.games.cancel') }}"
            })
        })

        document.querySelectorAll('.close').forEach(item => {
            item.addEventListener('click', e => {
                modalWrapper.style.display = 'none'
            })
        })
    </script>
@endpush
