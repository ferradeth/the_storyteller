@extends('templates.admin')
@section('title', 'Администраторы')
@section('content')
    @include('inc.message')
    <h1>Администраторы</h1>
    <div class="cont">
        <div class="grid-row head">
            <p>Логин</p>
            <p></p>
            <p></p>
            <p></p>
            <p><button id="addAdmin" class="btn"> Добавить администратора </button></p>
        </div>
        @forelse($admins as $admin)
            <div class="grid-row">
                <p class="title">{{ $admin->login}}</p>
                <p></p>
                <p></p>
                <button class="btn update" data-id="{{ $admin->id }}" data-name="{{$admin->login}}">Обновить</button>
                <form method="post" action="{{ route('admin.admins.delete', $admin->id) }}">
                    @csrf
                    @method('delete')
                    <button class="btn ban">Удалить</button>
                </form>
            </div>
        @empty
            <p class="empty">Пусто</p>
        @endforelse
    </div>

    <div id="modalWrapper" class="modal-wrapper">
        <div class="modal-window">
            <span id="closeBtn">&times;</span>
            <form action="" id="form" method="post">
                @csrf
            <div id="formCont"></div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        const createBtn = document.getElementById('addAdmin')
        const updateBtns = document.querySelectorAll('.update')

        createBtn.addEventListener('click', (e)=>{
            // modalWrapper.classList.toggle('hide')
            modalWrapper.style.display = "flex";
            form.action = "{{ route('admin.admins.create') }}"
            formCont.textContent = ''
            formCont.insertAdjacentHTML('afterbegin', `
                @csrf
                <input type="text" name="login" placeholder="Логин" style="margin-bottom: 5px">
                <button class="btn" style="width: 100%">Подтвердить</button>`)
            formCont.insertAdjacentHTML('afterbegin', ` <input type="password" name="password" placeholder="Пароль"  style="margin-bottom: 5px">`)
            formCont.insertAdjacentHTML('afterbegin', `<h3>Добавить админа</h3>`)
        })

        updateBtns.forEach(btn=>{
            btn.addEventListener('click', e=>{
                console.log(e.currentTarget.dataset.tag)
                // modalWrapper.classList.toggle('hide')
                modalWrapper.style.display = "flex";
                form.action = "{{ route('admin.admins.update') }}"
                formCont.textContent = ''
                formCont.insertAdjacentHTML('afterbegin', `
                <input type="text" name="login" value="${e.currentTarget.dataset.name}" placeholder="Логин" style="margin-bottom: 5px">
                <button class="btn" style="width: 100%">Подтвердить</button>`)
                formCont.insertAdjacentHTML('afterbegin', `<h3>Изменить логин</h3>`)
                formCont.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="id" value="${e.currentTarget.dataset.id}">`)
            })
        })
        closeBtn.addEventListener('click', ()=>{
            modalWrapper.style.display = "none";
        })
    </script>
@endpush

