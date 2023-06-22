@extends('templates.custom')
@section('title', 'Добавить игру')
@section('content')
    <main class="media-reg">
        @include('inc.message')
        <h1>Добавить новую новеллу</h1>
        <form method="post" enctype="multipart/form-data" id="form">
            @csrf
            <div class="form-group">
                <label for="name"> Название <span class="required">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name')??'' }}">
            </div>

            <div class="form-group">
                <label for="description"> Краткое описание <span class="required">*</span></label>
                <textarea name="description" id="description" rows="5" cols="15">{{ old('description')??'' }}</textarea>
            </div>

            <div class="form-group">
                <label for="file_link">Ссылка на файлобменник</label>
                <input type="text" name="file_link" id="file_link"  value="{{ old('file_link')??'' }}">
            </div>


            <div class="form-group">
                <label for="language">Язык</label>
                <input type="text" name="language" id="language"  value="{{ old('language')??'' }}">
            </div>

            <div class="form-group">
                <label for="tags">Метки</label>

                <div id="select-tags">
                    <div class="select-input" id="selectInput">
                        <select name="tags[]" id="tags" hidden="true" style="display: none" multiple></select>
                        <div id="selected-options"></div>
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
                        <input type="radio" name="access_id" class="access" value="1">
                        Публичный
                    </label>
                    <label>
                        <input type="radio" name="access_id" class="access" value="2">
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
                <div id="file-cont"></div>
            </div>
            <div class="form-group" id="cover-load">
                <label>Скриншоты</label>
                <label for="screenshots" id="screen-label">
                    <div class="label-desc">Нажмите, чтобы загрузить скриншоты</div>

                    <input type="file" name="screens[]" id="screenshots" hidden="true" multiple onchange="handleChange(event)">

                </label>
                <div id="screens-cont"></div>
            </div>

            <div class="form-group">
                <label for=""></label>
                <button name="submit" class="btn confirm" id="submit">Добавить</button>
            </div>

        </form>
    </main>
@endsection

@push('script')
    <script src="{{ asset('js/fetch.js') }}"></script>
    <script>
        loadFileAdd = document.querySelector('#cover')
        contCover = document.querySelector('#file-cont')

        loadFilesAdd = document.querySelector('#screenshots')
        contScreens = document.querySelector('#screens-cont')

        loadFileAdd.addEventListener('change', e=>{
            contCover.innerHTML = ''
            let image = document.createElement('img')
            image.style.display = 'block'
            image.style.width='150px'
            image.style.height = '200px'
            image.style.objectFit = 'cover'
            image.src=URL.createObjectURL(e.target.files[0])
            image.alt="img"
            contCover.append(image)
        })

        submit.addEventListener('click', async e=>{
            e.preventDefault()
            const formData =  new FormData(form);

            if (filesStore!=[]){
                filesStore.map((file, index) => {
                    formData.append(`screens[]`, file);
                });
            }
                for (let pair of formData.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

            // for (var pair of formData.entries()) {
            //     console.log(pair[0]+ ', ' + pair[1]);
            // }
            let res = await postJSON('{{ route('games.store') }}', formData, '{{ csrf_token() }}');
            console.log(res)
            if (res != null){
                location = "{{ route('user.profile') }}"
            }
        })



        let filesStore = []
             // какое-то хранищие файлов, для примера так

        // при выборе файлов, мы будем их добавлять
        function handleChange(e) {
            // console.log(e.target.files)
            // если не выбрали файл и нажали отмену, то ничего не делать
            if (!e.target.files.length) {
                return;
            }


            [...e.target.files].forEach(elem=>{
                filesStore.push(elem);
            })

            if (filesStore.length > 4){
                filesStore.splice(4, filesStore.length-4);
                console.log(filesStore)
                alert('Нельзя загружать больше 4 изображений!');
            }

            contScreens.textContent = ''

            filesStore.forEach((elem, key)=>{
                contScreens.insertAdjacentHTML('beforeend', `
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
            // удаляем файл по индексу
            filesStore.splice(e.target.dataset.index, 1);
            contScreens.textContent = ''
            filesStore.forEach((elem, key)=>{
                contScreens.insertAdjacentHTML('beforeend', `
                <div class="screenItem">
                    <img src="${URL.createObjectURL(elem)}" alt="" class="screens"> <span class="screenDel" data-index="${key}" onclick="removeFile(event)">&times</span>
                </div>
                `)
            })
        }

        // если надо послать файлы на сервер, формируем FormData с файлами
        // const formData = getFilesFormData(filesStore);
        //
        // function getFilesFormData(files) {
        //     const formData = new FormData(form);
        //
        //     files.map((file, index) => {
        //         formData.append(`files`, file);
        //     });
        //     console.log(formData.getAll('files'))
        //     return formData;
        // }


        // try {
        //     const response = await fetch('https://example.com/posts', {
        //         method: 'POST',
        //         body: formData
        //     });
        //     const result = await response.json();
        //     console.log('Успех:', JSON.stringify(result));
        // } catch (error) {
        //     console.error('Ошибка:', error);
        // }

    </script>
    <script>
        selectInput.addEventListener('click', ()=>{
            selectChoices.hidden = false
        })

        let array = new Map();
        document.querySelectorAll('.choice-item').forEach(elem=>{
            array.set(elem.id, elem.textContent)
            elem.addEventListener('click', e=>{
                selectInput.insertAdjacentHTML('afterbegin', `
                <div class="selected-choice" data-id="${e.target.id}">${elem.textContent} <span class="del-btn" data-relate="${e.target.id}">&times</span></div>
                `)

                tags.insertAdjacentHTML('afterbegin', `
                <option selected value="${e.target.id}">${elem.textContent}</option>
                `)
                elem.hidden = true
                document.querySelectorAll('.del-btn').forEach(el=>{
                    el.addEventListener('click', e=>{
                        document.getElementById(`${e.target.dataset.relate}`).hidden = false
                        document.querySelector(`div[data-id="${e.target.dataset.relate}"]`).remove()
                        document.querySelector(`select#tags option[value="${e.target.dataset.relate}"]`).remove()
                    })
                })
            })
        })
        console.log(array)



        window.addEventListener('click', e=>{
            const target = e.target
            if(!target.closest('#selectInput') && !target.closest('#selectChoices')){
                selectChoices.hidden = true
            }
        })

        function renderList(list, value){
            console.log([...list])
            let result = [...list].filter(i=>i[1].startsWith(value))
            console.log(result.map(el=>el[0]))

            document.querySelectorAll('.choice-item').forEach(elem=>{
                //ПРИДУМАТЬ КАК ПРОВЕРИТЬ СОВПАДЕНИЕ АЙДИ С result.map(el=>el[0]). ИНАЧЕ ДЕЛАЕТ СТАТУС HIDDEN НА ПРЕДЫДУЩИЙ ЭЛЕМЕНТ
                elem.hidden = true
                result.forEach(item=>{
                    if(item[0]==elem.id){
                        elem.hidden = false
                        console.log(item[0], elem.id)
                    }
                })
            })
        }

        document.getElementById('search-tag').addEventListener('input',e=>renderList(array, e.target.value))

    </script>
@endpush
