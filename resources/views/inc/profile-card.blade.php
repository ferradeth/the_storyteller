<div class="profile-card {{ $game->access_id ==3 && auth()->id() != $game->user_id ? 'hide': ''}}" >
    <img src="{{ $game->cover }}" alt="" class="card-img">
    <div class="card-body">
        <div class="card-body-top">
            <div class="title-row" style="margin-bottom: 10px; justify-content: flex-start">
                <a href="{{ route('games.show', $game->id) }}" class="game-title" style="margin-bottom: 0">{{ $game->name }}</a>
                <div class="profile-likes">
                    <img src="{{ asset('/icons/like-black.svg') }}" alt="">
                    <span>{{ $game->count_likes }}</span>
                </div>
            </div>
            <p class="game-tags"> Тэги:
                @forelse($game->gameTags as $tag)
                    <a href="{{ route('games.catalog', $tag->tag_id) }}" class="tags">{{$tag->tag->name}}</a>
                @empty
                    <span>Метки отсутствуют</span>
                @endforelse
            </p>
            @if($game->user_id==auth()->id())
            <p class="game-check">Статус: {{ $game->check->name }}</p>
            @else
            <p>Автор: <a href="{{ route('user.profile', $game->user_id) }}">{{ $game->user->username }}</a></p>
            @endif
            

        </div>
        @if(auth()->id() == $game->user_id)
            <div class="btns">
                <a href="{{ route('games.edit', $game->id) }}" class="btn nav-link" style="background: #B296DF">Редактировать</a>
                <form action="{{ route('games.delete', $game->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button class="btn nav-link" style="background: #ED8989">Удалить</button>
                </form>
                @if($game->access_id != 3)
                    <a href="{{ route('games.hide', $game->id) }}" class="btn nav-link"  style="background: #96B4C4">Скрыть</a>
                @else
                    <a href="{{ route('games.open', $game->id) }}" class="btn nav-link"  style="background: #96B4C4">Показать</a>
                @endif
            </div>
        @else
            <p class="date-last-update">Последнее обновление: {{ $game->dateClassic($game->updated_at) }}</p>
        @endif
    </div>

</div>
