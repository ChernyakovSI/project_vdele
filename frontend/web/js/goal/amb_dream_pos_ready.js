let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

let divParamDate = document.getElementById('paramDate');
let divParamDateDone = document.getElementById('paramDateDone');
let paramDateGoal = document.getElementById('paramDateGoal');
let divParamID = document.getElementById('paramID');
let divParamIDSphere = document.getElementById('paramIDSphere');
let divParamNum = document.getElementById('paramNum');
let divParamStatus = document.getElementById('paramStatus');
let paramLevel = document.getElementById('paramLevel').innerText;

let valueTitle = document.getElementById('valueTitle');
let valueText = document.getElementById('valueText');
let valueDate = document.getElementById('valueDate');
let valueSphere = document.getElementById('valueSphere');
let valueLevel = document.getElementById('valueLevel');
let valueIsArchive = document.getElementById('isArchive');
let valueIsInProcess = document.getElementById('isInProcess');
let valueIsDone = document.getElementById('isDone');
let valueDateGoal = document.getElementById('valueDateGoal');

let list_level = document.getElementById('list_level');

let btnClearSphere = document.getElementById('ClearSphere');
let btnClearLevel = document.getElementById('ClearLevel');
let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let divCaption = document.getElementById('form-caption');


let thisData = {
    'id' : 0,
    'date' : 0,
    'dateGoal' : 0,
    'id_sphere' : 0,
    'title' : '',
    'text' : '',
    'num' : 0,
    'id_level': 0,
    'status': 0,
    'dateDone': 0,
};

$(document).ready( function() {

    let strDate = convertTimeStampWithTime(divParamDate.innerText);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    thisData['date'] = divParamDate.innerText;

    valueDate.value = curDate.toISOString().substring(0, 16);

    if(Number(paramLevel) === 4) {
        strDate = convertTimeStampWithTime(paramDateGoal.innerText);
        curDate = new Date(strDate);
        curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
        thisData['dateGoal'] = paramDateGoal.innerText;

        valueDateGoal.value = curDate.toISOString().substring(0, 16);
    }

    if(divParamID.innerText) {
        thisData['id'] = Number(divParamID.innerText);
        thisData['id_sphere'] = Number(divParamIDSphere.innerText);
        thisData['title'] = valueTitle.value;
        thisData['text'] = valueText.value;
        thisData['num'] = Number(divParamNum.innerText);
        thisData['id_level'] = Number(paramLevel);
        thisData['status'] = Number(divParamStatus.innerText);
        thisData['dateDone'] = Number(divParamDateDone.innerText);
    }
    else {
        thisData['id'] = 0;
        thisData['id_sphere'] = 0;
        thisData['title'] = '';
        thisData['text'] = '';
        thisData['num'] = 0;
        thisData['id_level'] = Number(paramLevel);
        renewLevelElement();
        thisData['status'] = 0;
        thisData['dateDone'] = 0;
    }

    renewStatusElement();
    renewCaption();

});

//Events

valueDate.onchange = function(event){
    let curDate = new Date(this.value);
    thisData['date'] = String(curDate.getTime()).substr(0, 10);
    //curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
};

valueSphere.onchange = function(event){
    let curSphere = this.value.trim();
    let idSphere = 0;

    let children = list_sphere.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText === curSphere) {
            idSphere = children[child].getAttribute('data-id');
            thisData['id_sphere'] = Number(idSphere);
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

valueLevel.onchange = function(event){
    let curLevel = this.value.trim();
    let idLevel = 0;

    let children = list_level.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText === curLevel) {
            idLevel = children[child].getAttribute('data-id');
            thisData['id_level'] = Number(idLevel);
            break;
        }
    }

    if (idLevel === 0) {
        this.value = '';
    }
};

valueDateGoal.onchange = function(e) {
    let curDate = new Date(this.value);
    thisData['dateGoal'] = String(curDate.getTime()).substr(0, 10);
};

btnClearLevel.onclick = function(e) {
    valueLevel.value = '';

    thisData['id_level'] = 1;
};

valueIsArchive.onclick = function(e) {
    renewDataStatus();
};

valueIsInProcess.onclick = function(e) {
    renewDataStatus();
};

valueIsDone.onclick = function(e) {
    renewDataStatus();
};

btnCancel.onclick = function(e) {
    if(thisData['id_level'] == 1) {
        window.location.href = '/goal/dreams';
    }
    else if (thisData['id_level'] == 4) {
        window.location.href = '/goal/goals';
    }
    else if (thisData['id_level'] == 3) {
        window.location.href = '/goal/intents';
    }
    else if (thisData['id_level'] == 2) {
        window.location.href = '/goal/wishes';
    }
    else if(thisData['id_level'] > 1) {
        window.location.href = '/goal/dreams?level='+thisData['id_level'];
    }
};

btnSave.onclick = function(e) {
    runAjax('/goal/dream-save', thisData);
};

//Helpers

function renewDataStatus() {
    if(valueIsArchive.checked === true) {
        thisData['status'] = 2;
    }
    if(valueIsInProcess.checked === true) {
        thisData['status'] = 0;
        thisData['dateDone'] = 0;
    }
    if(valueIsDone.checked === true) {
        thisData['status'] = 1;
        let curDate = new Date();
        thisData['dateDone'] = String(curDate.getTime()).substr(0, 10);
    }
}

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

                if(thisData['id_level'] == 1) {
                    window.location.href = '/goal/dreams';
                }
                else if(thisData['id_level'] > 1) {
                    window.location.href = '/goal/dreams?level='+thisData['id_level'];
                }
            }
        }

        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        //console.log(value);
        floatingCirclesGMain.hidden = true;
    });
}

function renewStatusElement() {
    if (thisData['status'] === 0) {
        valueIsInProcess.checked = true;
        valueIsArchive.checked = false;
        valueIsDone.checked = false;
    }
    if (thisData['status'] === 1) {
        valueIsInProcess.checked = false;
        valueIsArchive.checked = false;
        valueIsDone.checked = true;
    }
    if (thisData['status'] === 2) {
        valueIsInProcess.checked = false;
        valueIsArchive.checked = true;
        valueIsDone.checked = false;
    }
}

function renewLevelElement(){
    let children = list_level.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && Number(children[child].getAttribute('data-id')) === thisData['id_level']) {
            valueLevel.value = children[child].innerText;
            break;
        }
    }
}

function renewCaption() {
    if (thisData['id_level'] == 1) {
        divCaption.innerText = "Мечта";
    } else if(thisData['id_level'] == 2) {
        divCaption.innerText = "Желание";
    } else if(thisData['id_level'] == 3) {
        divCaption.innerText = "Намерение";
    } else if(thisData['id_level'] == 4) {
        divCaption.innerText = "Цель";
    }
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