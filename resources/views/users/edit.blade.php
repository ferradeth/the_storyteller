@extends('templates.custom')
@section('title', 'Редактирование')
@section('content')
    
    <main class="media-reg">
        @include('inc.message')
        <a href="{{ route('user.profile') }}">Назад в профиль</a><br>
        <a href="{{route('user.edit.pass')}}">Редактировать пароль</a>
        <h1>Редактировать информацию профиля</h1>
        <form action="{{ route('user.update', auth()->id()) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="form-group">
                <label for="username"> Никнейм <span class="required">*</span></label>
                <input type="text" name="username" id="username" value="{{ old('username')??$user->username }}" required>
            </div>
            <div class="form-group">
                <div></div>
            @error('username')
                <p class="is-invalid">{{ $message }}</p>
            @enderror
            </div>
            
            <div class="form-group">
                <label for="email"> Электронная почта <span class="required">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email')??$user->email }}" required>
            </div>
            <div class="form-group">
                <div></div>
            @error('email')
                <p class="is-invalid">{{ $message }}</p>
            @enderror
            </div>
            <div class="form-group">
                <label for="desc"> Обо мне</label>
                <textarea name="description" id="description" rows="5" cols="15">{{ old('description')??$user->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="date"> Дата рождения <span class="required">*</span></label>
                <input type="date" name="birth_date" id="date" value="{{ old('birth_date')??$user->birth_date }}" required>
            </div>
            <div class="form-group">
                <label for="date"> Номер карты </label>
                <input type="text" name="card_number" id="card_number" value="{{ old('card_number')??$user->card_number }}">
            </div>

            <div class="form-group" id="cover-load">
                <label>Аватар</label>
                <label for="avatar" id="cover-label">
                    <div class="label-desc">Нажмите, чтобы загрузить изображение</div>
                    <input type="file" name="avatar" id="avatar" hidden="true">
                </label>
                <div id="file-cont">
                    <img src="{{ $user->avatar }}" style="width: 200px; height: 200px; object-fit: cover" alt="avatar" id="avatarImg">
                </div>
            </div>

            <div class="form-group">
                <label for=""></label>
                <button name="submit" class="btn confirm" id="submit">Обновить</button>
            </div>
        </form>
    </main>
@endsection
@push('script')
    <script src="https://unpkg.com/imask"></script>
    <script>
        const loadFileAdd = document.querySelector('#avatar')
        const contCover = document.querySelector('#file-cont')

        loadFileAdd.addEventListener('change', e=>{
            avatarImg.src=URL.createObjectURL(e.target.files[0])
        })

        const cardInput = document.querySelector('#card_number')

        const maskOptions3 = {
            mask: '0000 0000 0000 0000',
            lazy: false
        }
        const mask3 = new IMask(cardInput, maskOptions3);
    </script>

@endpush
