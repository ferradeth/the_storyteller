@extends('templates.admin')
@section('title', 'Комментарии')
@section('content')
    @include('inc.message')
    <h1>Комментарии</h1>
    <div class="grid-row head">
        <p>Автор</p>
        <p>Комментарий</p>
        <p>Дата создания</p>
        <p></p>
        <div>
{{--            <form action="{{ route('admin.comments.delete', $comment->id) }}" method="post">--}}
{{--                @csrf--}}
{{--                @method('delete')--}}
                <button class="btn ban" id="deleteBtn">Удалить выбранные</button>
            </form>
        </div>
    </div>
    @forelse($comments as $comment)
        <div class="grid-row" id="id{{ $comment->id }}">
            <p class="title">{{ $comment->user->username }}</p>
            <p>{{ $comment->message }}</p>
            <p>{{ $comment->created_at }}</p>
            <p> <input type="checkbox" value="{{ $comment->id }}" name="comments"></p>
{{--            <form action="{{ route('admin.comments.delete', $comment->id) }}" method="post">--}}
{{--                @csrf--}}
{{--                @method('delete')--}}
{{--                <button class="btn ban">Удалить</button>--}}
{{--            </form>--}}
        </div>
    @empty
    <p class="empty">Пусто</p>
    @endforelse
@endsection
@push('script')
    <script src="{{ asset('js/fetch.js') }}"></script>
    <script>
        deleteBtn.addEventListener('click', async ()=>{
            const checked = document.querySelectorAll("input[type='checkbox']:checked")
            // console.log(checked[0].value)
             let array = [...checked].map((elem)=>elem.value)

            let res = await postDataJSON("{{ route('admin.comments.delete') }}", array, "{{ csrf_token() }}")
            if (res) {
                alert('Комментарии успешно удалены')
                array.forEach(id=>{
                    document.getElementById(`id${id}`).remove()
                })
            }
            else alert('Не удалось удалить комментарии')
        })
    </script>
@endpush
