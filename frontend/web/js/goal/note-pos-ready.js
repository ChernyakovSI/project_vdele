let valueDate = document.getElementById('valueDate');
let divParamDate = document.getElementById('paramDate');

let valueSphere = document.getElementById('valueSphere');
let list_sphere = document.getElementById('list_sphere');
let btnClearSphere = document.getElementById('ClearSphere');

let valueTitle = document.getElementById('valueTitle');
let valueText = document.getElementById('valueText');

let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let divParamID = document.getElementById('paramID');
let divParamIDSphere = document.getElementById('paramIDSphere');
let divParamNum = document.getElementById('paramNum');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

let thisData = {
    'id' : 0,
    'date' : 0,
    'id_sphere' : 0,
    'title' : '',
    'text' : '',
    'num' : 0,
};

$(document).ready( function() {
    let strDate = convertTimeStampWithTime(divParamDate.innerText);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    thisData['date'] = divParamDate.innerText; //String(curDate.getTime()).substr(0, 10);

    valueDate.value = curDate.toISOString().substring(0, 16);

    if(divParamID.innerText) {
        thisData['id'] = divParamID.innerText;
        thisData['id_sphere'] = divParamIDSphere.innerText;
        thisData['title'] = valueTitle.value;
        thisData['text'] = valueText.value;
        thisData['num'] = divParamNum.innerText;
    }

})

//Events

valueDate.onchange = function(event){
    let curDate = new Date(this.value);
    thisData['date'] = String(curDate.getTime()).substr(0, 10);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
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

valueTitle.onchange = function(event){
    thisData['title'] = this.value.trim();
};

valueText.onchange = function(event){
    thisData['text'] = this.value.trim();
};

btnCancel.onclick = function(e) {
    window.location.href = '/goal/notes';
};

btnSave.onclick = function(e) {
    runAjax('/goal/note-save', thisData);
};

//Helpers

function runAjax(url, value, typeReq = 'post'){
    floatingCirclesGMain.hidden = false;

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

                window.location.href = '/goal/notes';
            }
        }

        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        //console.log(value);
        floatingCirclesGMain.hidden = true;
    });
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