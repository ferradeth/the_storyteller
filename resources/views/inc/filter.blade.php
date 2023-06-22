<form action="{{ route('games.filter') }}" method="get" class="filter">
    @if(isset($user))
        <input type="hidden" value="{{ $user ? $user->id : ''}}" name="user">
        <input type="hidden" value="" id="tab-id" name="tab">
    @endif
    <div class="sort">
        <p class="filt-head">Сортировка</p>
        <label>
            <input type="radio" name="sort"
                   value="name" {{ old('filters')? isset(old('filters')['sort'])? old('filters')['sort']=='name'?'checked':'':'':'' }}>
            По названию
        </label>
        <label>
            <input type="radio" name="sort"
                   value="updated_at" {{ old('filters')? isset(old('filters')['sort'])?  old('filters')['sort']=='updated_at'?'checked':'':'':'' }}>
            По дате обновления
        </label>
        <label>
            <input type="radio" name="sort"
                   value="created_at" {{ old('filters')? isset(old('filters')['sort'])? old('filters')['sort']=='created_at'?'checked':'':'':'' }}>
            По дате создания
        </label>
        <label>
            <input type="radio" name="sort"
                   value="count_likes" {{ old('filters')? isset(old('filters')['sort'])? old('filters')['sort']=='count_like'?'checked':'':'':'' }}>
            По количеству лайков
        </label>
    </div>
    <hr>
    <div class="sort">
        <p class="filt-head">Порядок сортировки</p>
        <label>
            <input type="radio" name="orderSort"
                   value="desc" {{ old('filters')? isset(old('filters')['orderSort'])? old('filters')['orderSort']=='desc'?'checked':'':'':'' }}>
            По убыванию
        </label>
        <label>
            <input type="radio" name="orderSort"
                   value="asc" {{ old('filters')? isset(old('filters')['orderSort'])? old('filters')['orderSort']=='asc'?'checked':'':'':''}}>
            По возрастанию
        </label>
    </div>
    <hr>
    <div class="tags_filt">
        <p class="filt-head">Тэги</p>
        @foreach($tags as $tag)
            <label>
                <input type="checkbox" value="{{ $tag->id }}"
                       name="tags[]" {{ array_search($tag->id, old('filters')['tags']??[])?'checked':'' }}>
                {{ $tag->name }}
            </label>
        @endforeach
    </div>
    <button class="btn">Применить</button>
</form>
