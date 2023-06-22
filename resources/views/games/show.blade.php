@extends('templates.custom')
@section('title', $game->name)
@section('content')

    <main class="media-reg">
        @if(auth('admin')->user() !== null)
            <a href="{{ route('admin.games.index') }}">Вернуться на панель администратора</a><br>
        @endif
        <a href="{{ route('games.index') }}">На главную</a>
        @include('inc.message')
        @if(!$game->baned)
            <p id="statusMessage" class="alert alert-success" hidden></p>
            <div class="main-game-info">
                <img class="main-game-img" src="{{ $game->cover }}">
                <div class="main-game-chars">
                    <div class="title-row">
                        <div class="title-left">
                            <p class="main-game-title">{{$game->name}}</p>
                            @auth()
                            <form method="post">
                                @csrf
                                <select id="statusSelect">
                                    <option value="0" data-game="{{ $game->id }}" hidden> Присвоить статус...</option>
                                    @foreach($statuses as $status)
                                        @if($game->gameStatuses->where('user_id', auth()->id())->first() != null)
                                            <option value="{{ $status->id }}" data-game="{{ $game->id }}" class="status-trigger" {{ auth()->check() && $game->gameStatuses->where('user_id', auth()->id())->first()->status_id == $status->id? 'selected': '' }}>{{ $status->name }}</option>
                                        @else
                                            <option value="{{ $status->id }}" data-game="{{ $game->id }}" class="status-trigger">{{ $status->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
                            @endauth
                        </div>
                        <div class="addition-inf">
                            <p>{{ $game->dateClassic($game->updated_at) }}</p>
    {{--                        <img src="{{ asset('/icons/fav.svg') }}" alt="favorite" class="fav" id="fav">--}}
                            <div class="like">
                                @if(auth()->user() != null)
                                    <img src="{{ count(auth()->user()->existLike($game->id))>0 ? asset('/icons/like-active.svg'): asset('/icons/like-black.svg')}}" alt="likes" id="like" data-game="{{$game->id}}">
                                @else
                                    <img src="{{ asset('/icons/like-black.svg')}}" alt="likes" id="like" data-game="{{$game->id}}">
                                @endif
                                <p>{{ $game->count_likes }}</p>
                            </div>
{{--                            <button class="ban" id="ban" data-game="{{$game->id}}">Пожаловаться</button>--}}

                            <div class="like">
                                @if(auth()->user() != null)
                                    <img src="{{ count(auth()->user()->existDislike($game->id))>0 ? asset('/icons/dislike-active.svg'): asset('/icons/dislike.svg')}}" alt="dislikes" id="dislike" data-game="{{$game->id}}">
                                @else
                                    <img src="{{ asset('/icons/dislike.svg')}}" alt="dislikes" id="dislike" data-game="{{$game->id}}">
                                @endif
                                <p>{{ $game->count_dislikes }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('user.profile', $game->user_id) }}" class="game-author">Автор: {{ $game->user->username }}</a>
                    <p class="main-game-tags"> Тэги:
                        {{--                {{ var_dump($game->gameTags) }}--}}
                        @forelse($game->gameTags as $tag)
                            <a href="{{ route('games.catalog', $tag->tag->id) }}" class="main-tags">{{$tag->tag->name}}</a>
                        @empty
                            <span>Метки отсутствуют</span>
                        @endforelse
                    </p>

                    <p class="game-language">Язык: {{ $game->language }}</p>
                    <p class="game-favs">Добавило в избранные: {{ $game->numberOfFavs() }}</p>
                    <p id="game-downloads">Скачиваний: {{ $game->count_downloads }}</p>
                    <div class="game-desc">Описание: {{ $game->description }}</div>
                    @if(($game->ban <= 5 && $game->access_id == 1) || $game->user_id == auth()->id())
                        <a class="game-link" href="{{ $game->file_link }}" id="download" data-game="{{ $game->id }}">Скачать новеллу</a>
                    @elseif($game->access_id == 2)
                        @if(auth()->check()?count(auth()->user()->checkSub($game->user_id))>0:false)
                            <a class="game-link" href="{{ $game->file_link }}" id="download" data-game="{{ $game->id }}">Скачать новеллу</a>
                        @elseif(auth()->check()?count(auth()->user()->checkPaySub($game->user_id))==0:false)
                            <button class="game-link" id="makeSub" disabled>Оплатите подписку для доступа</button>
                        @else
                            <button class="game-link" id="makeSub">Оформите подписку для скачивания</button>
                        @endif
                    @endif
                </div>
            </div>

            <div class="game-screens">
                <h3 class="show-h">Скриншоты</h3>
                <div class="screens-cont">
                    @if( count($game->images)>0)
    {{--                    <img src="{{ $game->images[0]->image }}" alt="main-screen" id="main-screen">--}}
                        <div class="slider">
                            @foreach($game->images as $key=>$image)
{{--                                <lable class="slider-elem" data-count="{{ $key }}" hidden="true">--}}
{{--                                    <input type="radio" value="{{ $image->image }}" name="screen" hidden="true" data-count="{{ $key }}" {{ $key==0?'checked':'' }}>--}}
                                    <img src="{{  $image->image  }}" alt="screen" class="slider-img">

{{--                                </lable>--}}
                            @endforeach
                        </div>
                </div>

                @else
                    <p>Скриншоты пока отсутсвуют.</p>
                @endif
            </div>
            <form class="comment-input" method="{{ auth()->check()? 'post':'get' }}" action="{{ auth()->check()?route('comments.store', $game->id):route('login')}}">
                @csrf
                <h3 class="show-h">Комментарии</h3>
                <textarea type="text" placeholder="Написать комментарий..." name="message" style="width: 100%; height: 80px; text-align: start; resize: none; margin-bottom: 10px"></textarea>
                <button class="btn sub">Отправить</button>
            </form>
            <div class="comment-cont">
                @foreach($game->comments as $comment)
                    <div class="comment-item">
                        <img src="{{ $comment->user->avatar }}" alt="avatar" class="comment-img">
                        <div class="comment-info">
                            <div class="title-row">
                                <p class="comment-author">{{ $comment->user->username }}</p>
                                <p>{{ $comment->dateClassic() }}</p>
                            </div>
                            <p class="comment-content">{{ $comment->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="empty">Игра заблокирована :(</p>
        @endif
    </main>

    <div class="modal-wrapper hide" id="modalWrapper">
        <div class="modal-window">
            <span id="closeBtn">&times;</span>
            <form method="post" action="{{route('sub.make', $game->user_id)}}">
                @csrf
                <h3>Выберите длительности подписки:</h3>
                <div class="sub-label">
                    <label class="sub-ver">
                        <input type="radio" name="period" value="1" hidden>
                        <p>Месяц</p>
                        <span>300 рублей</span>
                    </label>
                    <label class="sub-ver">
                        <input type="radio" name="period" value="12" hidden>
                        <p>Год</p>
                        <span>3600 рублей</span>
                    </label>
                </div>
                <button class="btn sub">Оформить подписку</button>
                <p class="modal-info">Подписка в любой момент может быть отменена. Оплата возврату не подлежит</p>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('js/fetch.js') }}"></script>
    <script>
        // const options = document.querySelectorAll('.status-trigger')
        // console.log(options)
        // options.forEach(elem=>{
            statusSelect.addEventListener('change', async ()=>{
                console.log(statusSelect.children)
                const options = statusSelect.childNodes
                let statusOpt;
                options.forEach(child=>{
                    if(child.selected) {
                        console.log(child.value)
                        statusOpt = child
                    }
                })
                let res = await postDataJSON("{{ route('games.status') }}", { 'id' : statusOpt.dataset.game, 'status_id': statusOpt.value}, "{{ csrf_token() }}")
                statusMessage.hidden = false
                if (res){
                    statusMessage.textContent = 'Статус успешно установлен!'
                }
                else{
                    statusMessage.textContent = 'Не удалось поменять статус'
                }

            // })
        })
    </script>
    <script>
        const subVer = document.querySelectorAll('.sub-ver')
        makeSub.addEventListener('click', e =>{
            console.log(makeSub)
            // console.log(modalWrapper.classList.toggle('hide'))
            modalWrapper.classList.toggle('hide')
        })

        closeBtn.addEventListener('click', ()=>{
            modalWrapper.classList.toggle('hide')
        })

        const clearActive = () => {
            subVer.forEach(item => {
                item.classList.remove('active')
            });
        }

        subVer.forEach(elem=>{
            console.log(elem)
            elem.addEventListener('change', e=>{
                // console.log(e.currentTarget.classList.toggle('active'))
                // e.currentTarget.classList.toggle('active')
                // console.log(elem.classList.contains('active'))
                if(elem.children[0].checked){
                    console.log(elem.children[0].checked)
                    clearActive()
                    elem.classList.contains('active')?elem.classList.remove('active'):elem.classList.add('active')
                }

            })
        })

    </script>
{{--    добавление в избранное, лайки и жалобы--}}
    <script>
        dislike.addEventListener('click', async e=>{
            let res = await postDataJSON("{{ route('games.dislike') }}", e.target.dataset.game, "{{csrf_token()}}")
            if (res){
                dislike.src = "{{ asset('icons/dislike-active.svg') }}"
                let last = e.target.parentNode.children[1].textContent
                console.log(e.target.parentNode.children[1])
                e.target.parentNode.children[1].textContent = (parseInt(last) + 1).toString()
            }
            else{
                e.target.src = "{{ asset('icons/dislike.svg') }}"
                let last = e.target.parentNode.children[1].textContent
                console.log(e.target.parentNode.children[1])
                e.target.parentNode.children[1].textContent = (parseInt(last) - 1).toString()
            }

        })
        like.addEventListener('click', async e=>{
            let res = await postDataJSON("{{ route('games.like') }}", e.target.dataset.game, "{{csrf_token()}}")
            console.log(res)
            if (res){
                e.target.src = "{{ asset('icons/like-active.svg') }}"
                let last = e.target.parentNode.children[1].textContent
                console.log(e.target.parentNode.children[1])
                e.target.parentNode.children[1].textContent = (parseInt(last) + 1).toString()
            }
            else{
                e.target.src = "{{ asset('icons/like-black.svg') }}"
                let last = e.target.parentNode.children[1].textContent
                console.log(e.target.parentNode.children[1])
                e.target.parentNode.children[1].textContent = (parseInt(last) - 1).toString()
            }

        })
    </script>
{{--    увеличение количества скачиваний--}}
    <script>
        download.addEventListener('click', async e=>{
            // e.preventDefault()
            let res = await postDataJSON("{{ route('games.download') }}", download.dataset.game, "{{ csrf_token() }}")
            if (res){
                document.getElementById("game-downloads").textContent = "Скачиваний: " + res.toString()
            }
        })
    </script>
@endpush
