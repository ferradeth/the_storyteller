@extends('templates.custom')
@section('title', 'Редактировать игру')
@section('content')
    <main class="media-reg">
        @include('inc.message')
        <a href="{{ route('user.profile') }}">Назад</a>
        <h1>Редактировать</h1>
        <form method="post" enctype="multipart/form-data" id="form">
            @csrf
            <div class="form-group">
                <label for="name"> Название <span class="required">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name')??$game->name }}">
            </div>

            <div class="form-group">
                <label for="description"> Краткое описание <span class="required">*</span></label>
                <textarea name="description" id="description" rows="5"
                          cols="15">{{ old('description')??$game->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="file_link">Ссылка на файлобменник</label>
                <input type="text" name="file_link" id="file_link" value="{{ old('file_link')??$game->file_link }}">
            </div>


            <div class="form-group">
                <label for="language">Язык</label>
                <input type="text" name="language" id="language" value="{{ old('language')??$game->language }}">
            </div>

            <div class="form-group">
                <label for="tags">Метки</label>

                <div id="select-tags">
                    <span style="font-size: 14px; color: grey">Удаление метки происходит моментально. Добавьте метку снова и сохраните изменения, если вам необходимо её оставить.</span>
                    <div class="select-input" id="selectInput">
                        @foreach($game->gameTags as $tag)
                            <div class="selected-choice" data-id="{{ $tag->tag_id }}">{{ $tag->tag->name }}<span
                                    class="del-btn-fetch" data-target="{{ $tag->tag_id }}" data-game="{{ $game->id }}">&times</span></div>
                        @endforeach
                        <select name="tags[]" id="tags" hidden="true" style="display: none" multiple>
                        </select>
                        {{--                        <div id="selected-options">--}}
                        {{--                            --}}
                        {{--                        </div>--}}
                        <input type="text" id="search-tag" placeholder="Введите и выберите одну или несколько меток">
                    </div>
                    <div class="select-choices" hidden="true" id="selectChoices">
                        @foreach($tags as $tag)
                            <div class="choice-item" id="{{ $tag->id }}">{{ $tag->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Уровень доступа</label>
                <div class="access-inputs">
                    <label>
                        <input type="radio" name="access_id" class="access"
                               value="1" {{ $game->access_id == 1? 'checked': '' }}>
                        Публичный
                    </label>
                    <label>
                        <input type="radio" name="access_id" class="access"
                               value="2" {{ $game->access_id == 2? 'checked': '' }}>
                        По подписке
                    </label>

                </div>

            </div>
            <div class="form-group" id="cover-load">
                <label>Обложка</label>
                <label for="cover" id="cover-label">
                    <div class="label-desc">Нажмите, чтобы загрузить обложку</div>

                    <input type="file" name="cover" id="cover" hidden="true">

                </label>
                <div id="file-cont">
                    <img src="{{ $game->cover }}" alt="cover" style="width: 150px; height: 200px; object-fit: cover"
                         id="cover-img">
                </div>
            </div>

            <div class="form-group" id="cover-load">
                <label>Скриншоты <br>
                    <span style="font-size: 14px; color: grey">! Скриншот удаляется навсегда при нажатии крестика !</span>
                </label>

                <label for="screenshots" id="screen-label">
                    <div class="label-desc">Нажмите, чтобы загрузить скриншоты</div>
                    <input type="file" name="screens[]" id="screenshots" hidden="true" multiple
                           onchange="handleChange(event)">

                </label>

                <div>
                    <div id="screens-cont">
                        @foreach($game->images as $image)
                            <div class="screenItem">
                                <img src="{{ $image->image }}" alt="" class="screens"> <span class="screenDel"
                                                                                             data-target="{{$image->id}}"
                                                                                             onclick="deleteImg(event)">&times</span>
                            </div>
                        @endforeach
                    </div>
                    <div id="new-screens">

                    </div>
                </div>

            </div>

            <div class="form-group">
                <label for=""></label>
                <input type="hidden" name="id" value="{{ $game->id  }}">
                <button name="submit" class="btn confirm" id="submit">Обновить</button>
            </div>

        </form>
    </main>
@endsection

@push('script')
    <script src="{{ asset('js/fetch.js') }}"></script>
    <script>
        const loadFileAdd = document.querySelector('#cover')
        const contCover = document.querySelector('#file-cont')
        const imgCover = document.querySelector('#cover-img')

        loadFilesAdd = document.querySelector('#screenshots')
        contScreens = document.querySelector('#screens-cont')
        contNewScreens = document.querySelector('#new-screens')

        loadFileAdd.addEventListener('change', e => {
            imgCover.src = URL.createObjectURL(e.target.files[0])
        })

        submit.addEventListener('click', async e => {
            e.preventDefault()
            const formData = new FormData(form);

            if (filesStore != []) {
                filesStore.map((file) => {
                    formData.append(`screens[]`, file);
                });
            }
            for (let pair of formData.entries()) {
                console.log(pair[0] + ', ' + pair[1]);
            }

            // for (var pair of formData.entries()) {
            //     console.log(pair[0]+ ', ' + pair[1]);
            // }
            let res = await postJSON(`{{ route('games.update') }}`, formData , '{{ csrf_token() }}');
            console.log(res)
            if (res != null) {
                location = "{{ route('user.profile') }}"
            }
        })
        let filesStore = []
        function handleChange(e) {
            // console.log(e.target.files)
            // если не выбрали файл и нажали отмену, то ничего не делать
            if (!e.target.files.length) {
                return;
            }

            let oldLength = filesStore.length;

            [...e.target.files].forEach(elem => {
                filesStore.push(elem);
            })

            let newLength = filesStore.length

            let amountLoaded = newLength - oldLength

            // if (filesStore.length > 4){
            //     filesStore.splice(4, filesStore.length-4);
            //     console.log(filesStore)
            //     alert('Нельзя загружать больше 4 изображений!');
            // }
            let imagesAmount = document.querySelectorAll('.screenItem').length;
            let imagesInOldCont = contScreens.querySelectorAll('.screenItem').length;
            console.log(imagesAmount)
            console.log(imagesInOldCont)

            if (imagesAmount >= 4 || amountLoaded > 4-imagesAmount) {
                alert('Изображений должно быть меньше 4!');
                console.log(filesStore)
                filesStore.splice(4 - imagesInOldCont,
                    imagesAmount+amountLoaded - 4);
                console.log(filesStore)
            }

            contNewScreens.textContent = ''


            filesStore.forEach((elem, key) => {
                contNewScreens.insertAdjacentHTML('beforeend', `
                <div class="screenItem">
                    <img src="${URL.createObjectURL(elem)}" alt="" class="screens"> <span class="screenDel" data-index="${key}" onclick="removeFile(event)">&times</span>
                </div>
                `)
            })
            // очищаем input, т.к. файл мы сохранили
            e.target.value = '';

        }
        // удалить файл из хранилища, например по индексу в массиве
        function removeFile(e) {
            console.log(filesStore)
            console.log(e.target.dataset.index)
            // удаляем файл по индексу
            filesStore.splice(e.target.dataset.index, 1);
            contNewScreens.textContent = ''
            console.log(filesStore.length)
            filesStore.forEach((elem, key) => {
                contNewScreens.insertAdjacentHTML('beforeend', `
                <div class="screenItem">
                    <img src="${URL.createObjectURL(elem)}" alt="" class="screens"> <span class="screenDel" data-index="${key}" onclick="removeFile(event)">&times</span>
                </div>
                `)
            })
        }

        async function deleteImg(e) {
            let res = await postDataJSON("{{route('games.imgDel')}}", e.target.dataset.target, "{{csrf_token()}}")
            e.target.parentNode.remove()
            console.log(res)
        }
    </script>
    <script>
        selectInput.addEventListener('click', () => {
            selectChoices.hidden = false
        })

        let array = new Map();
        let selected = [];
        document.querySelectorAll('.selected-choice').forEach(elem => {
            document.getElementById(`${elem.dataset.id}`).hidden = true
            selected.push(elem.dataset.id)
            console.log(elem.dataset.id)
        })
        document.querySelectorAll('.del-btn-fetch').forEach(el => {
            el.addEventListener('click', async e => {
                await postDataJSON("{{route('games.tagDel')}}", {'tag_id':e.target.dataset.target, 'game_id': e.target.dataset.game}, "{{csrf_token()}}")
                document.getElementById(`${e.target.dataset.target}`).hidden = false
                document.querySelector(`div[data-id="${e.target.dataset.target}"]`).remove()
                document.querySelector(`select#tags option[value="${e.target.dataset.target}"]`).remove()
            })
        })
        console.log(selected)
        document.querySelectorAll('.choice-item').forEach(elem => {
            array.set(elem.id, elem.textContent)
            elem.addEventListener('click', e => {
                selectInput.insertAdjacentHTML('afterbegin', `
                <div class="selected-choice" data-id="${e.target.id}">${elem.textContent} <span class="del-btn" data-relate="${e.target.id}">&times</span></div>
                `)

                tags.insertAdjacentHTML('afterbegin', `
                <option selected value="${e.target.id}">${elem.textContent}</option>
                `)
                elem.hidden = true
                document.querySelectorAll('.del-btn').forEach(el => {
                    el.addEventListener('click', e => {
                        document.getElementById(`${e.target.dataset.relate}`).hidden = false
                        document.querySelector(`div[data-id="${e.target.dataset.relate}"]`).remove()
                        document.querySelector(`select#tags option[value="${e.target.dataset.relate}"]`).remove()
                    })
                })
            })
        })
        console.log(array)


        window.addEventListener('click', e => {
            const target = e.target
            if (!target.closest('#selectInput') && !target.closest('#selectChoices')) {
                selectChoices.hidden = true
            }
        })

        function renderList(list, value) {
            console.log([...list])
            let result = [...list].filter(i => i[1].startsWith(value))
            console.log(result.map(el => el[0]))

            document.querySelectorAll('.choice-item').forEach(elem => {
                //ПРИДУМАТЬ КАК ПРОВЕРИТЬ СОВПАДЕНИЕ АЙДИ С result.map(el=>el[0]). ИНАЧЕ ДЕЛАЕТ СТАТУС HIDDEN НА ПРЕДЫДУЩИЙ ЭЛЕМЕНТ
                elem.hidden = true
                result.forEach(item => {
                    if (item[0] == elem.id && !selected.includes(elem.id)) {
                        elem.hidden = false
                        console.log(item[0], elem.id)
                    }
                })
            })
        }

        document.getElementById('search-tag').addEventListener('input', e => renderList(array, e.target.value))

    </script>
@endpush
