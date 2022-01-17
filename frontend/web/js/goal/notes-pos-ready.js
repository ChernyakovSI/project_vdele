let btnCancel = document.getElementById('button-cancel');
let btnRemove = document.getElementById('button-remove');
let panelColored = document.getElementById('panel-colored');
let fotoItems = document.getElementsByClassName('foto-item');

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');

let valueSphere = document.getElementById('selValueSphere');
let list_sphere = document.getElementById('list_sphere_sel');
let btnClearSphere = document.getElementById('ClearSphere');

let clickTime;
let modeColored = 0;
let arrDeleting = [];

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;

let thisData = {
    'dateFrom' : 0,
    'dateTo' : 0,
    'id_sphere' : 0,
};

$(document).ready( function() {
    panelColored.hidden = true;

    let curDate = new Date();
    curDate.setDate(curDate.getDate() - 31);
    valuePeriodFrom.value = curDate.toISOString().substring(0, 10);
    thisData['dateFrom'] = String(curDate.getTime()).substr(0, 10);

    curDate = new Date();
    curDate.setDate(curDate.getDate() + 31);
    valuePeriodTo.value = curDate.toISOString().substring(0, 10);
    thisData['dateTo'] = String(curDate.getTime()).substr(0, 10);

    AddEventsToNotes();
});

//------------------------------------------------------------------------------Events

valuePeriodFrom.onchange = function(e) {
    RenewPeriod();
};

valuePeriodTo.onchange = function(e) {
    RenewPeriod();
};

valueSphere.onchange = function(event){
    let curSphere = this.value.trim();
    let idSphere = 0;

    let children = list_sphere.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText === curSphere) {
            idSphere = children[child].getAttribute('data-id');
            thisData['id_sphere'] = idSphere;
            break;
        }
    }

    if (idSphere === 0) {
        this.value = '';
    }

    RenewPeriod();

};

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;
};

//------------------------------------------------------------------------------Helpers

function RenewPeriod() {
    let curDate = new Date(valuePeriodFrom.value);
    curDate.setHours(curDate.getHours() + currentTimeZoneOffset);
    thisData['dateFrom'] = String(curDate.getTime()).substr(0, 10);

    curDate = new Date(valuePeriodTo.value);
    curDate.setHours(curDate.getHours() + currentTimeZoneOffset);
    thisData['dateTo'] = String(curDate.getTime()).substr(0, 10);

    runAjax('/goal/get-notes-for-period', thisData);
}

function AddEventsToNotes() {
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
}

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
            'dateFrom' : thisData['dateFrom'],
            'dateTo' : thisData['dateTo'],
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
            pDate.className = 'text-right like-cell text-s-20px '+dataSet.datesColor[data['id']];
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