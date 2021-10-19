let paramPeriodFrom = Number(document.getElementById('paramPeriodFrom').innerText);
let paramPeriodTo = document.getElementById('paramPeriodTo').innerText;
let paramLevel = document.getElementById('paramLevel').innerText;

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');
let valueSphere = document.getElementById('valueSphere');
let list_sphere = document.getElementById('list_sphere');
let setDont = document.getElementById('setDont');
let setProcess = document.getElementById('setProcess');
let setDone = document.getElementById('setDone');

let dInfo1 = document.getElementById('info1');
let dInfo2 = document.getElementById('info2');
let dListDreams1 = document.getElementById('list-dreams1');
let dListDreams2 = document.getElementById('list-dreams2');

let btnClearSphere = document.getElementById('ClearSphere');
let btnNewDream = document.getElementById('new-reg');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;

let thisData = {
    'dateFrom' : 0,
    'dateTo' : 0,
    'id_sphere' : 0,
    'status_dont' : 0,
    'status_process' : 1,
    'status_done' : 0,
    'level': 1
};

$(document).ready( function() {
    let curDate = new Date(addMiliseconds(paramPeriodFrom));
    valuePeriodFrom.value = curDate.toISOString().substring(0, 10);
    let time = cutMiliseconds(curDate.getTime());
    thisData['dateFrom'] = time; //String(curDate.getTime()).substr(0, 10);

    curDate = new Date(addMiliseconds(paramPeriodTo));
    valuePeriodTo.value = curDate.toISOString().substring(0, 10);
    time = cutMiliseconds(curDate.getTime());
    thisData['dateTo'] = time; //String(curDate.getTime()).substr(0, 10);

    thisData['level'] = Number(paramLevel);

    if (thisData['status_dont'] == 1) {
        setDont.checked = true;
    } else {
        setDont.checked = false;
    }
    if (thisData['status_process'] == 1) {
        setProcess.checked = true;
    } else {
        setProcess.checked = false;
    }
    if (thisData['status_done'] == 1) {
        setDone.checked = true;
    } else {
        setDone.checked = false;
    }
});

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;

    runAjax('/goal/dreams-refresh', thisData);
};

//events

valuePeriodFrom.onchange = function(event){
    let curDate = new Date(this.value);
    curDate = beginDay(curDate);
    let time = cutMiliseconds(curDate.getTime());
    thisData['dateFrom'] = time;

    runAjax('/goal/dreams-refresh', thisData);
};

valuePeriodTo.onchange = function(event){
    let curDate = new Date(this.value);
    curDate = endDay(curDate);
    let time = cutMiliseconds(curDate.getTime());
    thisData['dateTo'] = time;

    runAjax('/goal/dreams-refresh', thisData);
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

    runAjax('/goal/dreams-refresh', thisData);
};

setDont.onchange = function(e) {
    thisData['status_dont'] = Number(this.checked);

    runAjax('/goal/dreams-refresh', thisData);
};

setProcess.onchange = function(e) {
    thisData['status_process'] = Number(this.checked);

    runAjax('/goal/dreams-refresh', thisData);
};

setDone.onchange = function(e) {
    thisData['status_done'] = Number(this.checked);

    runAjax('/goal/dreams-refresh', thisData);
};

btnNewDream.onclick = function(e) {
    if(paramLevel == 1) {
        window.location.href = '/goal/dream';
    }
    else if(paramLevel > 1) {
        window.location.href = '/goal/dream?level='+paramLevel;
    }

};
//===============

function runAjax(url, value, typeReq = 'post'){
    floatingCirclesGMain.hidden = false;

    //console.log(value);

    $.ajax({
        type : typeReq,
        url : url,
        data : value
    }).done(function(data) {
        if (data.error === null || data.error === undefined) {
            //console.log(data);

        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){
                //showError(data);
                //console.log(data);
                render(data);
            }
        }

        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        //console.log(value);
        floatingCirclesGMain.hidden = true;
    });
}

function render(dataSet) {

    dListDreams1.innerHTML = '';
    dListDreams2.innerHTML = '';

    //console.log(dataSet.data.length);

    if(dataSet.data.length > 1){
        let numTotal = dataSet.data.length;
        let num1 = Math.ceil(numTotal / 2);
        let numRow = 0;

        dataSet.data.forEach((curData) => {
            numRow = numRow + 1;
            let divRow = createRow(curData, numRow, dataSet.colorStyle[curData['id_sphere']], dataSet.pathNotes, dataSet.dates[curData['id']]);

            if(numRow <= num1) {
                dListDreams1.append(divRow);
            }
            else {
                dListDreams2.append(divRow);
            }

        });
    }
    else if(dataSet.data.length === 1) {

        let curData = dataSet.data[0];

        let numRow = 1;
        let divRow = createRow(curData, numRow, dataSet.colorStyle[curData['id_sphere']], dataSet.pathNotes, dataSet.dates[curData['id']]);
        dListDreams1.append(divRow);

        let divInfo = document.createElement('div');
        divInfo.className = 'text-font-5 text-center margin-v20';
        divInfo.innerText = 'Нет данных';
        dListDreams2.append(divInfo);
    }
    else {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font-5 text-center margin-v20';
        divInfo.innerText = 'Нет данных';
        dListDreams1.append(divInfo);

        divInfo = document.createElement('div');
        divInfo.className = 'text-font-5 text-center margin-v20';
        divInfo.innerText = 'Нет данных';
        dListDreams2.append(divInfo);
    }
}

function createRow(curData, numRow, style, pathNotes, date) {
    let divRow = document.createElement('div');
    divRow.className = 'fin-acc-row interactive-only '+ style;
    divRow.id = curData['id'];

    let aDream = document.createElement('a');
    aDream.setAttribute('href', pathNotes + curData['num']);

    let divWrap = document.createElement('div');
    divWrap.className = 'column-10 border-1px-bottom col-back-nul';
    let divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    let divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerText = numRow;
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divWrap = document.createElement('div');
    divWrap.className = 'column-25 border-1px-bottom col-back-nul';
    divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerText = date;
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divWrap = document.createElement('div');
    divWrap.className = 'column-65 border-1px-all col-back-nul';
    divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerText = curData['title'];
    divWrap2.append(divWrap3);
    divWrap.append(divWrap2);
    aDream.append(divWrap);

    divRow.append(aDream);

    return divRow;
}