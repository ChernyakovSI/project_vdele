let paramPeriodFrom = Number(document.getElementById('paramPeriodFrom').innerText);
let paramPeriodTo = document.getElementById('paramPeriodTo').innerText;

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');
let valueUser = document.getElementById('valueUser');
let valueStatus = document.getElementById('valueStatus');
//++ 1-2-3-007 05/08/2022
let valueURL = document.getElementById('valueURL');
//-- 1-2-3-007 05/08/2022

let list_user = document.getElementById('list_user');
let list_status = document.getElementById('list_status');
//++ 1-2-3-007 05/08/2022
let list_URL = document.getElementById('list_URL');
//-- 1-2-3-007 05/08/2022

let btnClearUser = document.getElementById('ClearUser');
let btnClearStatus = document.getElementById('ClearStatus');
//++ 1-2-3-007 05/08/2022
let btnClearURL = document.getElementById('ClearURL');
//-- 1-2-3-007 05/08/2022

let dListLogs = document.getElementById('list-logs');

let thisData = {
    'dateFrom' : 0,
    'dateTo' : 0,
    //++ 1-2-3-007 05/08/2022
    //*-
    //'user' : 0,
    //*+
    'user' : -1,
    'URL' : '',
    //-- 1-2-3-007 05/08/2022
    'status' : ''
};

$(document).ready( function() {
    let curDate = new Date(addMiliseconds(paramPeriodFrom));
    valuePeriodFrom.value = curDate.toISOString().substring(0, 10);
    let time = cutMiliseconds(curDate.getTime());
    thisData['dateFrom'] = time;

    curDate = new Date(addMiliseconds(paramPeriodTo));
    valuePeriodTo.value = curDate.toISOString().substring(0, 10);
    time = cutMiliseconds(curDate.getTime());
    thisData['dateTo'] = time;

    resize();
});

//events

valuePeriodFrom.onchange = function(event){
    let curDate = new Date(this.value);
    curDate = beginDay(curDate);
    let time = cutMiliseconds(curDate.getTime());
    thisData['dateFrom'] = time;

    runAjax('/logs', thisData);
};

valuePeriodTo.onchange = function(event){
    let curDate = new Date(this.value);
    curDate = endDay(curDate);
    let time = cutMiliseconds(curDate.getTime());
    thisData['dateTo'] = time;

    runAjax('/logs', thisData);
};

valueUser.onchange = function(event){
    let curUser = this.value.trim();
    //++ 1-2-3-007 05/08/2022
    //*-
    //let idUser = 0;
    //*+
    let idUser = -1;
    //-- 1-2-3-007 05/08/2022

    let children = list_user.childNodes;
    for(child in children){
        //++ 1-2-3-007 05/08/2022
        //*-
        //if(children[child].nodeName === 'OPTION' && children[child].innerText === curUser) {
        //*+
        if(children[child].nodeName === 'OPTION' && children[child].innerText.trim() === curUser) {
        //-- 1-2-3-007 05/08/2022
            idUser = children[child].getAttribute('data-id');
            thisData['user'] = idUser;
            break;
        }
    }

    //++ 1-2-3-007 05/08/2022
    //*-
    //if (idUser === 0) {
    //*+
    if (idUser === -1) {
    //-- 1-2-3-007 05/08/2022
        this.value = '';
    }

    //++ 1-2-3-007 05/08/2022
    if (idUser == 0) {
        this.value = '< не авторизован >';
    }
    //-- 1-2-3-007 05/08/2022

    runAjax('/logs', thisData);
};

btnClearUser.onclick = function(e) {
    valueUser.value = '';

    //++ 1-2-3-007 05/08/2022
    //*-
    //thisData['user'] = 0;
    //*+
    thisData['user'] = -1;
    //-- 1-2-3-007 05/08/2022

    runAjax('/logs', thisData);
};

valueStatus.onchange = function(event){
    let curStatus = this.value.trim();
    let idStatus = '';

    let children = list_status.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText === curStatus) {
            idStatus = children[child].getAttribute('data-id');
            thisData['status'] = idStatus;
            break;
        }
    }

    if (idStatus === '') {
        this.value = '';
    }

    runAjax('/logs', thisData);
};

btnClearStatus.onclick = function(e) {
    valueStatus.value = '';

    thisData['status'] = '';

    runAjax('/logs', thisData);
};

//++ 1-2-3-007 05/08/2022
valueURL.onchange = function(event){
    let curURL = this.value.trim();
    let idURL = '';

    let children = list_URL.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText.trim() === curURL) {
            idURL = children[child].getAttribute('data-id');
            thisData['URL'] = idURL;
            break;
        }
    }

    if (idURL === '') {
        this.value = '';
    }

    runAjax('/logs', thisData);
};

btnClearURL.onclick = function(e) {
    valueURL.value = '';

    thisData['URL'] = '';

    runAjax('/logs', thisData);
};
//-- 1-2-3-007 05/08/2022

//Helpers

