@extends('templates.custom')
@section('title', 'Каталог')
@section('content')
    @include('inc.message')
    <main class="catalog">
        @include('inc.filter')
        <div class="games-catalog">
            @if(old('filter_games'))
                <a href="{{ route('games.catalog') }}">Сбросить фильтр</a>
            @endif
            @if(isset($title))
                {{ $title }}
            @endif
            @if($filter_tag)
                <h2> Игры с меткой {{ $filter_tag }}</h2>
                    <a href="{{ route('games.catalog') }}">Посмотреть полный каталог</a>
            @endif
            @forelse(old('filter_games')??$games as $game)
                @include('inc.card')
                <hr>
            @empty
                <p class="empty">Пока тут пусто :(</p>
            @endforelse
        </div>
    </main>
@endsection
@push('script')
    <script src="{{ asset('js/fetch.js') }}"></script>
    <script>
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
                        console.log(res)
                    }
                    else{
                        location = "{{ route('login') }}"
                    }
                })
            })
        }
        likes()
    </script>
@endpush
