<div class="card {{ $game->access_id ==3 && auth()->id() != $game->user_id ? 'hide': ''}}" >
    <img src="{{ $game->cover }}" alt="" class="card-img">
    <div class="card-body">
        <div class="card-body-top">
            <a href="{{ route('games.show', $game->id) }}" class="game-title">{{ $game->name }}</a>
            <p class="game-tags"> Тэги:
{{--                {{ var_dump($game->gameTags) }}--}}
                @forelse($game->gameTags as $tag)
                    <a href="{{ route('games.catalog', $tag->tag_id) }}" class="tags">{{$tag->tag->name}}</a>
                @empty
                    <span>Метки отсутствуют</span>
                @endforelse
            </p>
            <a href="{{ route('user.profile', $game->user_id) }}" class="game-author">Автор: {{ $game->user->username }}</a>
            <p class="game-description" style="margin-top: 5px">Описание: {{ $game->description }}</p>
        </div>
        <p class="date-last-update">Последнее обновление: {{ $game->dateClassic($game->updated_at) }}</p>
    </div>
    <div class="likes" data-item="{{$game->id}}" data-user="{{ auth()->check()}}">
        @if(auth()->user() != null)
            <img src="{{ count(auth()->user()->existLike($game->id))>0 ? asset('/icons/like-active.svg'): asset('/icons/like-black.svg')}}" alt="likes">
        @else
            <img src="{{ asset('/icons/like-black.svg')}}" alt="likes">
        @endif
        <span>{{ $game->count_likes}}</span>
    </div>
</div>

@push('script')
    <script>
        const descs = document.querySelectorAll('.game-description')

        descs.forEach(elem=>{
            elem.textContent = limitStr(elem.textContent, 100)
        })
        function limitStr(str, n, symb) {
            if (!n && !symb ) return str;
            if (str.length<n) return str;
            symb = symb || '...';
            return str.substr(0, n - symb.length) + symb;
        }
    </script>
@endpush