//++ 1-2-4-002 10/10/2022
//*-
//function render(dataSet) {
//*+
function render(dataSet, Parameters) {
//-- 1-2-4-002 10/10/2022

    dListLogs.innerHTML = '';

    if(dataSet.data.length > 0){
        dataSet.data.forEach((curData) => {
            let divRow = createRow(curData, dataSet.pathNotes, dataSet.dates[curData['id']]);

            dListLogs.append(divRow);

        });
    }
    else {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font-5 text-center margin-v20';
        divInfo.innerText = 'Нет данных';
        dListLogs.append(divInfo);
    }

    resize();
}

function createRow(curData, pathNotes, date) {
    let divRow = document.createElement('div');
    divRow.className = 'fin-acc-row interactive-only';
    divRow.id = curData['id'];

    let aDream = document.createElement('a');
    aDream.setAttribute('href', pathNotes + curData['id']);

    let divWrap = document.createElement('div');
    divWrap.className = 'column-10 border-1px-bottom col-back-nul colNameDate';
    let divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    let divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerHTML = date;
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divWrap = document.createElement('div');
    divWrap.className = 'column-20 border-1px-bottom col-back-nul colNameUser';
    divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerText = getFIO(curData);
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divWrap = document.createElement('div');
    divWrap.className = 'column-20 border-1px-bottom col-back-nul colNameURL';
    divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerText = curData['url'];
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divWrap = document.createElement('div');
    divWrap.className = 'column-20 border-1px-bottom col-back-nul colNameStatus';
    divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerText = curData['status'];
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divWrap = document.createElement('div');
    divWrap.className = 'column-30 border-1px-all col-back-nul colNameDescription';
    divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerText = curData['description'];
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divRow.append(aDream);

    return divRow;
}

function getFIO(curData) {
    let FIO = '';

    if (curData['surname'] !== '' || curData['name'] !== '' || curData['middlename'] !== '') {
        FIO = curData['surname'].trim();
        FIO = (FIO + ' ' + curData['name']).trim();
        FIO = (FIO + ' ' + curData['middlename']).trim();
    } else {
        if (curData['email'] !== '') {
            FIO = curData['email'].trim();
        } else {
            FIO = curData['username'].trim();
        }
    }

    if (curData['id_user'] !== '0') {
        FIO = (FIO + ' (' + curData['id_user'] + ')').trim();
    }

    return FIO;
}

function resize(mode = 0) {
    let children = dListLogs.childNodes;
    resizeTable(children, 0);
}

function resizeTable(children, mode = 0) {
    if (children.length > 0) {
        let divRow;

        let colADate, colUser, colURL, colStatus, colDescr, maxHeight;

        for(child in children){
            maxHeight = 0;
            divRow = children[child].childNodes;

            if (divRow == undefined || divRow.length == 0) {
                continue
            } else {
                if(mode == 0) {
                    for(column in divRow){
                        if(divRow[column].nodeName == 'A') {
                            divRow = divRow[column].childNodes;
                            break;
                        }
                    }
                }
            }

            if (mode == 0 && (divRow == undefined || divRow.length == 0)) {
                continue
            }

            for(column in divRow){
                if (divRow.length > 0){
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDate') > -1) {
                        colADate = divRow[column];
                        if(maxHeight < colADate.clientHeight) {
                            maxHeight = colADate.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameUser') > -1) {
                        colUser = divRow[column];
                        if(maxHeight < colUser.clientHeight) {
                            maxHeight = colUser.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameURL') > -1) {
                        colURL = divRow[column];
                        if(maxHeight < colURL.clientHeight) {
                            maxHeight = colURL.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameStatus') > -1) {
                        colStatus = divRow[column];
                        if(maxHeight < colStatus.clientHeight) {
                            maxHeight = colStatus.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDescription') > -1) {
                        colDescr = divRow[column];
                        if(maxHeight < colDescr.clientHeight) {
                            maxHeight = colDescr.clientHeight;
                        }
                    }
                }
            }
            if(colADate != undefined && colADate.clientHeight != undefined < maxHeight) {
                colADate.style.height = maxHeight + "px";
            }
            if(colUser != undefined && colUser.clientHeight != undefined < maxHeight) {
                colUser.style.height = maxHeight + "px";
            }
            if(colURL != undefined && colURL.clientHeight != undefined < maxHeight) {
                colURL.style.height = maxHeight + "px";
            }
            if(colStatus != undefined && colStatus.clientHeight != undefined < maxHeight) {
                colStatus.style.height = maxHeight + "px";
            }
            if(colDescr != undefined && colDescr.clientHeight != undefined < maxHeight) {
                colDescr.style.height = maxHeight + "px";
            }

            colDescr.children[0].children[0].innerHTML = getNewLinesToBr_Text(colDescr.children[0].children[0].innerHTML);

            colADate = undefined;
            colUser = undefined;
            colURL = undefined;
            colStatus = undefined;
            colDescr = undefined;

        }
    }
}