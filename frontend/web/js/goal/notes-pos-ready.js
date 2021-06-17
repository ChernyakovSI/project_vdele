let btnCancel = document.getElementById('button-cancel');
let btnRemove = document.getElementById('button-remove');
let panelColored = document.getElementById('panel-colored');
let fotoItems = document.getElementsByClassName('foto-item');

let clickTime;
let modeColored = 0;
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
                    children[child].setAttribute('data-href', children[child].getAttribute('href'));
                    children[child].setAttribute('href', '#');
            }
        }
    });
}

btnCancel.onclick = function(e) {
    modeColored = 0;
    panelColored.hidden = true;
    returnLightBox();
};

function returnLightBox() {
    arrFotos = Array.from(fotoItems);
    arrFotos.forEach(function(item, i, arr) {
        let children = item.childNodes;

        for(child in children){
            if(children[child].nodeName === 'A') {
                if (children[child].hasAttribute('data-href') === true) {
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

btnRemove.onclick = function(e) {
    arrSrc = [];
    arrDeleting.forEach(function(item, i, arr) {
        let children = item.childNodes;

        for(child in children){
            if(children[child].nodeName === 'A') {
                if (children[child].hasAttribute('data-href') === true) {
                    arrSrc.push(children[child].getAttribute('data-href').replace('note/', ''));
                }
            }
        }
    });

    if(arrSrc.length > 0) {
        value = {
            'sources' : arrSrc,
        };

        runAjax('/goal/note-delete', value);
    }

    modeColored = 0;
    panelColored.hidden = true;
    returnLightBox();
};

function runAjax(url, value, typeReq = 'post'){
    floatingCirclesGMain.hidden = false;

    $.ajax({
        type : typeReq,
        url : url,
        data : value, // array
    }).done(function(data) {

        console.dir(data);
        if (data.error === null || data.error === undefined || data.error === '') {
            rerender(data);
            floatingCirclesGMain.hidden = true;


        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){
                //showError(data);
                floatingCirclesGMain.hidden = true;
            }
        }

    }).fail(function() {

    });
}

function rerender(dataSet) {
    let listFoto = document.getElementById('list-fotos');
    let children = listFoto.childNodes;

    arrElemsForDel = [];
    for(child in children){
        if((children[child].nodeName === 'DIV') & ((' ' + children[child].className + ' ').indexOf('add-img') === -1)) {
            arrElemsForDel.push(children[child]);
        }
    }
    arrElemsForDel.forEach(function(data, i, arrData){
        data.remove();
    });

    if(dataSet.allNotes.length > 0){

        dataSet.allNotes.forEach((data) => {
            let divElem = document.createElement('div');
            divElem.className = 'window window-border flex-item foto-item '+ dataSet.colorStyle[data['id_sphere']];

            let aFoto = document.createElement('a');
            aFoto.setAttribute('href', dataSet.pathNotes + data['num']);

            let divWrap = document.createElement('div');
            divWrap.className = 'h-150px w-180px';
            let divWrap2 = document.createElement('div');
            divWrap2.className = 'subwindow hm-80 w-100 content-hide';
            let divWrap3 = document.createElement('div');
            divWrap3.className = 'like-table h-100 w-100';

            let pTitle = document.createElement('p');
            pTitle.className = 'text-center like-cell text-s-16px';
            pTitle.innerText = data['title'];

            let divWrap4 = document.createElement('div');
            divWrap4.className = 'subwindow h-20 like-table w-100 content-hide';

            let pDate = document.createElement('p');
            pDate.className = 'text-right like-cell text-s-20px';
            pDate.innerText = dataSet.dates[data['id']];

            divWrap3.append(pTitle);
            divWrap2.append(divWrap3);
            divWrap.append(divWrap2);

            divWrap4.append(pDate);
            divWrap.append(divWrap4);

            aFoto.append(divWrap);
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
        divInfo.innerHTML = 'Нет заметок';

        listFoto.append(divInfo);
    }
}