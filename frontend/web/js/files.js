
let preview = document.querySelector('#preview');

let buttonAdd = document.getElementById('button-add');
let buttonDel = document.getElementById('button-del');

let floatingCirclesG = document.getElementById('floatingCirclesG');

let allFiles = [];

let elemFiles;

function handleFiles(files, elem) {
    let loadedPics = 0;
    elemFiles = elem;

    allFiles = Array.from(files);

    for (let i = 0; i < files.length; i++) {
        let file = files[i];

        if (!file.type.startsWith('image/')){ continue }

        let img = document.createElement("img");
        img.classList.add("img-wrap");
        img.file = file;
        img.setAttribute('alt', file.name);


        let reader = new FileReader();
        reader.onload = (function(aImg) {
            return function(e) {
                aImg.src = e.target.result;
            };
        })(img);
        reader.readAsDataURL(file);

        let divItem = document.createElement('div');
        divItem.className = 'window window-border flex-item';

        let divRemove = document.createElement('div');
        divRemove.className = 'preview-remove';
        divRemove.innerHTML = '&times;';
        divRemove.setAttribute('data-name', file.name);

        let spanName = document.createElement('span');
        spanName.innerHTML = file.name;
        let spanSize = document.createElement('span');
        spanSize.innerHTML = bytesToSize(file.size);

        let divInfo = document.createElement('div');
        divInfo.className = 'preview-info';
        divInfo.append(spanName);
        divInfo.append(spanSize);

        divItem.append(img);
        divItem.append(divRemove);
        divItem.append(divInfo);
        preview.appendChild(divItem); // Предполагается, что "preview" это div, в котором будет отображаться содержимое.

        loadedPics = loadedPics + 1;
    }

    const removeHandler = event => {
        if(!event.target.dataset.name) {
            return;
        }

        const {name} = event.target.dataset;

        allFiles = allFiles.filter(file => file.name !== name);

        const block = preview.querySelector(`[data-name="${name}"]`).closest('.flex-item');
        block.classList.add('removing');
        setTimeout(() => block.remove(), 300);
        //block.remove();
    };

    preview.addEventListener('click', removeHandler);

    if(loadedPics > 0) {
        showFormNew(function(value) {
            if (value !== null) {
                runAjaxFoto(urlFotoLoad, value)
            }
        });
    }

}

function showFormNew(callback) {
    showCover();
    let form = document.getElementById('prompt-form');

    form.classList.add('form-on');

    let container = document.getElementById('prompt-form-container');
    let btnClose = document.getElementById('btnClose');

    buttonAdd.hidden = false;
    floatingCirclesG.hidden = true;

    //++ 1-2-4-001 31/08/2022
    //*-
    //thisData = {
    //*+
    thisDataFiles = {
    //-- 1-2-4-001 31/08/2022
        'files' : allFiles,
    };


    buttonAdd.onclick = function(e) {
        this.hidden = true;
        initBtnConfirm();
    };

    function initBtnConfirm() {
        //++ 1-2-4-001 31/08/2022
        //*-
        //thisData.files = allFiles;
        //complete(thisData);
        //*+
        thisDataFiles.files = allFiles;
        complete(thisDataFiles);
        //-- 1-2-4-001 31/08/2022
    }

    function complete(value) {
        callback(value);
    }

    document.onkeydown = function(e) {
        if (e.key == 'Escape') {
            complete(null);
            deleteForm();
        }
    };

    btnClose.onclick = function(e) {
        closeFrom();
    };

    buttonDel.onclick = function(e) {
        closeFrom();
    };

    container.ondblclick = function(e) {
        if (event.defaultPrevented) return;

        closeFrom();
    };

    form.ondblclick = function(e) {
        e.preventDefault();
        return false;
    };

    function closeFrom() {
        complete(null);
        deleteForm();
    }

    //container.style.display = 'block';
}

function showCover() {
    let coverDiv = document.createElement('div');
    coverDiv.id = 'cover-div';

    coverDiv.classList.remove('form-on');

    let container = document.getElementById('prompt-form-container');
    container.style.display = 'block';

    // убираем возможность прокрутки страницы во время показа модального окна с формой
    document.body.style.overflowY = 'hidden';

    document.body.append(coverDiv);
}

function hideCover() {
    let coverDiv = document.getElementById('cover-div')

    preview.innerHTML = '';

    coverDiv.remove();
    document.body.style.overflowY = '';
}

function deleteForm(){
    let container = document.getElementById('prompt-form-container');

    hideCover();
    container.style.display = 'none';
    document.onkeydown = null;
}

function runAjaxFoto(url, value, mode = 1, typeReq = 'post'){
    floatingCirclesG.hidden = false;

    if(mode === 1) {
        id_elem = elemFiles.id;
        if (id_elem.includes('fotos1_') === true) {
            num = id_elem.replace('fotos1_', '');
            num = Number(num);
            idForm = 'form1_'+num;
        }
        else if (id_elem.includes('fotos2_') === true) {
            num = id_elem.replace('fotos2_', '');
            num = Number(num);
            idForm = 'form2_'+num;
        } else {
            idForm = 'form';
        }

        form = document.getElementById(idForm);
        form.files = value.files;

        let formData = new FormData();
        formData.append('section', 'general');
        formData.append('action', 'previewImg');
        qLoaded = 0;

        for (let i = 0; i < value.files.length; i++) {
            formData.append('file', value.files[i]);
            //formData.append('extension', value.files[i].extension);

            $.ajax({
                type : typeReq,
                url : url,
                data : formData, // form
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                //console.dir(data);
                qLoaded = qLoaded + 1;

                if (data.error === null || data.error === undefined) {
                    if(mode === 1){

                    }

                } else {
                    if (data.error !== '' || data.error !== null || data.error !== undefined){
                        if(mode === 1){
                            //showError(data);
                        }
                    }
                }

                if (qLoaded === value.files.length){
                    data['element'] = elemFiles;
                    afterLoadingFotos(data);
                    floatingCirclesG.hidden = true;

                    //console.log('Загружено');
                }

            }).fail(function() {
                qLoaded = qLoaded + 1;

                if (qLoaded === value.files.length){
                    data['element'] = elemFiles;
                    afterLoadingFotos();
                    floatingCirclesG.hidden = true;
                    //console.log('Загружено');
                }
            });
        }
    }
    else {
        $.ajax({
            type : typeReq,
            url : url,
            data : value, // array
        }).done(function(data) {

            console.dir(data);
            if (data.error === null || data.error === undefined || data.error === '') {
                if(mode === 2){
                    rerender(data);
                    floatingCirclesG.hidden = true;
                }

            } else {
                if (data.error !== '' || data.error !== null || data.error !== undefined){
                    if(mode === 2){
                        //showError(data);
                        floatingCirclesG.hidden = true;
                    }
                }
            }

        }).fail(function() {

        });
    }

}

function afterLoadingFotos(data = undefined) {
    deleteForm();
    if(data !== undefined) {
        rerender(data);
    }
}

function bytesToSize(bytes) {
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0)
        return '0 Byte';
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));

    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}