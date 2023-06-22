@extends('templates.admin')
@section('title', $game->name)
@section('content')
        <a href="{{ route('admin.games.check') }}">Назад</a>
        @include('inc.message')
            <p id="statusMessage" class="alert alert-success" hidden></p>
            <div class="main-game-info">
                <img class="main-game-img" src="{{ $game->cover }}">
                <div class="main-game-chars">
                    <div class="title-row">
                        <div class="title-left">
                            <p class="main-game-title">{{$game->name}}</p>
                        </div>
                        <div class="addition-inf">
                            <p>{{ $game->dateClassic($game->updated_at) }}</p>
                        </div>
                    </div>

                    <a href="{{ route('user.profile', $game->user_id) }}" class="game-author">Автор: {{ $game->user->username }}</a>
                    <p class="main-game-tags"> Тэги:
                        @forelse($game->gameTags as $tag)
                            <a href="#" class="main-tags">{{$tag->tag->name}}</a>
                        @empty
                            <span>Метки отсутствуют</span>
                        @endforelse
                    </p>

                    <p class="game-language">Язык: {{ $game->language }}</p>
                    <div class="game-desc">Описание: {{ $game->description }}</div>
                    <a class="game-link" href="{{ $game->file_link }}" id="download" data-game="{{ $game->id }}">Скачать новеллу</a>
                </div>
            </div>

            <div class="game-screens">
                <h3 class="show-h">Скриншоты</h3>
                <div class="screens-cont">
                    @if( count($game->images)>0)
                        <div class="slider">
                            @foreach($game->images as $key=>$image)
                                <img src="{{  $image->image  }}" alt="screen" class="slider-img">
                            @endforeach
                        </div>
                </div>

                @else
                    <p>Скриншоты пока отсутсвуют.</p>
                @endif
            </div>
@endsection
