let divParamDate = document.getElementById('paramDate');
let divParamIDSphere = document.getElementById('paramIDSphere');

let valueDate = document.getElementById('valueDate');

let valueSphere = document.getElementById('valueSphere');
let list_sphere = document.getElementById('list_sphere');
let btnClearSphere = document.getElementById('ClearSphere');
let divContentNotes = document.getElementById('content-notes');
let divListNotes = document.getElementById('list-notes');

let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;

let thisData = {
    'date' : 0,
    'id_sphere' : 0
};

$(document).ready( function() {
    let strDate = convertTimeStampWithTime(divParamDate.innerText);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    curDate.setHours(0,0,0,0);

    thisData['date'] = String(curDate.getTime()).substr(0, 10);

    thisData['id_sphere'] = divParamIDSphere.innerText;

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

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;
};

btnCancel.onclick = function(e) {
    window.location.href = '/goal/calendar';
};

btnSave.onclick = function(e) {
    runAjax('/goal/day-save', thisData);
    window.location.href = '/goal/calendar';
};

valueDate.onchange = function(event){
    let curDate = new Date(this.value);
    curDate.setHours(curDate.getHours() + currentTimeZoneOffset);
    thisData['date'] = String(curDate.getTime()).substr(0, 10);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    console.log(thisData['date']);

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
    if(dataSet.dayData !== null && dataSet.dayData !== undefined) {
        valueSphere.value = dataSet.dayData[0]['name_sphere'];

    }

    let strDate = convertTimeStamp(thisData['date']);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    valueDate.value = curDate.toISOString().substring(0, 10);

    let colors = dataSet.colorStyle;

    divListNotes.innerHTML = '';
    if(dataSet.allNotes.length > 0) {
        divContentNotes.hidden = false;

        let pos = 0;
        dataSet.allNotes.forEach((data) => {

            pos = pos + 1;

            let divTitle = document.createElement('div');
            divTitle.className = 'message-text-line';
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
    }
    else {
        divContentNotes.hidden = true;
    }

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