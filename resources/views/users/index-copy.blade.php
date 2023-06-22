@extends('templates.custom')
@section('title', $user->username)
@section('content')
    <main class="main-profile">
        @include('inc.message')
        <div class="profile-cont">
            <div class="left">
                <img src="{{ $user->avatar }}" alt="изображение профиля">
                <div class="profile-info">
                    <h2>{{ $user->username }}</h2>
                    <p>Дата рождения: {{ $user->dateClassic }}</p>
                    <p>Обо мне: {{ $user->description ?? 'Описание профиля отсуствует'}}</p>

                    <div class="followers">
                        <span>{{ $user->numberFollowers($user) }} подписчиков</span>
                        <span>{{ $user->numberSubs($user) }} подписок</span>
                        <span>{{ count($user->filterWorks(0)->get()) }} работ</span>
                    </div>
                </div>
            </div>
            @if(auth()->id() == $user->id)
            <div class="profile-control">
                <a href="{{ route('games.create') }}" class="btn nav-link edit {{ $user->ban?'disable':'' }}">Добавить работу</a>
                <a href="{{ route('user.edit') }}" class="btn nav-link edit">Редактировать</a>
                <a href="{{ route('logout') }}" class="btn nav-link exit">Выйти</a>
            </div>
            @else
            <div class="profile-control">
                @if (count($user->subs->where('sub_id', auth()->id())) == 0)
                    <a href="#" class="btn nav-link edit" id="makeSub">Подписаться</a>
                @else
                    <form method="post" action="{{ route('sub.del', $user->id) }}">
                        @method('delete')
                        @csrf
                        <button class="btn nav-link edit" id="makeUnsub">Отписаться</button>
                    </form>
                @endif
            </div>
            @endif
        </div>
{{--        {{ $user->processWorks() }}--}}
{{--        @foreach ($user->processWorks() as $game)--}}
{{--            {{ $game }}--}}
{{--        @endforeach--}}
{{--        <hr>--}}
{{--        @foreach ($user->allWorks() as $game)--}}
{{--            {{ $game }}--}}
{{--        @endforeach--}}
        <div class="profile-games">
            @include('inc.filter')
            <div class="games">
                <div class="tabs">
                    <a href="{{route('games.filter-tab', [$user->id, 0])}}/" class="tab-a">
                    <div class="tab-header__item" data-current="0" data-user="{{ $user->id }}">
                        <span>Мои работы</span>
                        <span>{{ count($user->filterWorks(0)->get())}}</span>
                    </div>
                    </a>
                    <a href="{{route('games.filter-tab', [$user->id, "all"])}}/"  class="tab-a">
                    <div class="tab-header__item" data-current="all"  data-user="{{ $user->id }}">
                        <span>Подписки</span>
                        <span>{{ count($user->filterWorks('all')->get())}}</span>
                    </div>
                    </a>
                    @foreach($statuses as $status)
                        <a href="{{route('games.filter-tab', [$user->id, $status->id])}}/" class="tab-a">
                        <div class="tab-header__item" data-current="{{$status->id}}"  data-user="{{ $user->id }}">
                            <span>{{ $status->name }}</span>
                            <span>{{ count($user->filterWorks($status->id)->get())}}</span>
                        </div>
                        </a>
                    @endforeach
                </div>
                <div id="gamesCont">
                    @forelse(old('filter_games')??$games as $game)
                        @include('inc.profile-card')
                    @empty
                        <p class="empty">Тут пока пусто</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <div class="modal-wrapper hide" id="modalWrapper">
        <div class="modal-window">
            <span id="closeBtn">&times;</span>
            <form method="post" action="{{route('sub.make', $user->id)}}">
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
{{--    скрипт на модальное окно подписки--}}
    <script>
        const subVer = document.querySelectorAll('.sub-ver')
        makeSub.addEventListener('click', e =>{
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
                if(elem.children[0].checked){
                    console.log(elem.children[0].checked)
                    clearActive()
                    elem.classList.contains('active')?elem.classList.remove('active'):elem.classList.add('active')
                }

            })
        })
    </script>
{{--    скрипт табов--}}
    <script>
        const tabs = document.querySelectorAll('.tab-header__item')
        if(sessionStorage.getItem('currentTab')==null){
            sessionStorage.setItem('currentTab', 0);
        }
        console.log(document.querySelector(`div[data-current="${sessionStorage.getItem('currentTab')}"]`));


        document.querySelector(`div[data-current="${sessionStorage.getItem('currentTab')}"]`).classList.add('active')
        document.getElementById('tab-id').value = sessionStorage.getItem('currentTab');
        const clearTab = () => {
            tabs.forEach(item => {
                item.classList.remove('active')
            });
        }

        tabs.forEach(tab=>{
            tab.addEventListener('click', async e=>{
                clearTab()
                sessionStorage.setItem('currentTab', e.currentTarget.dataset.current);
                document.getElementById('tab-id').value = e.currentTarget.dataset.current;
            })
        })
    </script>

@endpush
