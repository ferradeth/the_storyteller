@extends('templates.admin')
@section('title', 'Подписки')
@section('content')
    @include('inc.message')
    <h1>Подписки</h1>
    <div class="grid-row head">
        <p>Период и дата оформления</p>
        <p>Статус</p>
        <p>Автор</p>
        <p>Подписчик</p>
        <div></div>
    </div>
    @forelse($subs as $sub)
        <div class="grid-row">
            <p class="title">{{ $sub->period }} месяцев<br>{{ $sub->created_at }}</p>
            <p>{{ $sub->payed? 'Оплачена':'Не оплачена'}}</p>
            <a href="{{ route('user.profile', $sub->user_id) }}">{{ $sub->user->username }}</a>
            <a href="{{ route('user.profile', $sub->sub_id) }}">{{ $sub->sub->username }}</a>
            <div class="btns">
                <form action="{{ route('admin.subs.del', $sub->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button class="btn ban">Отменить</button>
                </form>
                @if(!$sub->payed)
                <a href="{{ route('admin.subs.confirm', $sub->id) }}" class="btn">Подтвердить</a>
                @endif
            </div>

        </div>
    @empty
        <p class="empty">Пусто</p>
    @endforelse
@endsection
