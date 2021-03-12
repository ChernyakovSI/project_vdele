
let preview = document.querySelector('#preview');

let buttonAdd = document.getElementById('button-add');
let buttonDel = document.getElementById('button-del');

let fotos = document.getElementById('fotos');

let floatingCirclesG = document.getElementById('floatingCirclesG');

let allFiles = [];

let clickTime;
let modeColored = 0;

let fotoItems = document.getElementsByClassName('foto-item');
let panelColored = document.getElementById('panel-colored');
let btnRemoveFotos = document.getElementById('button-remove');
let btnCancel = document.getElementById('button-cancel');

let arrDeleting = [];

$(document).ready( function() {
    panelColored.hidden = true;

    arrFotos = Array.from(fotoItems);
    arrFotos.forEach(function(item, i, arr) {
        item.onmousedown = function(e) {
            if(modeColored === 0) {
                clickTime = setTimeout(function(){
                    deleteLightBox();
                    modeColored = 1;
                    panelColored.hidden = false;
                    item.classList.add('window-colored');

                    arrDeleting.push(item);
                }, 1000);
            }

            if (modeColored === 1) {

                if(item.classList.contains('window-colored')) {
                    item.classList.remove('window-colored');
                    arrDeleting = arrDeleting.filter(function(elem) {
                        return elem !== item
                    })
                }
                else {
                    item.classList.add('window-colored');
                    arrDeleting.push(item);
                }
            }

        };

        item.onmouseup = function(e) {
            clearTimeout(clickTime);
        };
    })

});

function deleteLightBox() {
    arrFotos = Array.from(fotoItems);
    arrFotos.forEach(function(item, i, arr) {
        let children = item.childNodes;

        for(child in children){
            if(children[child].nodeName === 'A') {
                if(children[child].hasAttribute('data-lightbox')) {
                    children[child].removeAttribute('data-lightbox');
                    children[child].setAttribute('data-href', children[child].getAttribute('href'));
                    children[child].setAttribute('href', '#');
                }
            }
        }
    });
}

function returnLightBox() {
    arrFotos = Array.from(fotoItems);
    arrFotos.forEach(function(item, i, arr) {
        let children = item.childNodes;

        for(child in children){
            if(children[child].nodeName === 'A') {
                if (children[child].hasAttribute('data-lightbox') === false) {
                    children[child].setAttribute('data-lightbox', 'roadtrip');
                    children[child].setAttribute('href',  children[child].getAttribute('data-href'));
                    children[child].removeAttribute('data-href');
                }
            }
        }

        if(item.classList.contains('window-colored')) {
            item.classList.remove('window-colored');
        }
    });
}

btnCancel.onclick = function(e) {
    modeColored = 0;
    panelColored.hidden = true;
    returnLightBox();
};

btnRemoveFotos.onclick = function(e) {
    arrSrc = [];
    arrDeleting.forEach(function(item, i, arr) {
        let children = item.childNodes;

        for(child in children){
            if(children[child].nodeName === 'A') {
                if (children[child].hasAttribute('data-href') === true) {
                    arrSrc.push(children[child].getAttribute('data-href'));
                }
            }
        }
    });

    if(arrSrc.length > 0) {
        value = {
            'sources' : arrSrc,
        };

        runAjax('/foto-delete', value, 2);
    }

    modeColored = 0;
    panelColored.hidden = true;
    returnLightBox();
};

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

    if(mode === 1) {
        form = document.getElementById('form');
        form.files = value.files;

        let formData = new FormData();
        formData.append('section', 'general');
        formData.append('action', 'previewImg');
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
        this.hidden = true;
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

    arrElemsForDel = [];
    for(child in children){
        if((children[child].nodeName === 'DIV') & ((' ' + children[child].className + ' ').indexOf('add-img') === -1)) {
            console.log('удален');
            arrElemsForDel.push(children[child]);
        }
    }
    arrElemsForDel.forEach(function(data, i, arrData){
        data.remove();
    });

    console.log(dataSet.allPaths.length);
    if(dataSet.allPaths.length > 0){
        dataSet.allPaths.forEach(function(data, i, arrData){
            let divElem = document.createElement('div');
            divElem.className = 'window window-border flex-item foto-item';

            let aFoto = document.createElement('a');
            aFoto.setAttribute('href', dataSet.pathFotos + data['src']);
            aFoto.setAttribute('data-lightbox', 'roadtrip');

            let imgFoto = document.createElement('img');
            imgFoto.className = 'img-wrap';
            imgFoto.setAttribute('src', dataSet.pathFotos + data['src']);

            aFoto.append(imgFoto);
            divElem.append(aFoto);
            listFoto.append(divElem);

            divElem.onmousedown = function(e) {
                if(modeColored === 0) {
                    clickTime = setTimeout(function(){
                        deleteLightBox();
                        modeColored = 1;
                        panelColored.hidden = false;
                        divElem.classList.add('window-colored');

                        arrDeleting.push(divElem);
                    }, 1000);
                }

                if (modeColored === 1) {

                    if(divElem.classList.contains('window-colored')) {
                        divElem.classList.remove('window-colored');
                        arrDeleting = arrDeleting.filter(function(elem) {
                            return elem !== divElem
                        })
                    }
                    else {
                        divElem.classList.add('window-colored');
                        arrDeleting.push(divElem);
                    }
                }

            };

            divElem.onmouseup = function(e) {
                clearTimeout(clickTime);
            };
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
