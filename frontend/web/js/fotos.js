
let preview = document.querySelector('#preview');

let buttonAdd = document.getElementById('button-add');
let buttonDel = document.getElementById('button-del');

let fotos = document.getElementById('fotos');

let floatingCirclesG = document.getElementById('floatingCirclesG');

let allFiles = [];

function bytesToSize(bytes) {
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0)
        return '0 Byte';
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));

    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function handleFiles(files) {
    let loadedPics = 0;

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
                runAjax('/foto-load', value)
            }
        });
    }

}

function runAjax(url, value, mode = 1, typeReq = 'post'){
    floatingCirclesG.hidden = false;

    //value = {'files' : []};
    //console.dir(value);
    form = document.getElementById('form');
    form.files = value.files;

    let formData = new FormData();
    formData.append('section', 'general');
    formData.append('action', 'previewImg');
// Attach file
    let FilesForLoad = [];
    qLoaded = 0;
    for (let i = 0; i < fotos.files.length; i++) {
        formData.append('file', fotos.files[i]);
        //formData.append('extension', fotos.files[i].extension);
        //console.log(fotos.files[i]);

        $.ajax({
            type : typeReq,
            url : url,
            data : formData, // form
            contentType: false,
            processData: false,
            cache: false,
        }).done(function(data) {
            console.dir(data);
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

            if (qLoaded === fotos.files.length){
                afterLoadingFotos(data);
                floatingCirclesG.hidden = true;

                //console.log('Загружено');
            }

        }).fail(function() {
            qLoaded = qLoaded + 1;

            if (qLoaded === fotos.files.length){
                afterLoadingFotos();
                floatingCirclesG.hidden = true;
                //console.log('Загружено');
            }
        });
    }
}

function afterLoadingFotos(data = undefined) {
    deleteForm();
    if(data !== undefined) {
        rerender(data);
    }
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

    //coverDiv.classList.remove('form-on');
    //coverDiv.classList.add('form-off');
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

function showFormNew(callback) {
    showCover();
    let form = document.getElementById('prompt-form');

    form.classList.add('form-on');

    let container = document.getElementById('prompt-form-container');
    let btnClose = document.getElementById('btnClose');

    floatingCirclesG.hidden = true;

    thisData = {
        'files' : allFiles,
    };


    buttonAdd.onclick = function(e) {
        initBtnConfirm();
    };

    function initBtnConfirm() {
        thisData.files = allFiles;
        complete(thisData);
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

function rerender(dataSet) {
    let listFoto = document.getElementById('list-fotos');
    let children = listFoto.childNodes;

    for(child in children){
        if(children[child].nodeName === 'DIV' & (' ' + children[child].className + ' ').indexOf('add-img') === -1) {
            children[child].remove();
        }
    }

    if(dataSet.allPaths.length > 0){
        dataSet.allPaths.forEach(function(data, i, arrData){
            let divElem = document.createElement('div');
            divElem.className = 'window window-border flex-item';

            let imgFoto = document.createElement('img');
            imgFoto.className = 'img-wrap';
            imgFoto.setAttribute('src', dataSet.pathFotos + data['src']);

            divElem.append(imgFoto);
            listFoto.append(divElem);
        });
    }
    else
    {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20 flex-item';
        divInfo.setAttribute('id', 'info');
        divInfo.innerHTML = 'Нет фотографий';

        listFoto.append(divInfo);
    }
}
