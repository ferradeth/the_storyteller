@extends('templates.custom')
@section('title', 'My story')
@section('content')
@include('inc.message')
<main class="main-page">
    <section>
        <div class="popular">
            <div class="header">Популярное</div>
            <div class="popular-games">
                @forelse($popular as $game)
                    <a href="{{ route('games.show', $game->id) }}">
                        <div class="pop-game" style="background: linear-gradient(to top, rgb(0,0,0) 0%, rgba(255,255,255,0) 100%), url('{{ $game->cover }}'); background-size: cover; background-position: center">
                            {{--                        <img src="{{$game->cover}}" alt="game-cover">--}}
                            <p class="pop-game-title">{{ $game->name }}</p>
                        </div>
                    </a>
                @empty
                    <p class="empty">Пока тут пусто :(</p>
                @endforelse
            </div>
        </div>
        <div class="last-update">
            <div class="update-head">
                <p>Последние обновления</p>
                <div class="filt-updates">
                    <button class="all active" id="all">Все</button>
                    @auth()
                    <button class="subs" id="subs">Подписки</button>
                    @endauth
                </div>
            </div>
            <div class="container">
{{--                {{ var_dump($games_sub) }}--}}
                @forelse($games as $game)
                @include('inc.card')
                @empty
                    <p class="empty">Пока тут пусто :(</p>
                @endforelse
            </div>
        </div>
    </section>
    <section>
        <div class="top-authors">
            <div class="header">Топ авторов</div>
            <ol class="authors">
{{--                {{ var_dump($top_authors) }}--}}
                @php
                  $number =  1;
                @endphp
                @forelse($top_authors as $key=>$author)
                    <li class="author-top">
                        <span class="top-number">{{ $key+1 }}</span>
                        <div class="user-info">
                            <img class="user-top-img" src="{{ $author->avatar }}" alt="profile pic">
                            <a href="{{ route('user.profile', $author->id) }}">{{ $author->username }}</a>
                        </div>
                        <span class="followers">{{ $author->numberFollowers() }}</span>
                    </li>
{{--                    @php--}}
{{--                        $number = $number + 1;--}}
{{--                    @endphp--}}
                @empty
                    <p class="empty">Станьте первым!</p>
                @endforelse
            </ol>
        </div>
        <div class="tags-cont">
            <div class="header">Метки</div>
            <div class="tags-name">
                @foreach($tags as $tag)
                    <a href="{{ route('games.catalog', $tag->id) }}" class="tag">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
@push('script')
    <script src="{{ asset('js/fetch.js') }}"></script>
    <script>
        const allBtn = document.querySelector('#all')
        const subBtn = document.querySelector('#subs')

        function likes(){
            const likeBtns = document.querySelectorAll('.likes')

            likeBtns.forEach(elem =>{
                elem.addEventListener('click', async (e)=>{
                    console.log(e.currentTarget.dataset.item)
                    if (elem.dataset.user){
                        let res = await postDataJSON('{{ route('games.like')}}', e.currentTarget.dataset.item, '{{ csrf_token() }}');
                        if(res){
                            elem.style.background = '#7645C7';
                            let last = document.querySelector(`div[data-item="${elem.dataset.item}"] span`).textContent;
                            console.log(last)
                            document.querySelector(`div[data-item="${elem.dataset.item}"] span`).textContent = (parseInt(last) + 1).toString()
                            elem.children[0].src = "{{ asset('/icons/like-active.svg') }}";
                        }
                        else{
                            elem.style.background = '#7645C7';
                            let last = document.querySelector(`div[data-item="${elem.dataset.item}"] span`).textContent;
                            console.log(last)
                            document.querySelector(`div[data-item="${elem.dataset.item}"] span`).textContent = (parseInt(last) - 1).toString()
                            elem.children[0].src = "{{ asset('/icons/like-black.svg') }}";
                        }
                        console.log(res)
                    }
                    else{
                        location = "{{ route('login') }}"
                    }
                })
            })
        }
        likes()


        allBtn.addEventListener('click', ()=>{
            subs.classList.toggle('active')
            all.classList.toggle('active')
            document.querySelector('.container').textContent = ''
            document.querySelector('.container').insertAdjacentHTML('beforeend', `
            @foreach($games as $game)
            @include('inc.card')
            @endforeach
            `)
            likes()
        })

        subBtn.addEventListener('click',()=>{
            subs.classList.toggle('active')
            all.classList.toggle('active')
            document.querySelector('.container').textContent = ''
            document.querySelector('.container').insertAdjacentHTML('beforeend',
                `@forelse($games_sub as $game)
                    @include('inc.card')
                    @empty
                    <p class="empty">Тут пока пусто</p>
                @endforelse`

            )
            likes()
        })
    </script>
@endpush
