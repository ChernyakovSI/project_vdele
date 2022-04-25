let divParamDate = document.getElementById('paramDate');
let divParamIDSphere = document.getElementById('paramIDSphere');

let valueDate = document.getElementById('valueDate');

let valueSphere = document.getElementById('valueSphere');
let list_sphere = document.getElementById('list_sphere');
let btnClearSphere = document.getElementById('ClearSphere');
let divContentNotes = document.getElementById('content-notes');
let divContentFin = document.getElementById('content-fin');
let divListNotes = document.getElementById('list-notes');
let divListGoals = document.getElementById('list-goals');
let divCellNoteTitle = document.getElementById('cell-note-title');
let divCellGoalTitle = document.getElementById('cell-goal-title');
//++ 1-2-2-008 11/04/2022
let setKey = document.getElementById('setKey');
//-- 1-2-2-008 11/04/2022

let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;

let thisData = {
    'date' : 0,
    //++ 1-2-2-008 11/04/2022
    'isKey' : 0,
    //-- 1-2-2-008 11/04/2022
    'id_sphere' : 0
};

let spanDelta = document.querySelector('#delta');

$(document).ready( function() {
    let strDate = convertTimeStampWithTime(divParamDate.innerText);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    curDate.setHours(0,0,0,0);

    thisData['date'] = String(curDate.getTime()).substr(0, 10);

    thisData['id_sphere'] = divParamIDSphere.innerText;

    //++ 1-2-2-008 11/04/2022
    thisData['isKey'] = 0;
    //-- 1-2-2-008 11/04/2022

    runAjax('/goal/get-data-for-day', thisData);

});

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;
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

};

//++ 1-2-2-008 11/04/2022
setKey.onclick = function(e) {
    if (this.checked === true) {
        thisData['isKey'] = 1;
    } else {
        thisData['isKey'] = 0;
    }
};
//-- 1-2-2-008 11/04/2022

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;
};

btnCancel.onclick = function(e) {
    window.location.href = '/goal/calendar';
};

btnSave.onclick = function(e) {
    //console.log(thisData);
    runAjax('/goal/day-save', thisData);
    window.location.href = '/goal/calendar';
};

valueDate.onchange = function(event){
    let curDate = new Date(this.value);
    curDate.setHours(curDate.getHours() + currentTimeZoneOffset);
    thisData['date'] = String(curDate.getTime()).substr(0, 10);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    //++ 1-2-2-013 25/04/2022
    //console.log(thisData['date']);
    //-- 1-2-2-013 25/04/2022

    runAjax('/goal/get-data-for-day', thisData);
};

//-----------------------

function runAjax(url, value, typeReq = 'post'){
    floatingCirclesGMain.hidden = false;

    $.ajax({
        type : typeReq,
        url : url,
        data : value
    }).done(function(data) {
        if (data.error === null || data.error === undefined || data.error === '') {
            render(data);

        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){
                //showError(data);
            }
        }

        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        floatingCirclesGMain.hidden = true;
    });
}

