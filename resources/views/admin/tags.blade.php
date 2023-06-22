@extends('templates.admin')
@section('title', 'Тэги')
@section('content')
    @include('inc.message')
    <h1>Список тэгов</h1>
    <button class="btn" id="create" style="margin-bottom: 15px">Добавить тэг</button>
    <div class="cont">
    @forelse($tags as $tag)
        <div class="grid-row">
            <p class="title">{{ $tag->name }}</p>
            <div></div>
            <div></div>
            <button class="btn update" data-tag="{{ $tag->id }}" data-name="{{$tag->name}}">Обновить</button>
            <form method="post" action="{{ route('admin.tags.delete', $tag->id) }}">
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
                <h3 id="modalTitle">Добавить тэг</h3>

                <input type="text" name="name" id="tagName">
                <button class="btn" style="width: 100%">Подтвердить</button>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        const createBtn = document.getElementById('create')
        const updateBtns = document.querySelectorAll('.update')

        createBtn.addEventListener('click', (e)=>{
            // modalWrapper.classList.toggle('hide')
            modalWrapper.style.display = "flex";
            form.action = "{{ route('admin.tags.create') }}"
            modalTitle.textContent = 'Добавить тэг'
        })

        updateBtns.forEach(btn=>{
            btn.addEventListener('click', e=>{
                console.log(e.currentTarget.dataset.tag)
                // modalWrapper.classList.toggle('hide')
                modalWrapper.style.display = "flex";
                form.action = "{{ route('admin.tags.update') }}"
                modalTitle.textContent = 'Изменить тэг'
                form.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="id" value="${e.currentTarget.dataset.tag}">`)
                tagName.value = e.currentTarget.dataset.name
            })
        })
        closeBtn.addEventListener('click', ()=>{
            modalWrapper.style.display = "none";
        })
    </script>
@endpush
