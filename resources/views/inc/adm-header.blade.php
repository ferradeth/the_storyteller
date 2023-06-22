<nav>
    <a href="{{ route('admin.logout') }}" class="btn-adm">Выход</a>
    <a href="{{ route('admin.index') }}">Главная</a>
    <a href="{{ route('admin.games.check') }}">Ожидают подтверждения</a>
    <a href="{{ route('admin.games.canceled') }}">Отклонённые игры</a>
    <a href="{{ route('admin.games.index') }}">Список игр</a>
    <a href="{{ route('admin.admins.index') }}">Администраторы</a>
    <a href="{{ route('admin.users.index') }}">Блокировка пользователей</a>
    <a href="{{ route('admin.tags.index') }}">Тэги</a>
    <a href="{{ route('admin.comments.index') }}">Комментарии</a>
    <a href="{{ route('admin.subs.index') }}">Подписки</a>
</nav>