function render(dataSet) {
    if(dataSet.dayData !== null && dataSet.dayData !== undefined && dataSet.dayData.length > 0) {
        valueSphere.value = dataSet.dayData[0]['name_sphere'];

        //++ 1-2-2-008 11/04/2022
        DataDay = dataSet.dayData[0];

        thisData['id_sphere'] = DataDay['id_sphere'];

        if (DataDay['is_key'] === '1') {
            setKey.checked = true;
            thisData['isKey'] = 1;
        } else {
            setKey.checked = false;
            thisData['isKey'] = 0;
        }
        //-- 1-2-2-008 11/04/2022
    }

    let strDate = convertTimeStamp(thisData['date']);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    valueDate.value = curDate.toISOString().substring(0, 10);

    let colors = dataSet.colorStyle;

    divContentNotes.hidden = true;

    divListNotes.innerHTML = '';
    if(dataSet.allNotes.length > 0) {
        divContentNotes.hidden = false;

        let pos = 0;
        dataSet.allNotes.forEach((data) => {

            pos = pos + 1;

            let divTitle = document.createElement('div');
            divTitle.className = 'message-text-line text-bold text-color-black';
            divTitle.innerText = data['title'];

            let divWrap = document.createElement('div');
            divWrap.className = 'message-wrapper-title';

            let divBorder = document.createElement('div');
            if(pos === dataSet.allNotes.length){
                divBorder.className = 'border-1px-all';
            }
            else {
                divBorder.className = 'border-1px-right';
            }

            let aNote = document.createElement('a');
            aNote.setAttribute('href', dataSet.pathNotes + data['num']);

            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row interactive-only ' + colors[data['id_sphere']];;

            divWrap.append(divTitle);
            divBorder.append(divWrap);
            aNote.append(divBorder);
            divRow.append(aNote);
            divListNotes.append(divRow);
        })

        divCellNoteTitle.classList.remove('border-1px-right');
        divCellNoteTitle.classList.remove('border-1px-all');
        divCellNoteTitle.classList.add('border-1px-right');
    }
    else {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'infoExp');
        divInfo.innerHTML = 'Нет данных';

        divListNotes.append(divInfo);

        divCellNoteTitle.classList.remove('border-1px-right');
        divCellNoteTitle.classList.remove('border-1px-all');
        divCellNoteTitle.classList.add('border-1px-all');
    }

    divListGoals.innerHTML = '';
    if(dataSet.goals.length > 0) {
        divContentNotes.hidden = false;

        let pos = 0;
        dataSet.goals.forEach((data) => {

            pos = pos + 1;

            let divTitle = document.createElement('div');
            divTitle.className = 'message-text-line text-bold text-color-black';
            divTitle.innerText = data['title'];

            let divWrap = document.createElement('div');
            divWrap.className = 'message-wrapper-title';

            let divBorder = document.createElement('div');
            if(pos === dataSet.goals.length){
                divBorder.className = 'border-1px-all';
            }
            else {
                divBorder.className = 'border-1px-right';
            }

            let aNote = document.createElement('a');
            aNote.setAttribute('href', dataSet.pathGoals + data['num']);

            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row interactive-only ' + colors[data['id_sphere']];;

            divWrap.append(divTitle);
            divBorder.append(divWrap);
            aNote.append(divBorder);
            divRow.append(aNote);
            divListGoals.append(divRow);
        })

        divCellGoalTitle.classList.remove('border-1px-right');
        divCellGoalTitle.classList.remove('border-1px-all');
        divCellGoalTitle.classList.add('border-1px-right');
    }
    else {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'infoExp');
        divInfo.innerHTML = 'Нет данных';

        divListGoals.append(divInfo);

        divCellGoalTitle.classList.remove('border-1px-right');
        divCellGoalTitle.classList.remove('border-1px-all');
        divCellGoalTitle.classList.add('border-1px-all');
    }

    //Finance
    if(dataSet.dataExp.length > 0 || dataSet.dataProf.length > 0) {
        divContentFin.hidden = false;
    } else {
        divContentFin.hidden = true;
    }

    //Expences

    let listExp = document.getElementById('list-expenses');
    listExp.innerHTML = '';

    let SumFormatExp = dataSet.SumFormatExp;

    if(dataSet.dataExp.length > 0){
        dataSet.dataExp.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row expense-back interactive-only';

            let divMainCat = document.createElement('div');
            divMainCat.className = 'fin-reg-cat-60 table-text';

            let divWrapCat = document.createElement('div');
            divWrapCat.className = 'message-wrapper-title';

            let divTextCat = document.createElement('div');
            divTextCat.className = 'message-text-line';

            divTextCat.innerHTML = data['CatName'];

            divWrapCat.append(divTextCat);
            divMainCat.append(divWrapCat);
            divRow.append(divMainCat);

            let divMainAmount = document.createElement('div');

            let divWrapAmount = document.createElement('div');
            divWrapAmount.className = 'message-wrapper-title';

            let divTextAmount = document.createElement('div');
            divTextAmount.className = 'message-text-line right-text';
            divTextAmount.innerHTML = SumFormatExp[data['id_category']];
            divMainAmount.className = 'fin-reg-amount-end  table-text';

            divWrapAmount.append(divTextAmount);
            divMainAmount.append(divWrapAmount);
            divRow.append(divMainAmount);

            let hrLine = document.createElement('hr');
            hrLine.className = 'line';

            let divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divClear.append(hrLine);
            divRow.append(divClear);

            listExp.append(divRow);
        });

        // resize();
    }
    else
    {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'infoExp');
        divInfo.innerHTML = 'Нет движений';

        listExp.append(divInfo);
    }

    let divTotalExp = document.getElementById('totalExp');
    divTotalExp.innerHTML = dataSet.totalExp;

    // Profits

    let listProfit = document.getElementById('list-profits');
    listProfit.innerHTML = '';

    let SumFormatProf = dataSet.SumFormatProf;

    if(dataSet.dataProf.length > 0){
        dataSet.dataProf.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row profit-back interactive-only';

            let divMainCat = document.createElement('div');
            divMainCat.className = 'fin-reg-cat-60 table-text';

            let divWrapCat = document.createElement('div');
            divWrapCat.className = 'message-wrapper-title';

            let divTextCat = document.createElement('div');
            divTextCat.className = 'message-text-line';

            divTextCat.innerHTML = data['CatName'];

            divWrapCat.append(divTextCat);
            divMainCat.append(divWrapCat);
            divRow.append(divMainCat);

            let divMainAmount = document.createElement('div');

            let divWrapAmount = document.createElement('div');
            divWrapAmount.className = 'message-wrapper-title';

            let divTextAmount = document.createElement('div');
            divTextAmount.className = 'message-text-line right-text';
            divTextAmount.innerHTML = SumFormatProf[data['id_category']];
            divMainAmount.className = 'fin-reg-amount-end table-text';

            divWrapAmount.append(divTextAmount);
            divMainAmount.append(divWrapAmount);
            divRow.append(divMainAmount);

            let hrLine = document.createElement('hr');
            hrLine.className = 'line';

            let divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divClear.append(hrLine);
            divRow.append(divClear);

            listProfit.append(divRow);
        });



        // resize();
    }
    else
    {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'infoProf');
        divInfo.innerHTML = 'Нет движений';

        listProfit.append(divInfo);
    }

    let divTotalProf = document.getElementById('totalProf');
    divTotalProf.innerHTML = dataSet.totalProf;

    spanDelta.innerHTML = dataSet.totalDelta;

    //console.log(dataSet);
}

function convertTimeStampWithTime(timestamp) {
    let condate = new Date(timestamp*1000);

    strDate = [
        condate.getFullYear(),           // Get day and pad it with zeroes
        ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
        ('0' + condate.getDate()).slice(-2)                          // Get full year
    ].join('-');  // Glue the pieces together

    strDate = strDate + 'T';

    strTime = [
        ('0' + (condate.getHours())).slice(-2),
        ('0' + condate.getMinutes()).slice(-2)
    ].join(':');

    return strDate+strTime;
}

function convertTimeStamp(timestamp) {
    let condate = new Date(timestamp*1000);

    return [
        condate.getFullYear(),           // Get day and pad it with zeroes
        ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
        ('0' + condate.getDate()).slice(-2)                          // Get full year
    ].join('.');                                  // Glue the pieces together
}