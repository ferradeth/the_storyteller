<nav>
    <div class="left col">
        <a href="{{ route('games.index') }}" id="logo"> The storyteller </a>

        <form class="search" action="{{ route('games.search')}}">
            <input type="text" name="search" placeholder="Поиск по сайту">
            <button id="search"> <img src="{{ asset('icons/search.svg') }}" alt="поиск"> </button>
        </form>
    </div>

    <div class="right col">
        <a href="{{ route('games.catalog') }}" class="pages">Каталог</a>
        <a href="{{ route('info') }}" class="pages">О нас</a>
{{--        <div class="menu hide">--}}
{{--            <a class="nav-link" href="#">Контакты</a>--}}
{{--            <a class="nav-link" href="#">FAQ</a>--}}
{{--            <a class="nav-link" href="#">Рандомная новелла</a>--}}
{{--        </div>--}}

        @guest()
            <a class="nav-link signup" href="{{ route('login') }}">Вход</a>
            <a class="nav-link register" href="{{ route('user.reg') }}">Регистрация</a>
        @endguest
        @auth()
            <button id="notifications">
                <img src="{{ asset('icons/notify.svg') }}" alt="уведомления">
            </button>

            <button id="profile">
                <img src="{{auth()->user()->avatar }}" alt="изображение профиля">
            </button>

            <div class="menu hide" id="profileMenu">
                <a class="menu-link" id="link1" href="{{ route('user.profile') }}">Мой профиль</a>
                <a class="menu-link" href="{{ route('user.edit') }}">Настройки профиля</a>
                <a class="menu-link" href="{{ route('logout') }}">Выход</a>
            </div>
            <div class="notify-menu hide" id="notifyMenu">
                <h3>Уведомления</h3>
                @forelse(auth()->user()->notifies as $notify)
                    <div class="notify">
                        <p class="date">{{ $notify->created_at }}</p>
                        <p>{{ $notify->message }}</p>

                    </div>
                @empty
                    <p class="empty notify"> Пока нет уведомлений</p>
                @endforelse
            </div>
        @endauth
    </div>
</nav>

@push('script')
    <script src="{{ asset('js/script.js') }}">
    </script>
@endpush
