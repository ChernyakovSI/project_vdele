//include Date.js

let divParamDate = document.getElementById('paramDate');
let divParamDateDone = document.getElementById('paramDateDone');
let paramDateGoal = document.getElementById('paramDateGoal');
let divParamID = document.getElementById('paramID');
let divParamIDSphere = document.getElementById('paramIDSphere');
let divParamNum = document.getElementById('paramNum');
let divParamStatus = document.getElementById('paramStatus');
let paramLevel = document.getElementById('paramLevel').innerText;
let divParamText = document.getElementById('paramText');
let divParamResType = document.getElementById('paramResultType');
let divParamResMark = document.getElementById('paramResultMark');
let divParamResText = document.getElementById('paramResultText');

let setZachet = document.getElementById('setZachet');
let valueMark = document.getElementById('valueMark');

let valueTitle = document.getElementById('valueTitle');
let valueText = document.getElementById('valueText');
let valueDate = document.getElementById('valueDate');
let valueSphere = document.getElementById('valueSphere');
let valueLevel = document.getElementById('valueLevel');
let valueIsArchive = document.getElementById('isArchive');
let valueIsInProcess = document.getElementById('isInProcess');
let valueIsDone = document.getElementById('isDone');
let valueDateGoal = document.getElementById('valueDateGoal');

let valueIsUsual = document.getElementById('isUsual');
let valueIsZachet = document.getElementById('isZachet');
let valueIsExam = document.getElementById('isExam');

let list_level = document.getElementById('list_level');

let btnClearSphere = document.getElementById('ClearSphere');
let btnClearLevel = document.getElementById('ClearLevel');
let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');
let btnText = document.getElementById('btnText');
let btnResult = document.getElementById('btnResult');

let divCaption = document.getElementById('form-caption');
let divMarkZachet = document.getElementById('markZachet');
let divMarkExam = document.getElementById('markExam');
let divGoalResult = document.getElementById('goalResult');
let divGoalMark = document.getElementById('goalMark');

let GroupGoalResult = document.getElementById('goalResult');

let thisData = {
    'id' : 0,
    'date' : 0,
    'dateGoal' : 0, //Дата цели (в мечтах тоже есть)
    'id_sphere' : 0,
    'title' : '',
    'text' : '',
    'num' : 0,
    'id_level': 0,
    'status': 0,
    'dateDone': 0,
    'resultType': 0,
    'resultMark': 0,
    'resultText': '',
};

//++ 1-2-2-014 27/04/2022
let IsModified = false;
let thisDataBefore = {
    'date' : 0,
    'dateGoal' : 0,
    'id_sphere' : 0,
    'title' : '',
    'text' : '',
    'id_level': 0,
    'status': 0,
    'resultType': 0,
    'resultMark': 0,
    'resultText': '',
};
//-- 1-2-2-014 27/04/2022

$(document).ready( function() {

    thisData['date'] = divParamDate.innerText;
    valueDate.value = getStringDateFromTimeStamp(divParamDate.innerText);

    thisData['dateGoal'] = paramDateGoal.innerText;
    valueDateGoal.value = getStringDateFromTimeStamp(paramDateGoal.innerText);

    if(divParamID.innerText) {
        thisData['id'] = Number(divParamID.innerText);
        thisData['id_sphere'] = Number(divParamIDSphere.innerText);
        thisData['title'] = valueTitle.value;
        thisData['text'] = divParamText.innerText;
        thisData['num'] = Number(divParamNum.innerText);
        thisData['id_level'] = Number(paramLevel);
        thisData['status'] = Number(divParamStatus.innerText);
        thisData['dateDone'] = Number(divParamDateDone.innerText);
        thisData['resultType'] = Number(divParamResType.innerText);
        thisData['resultMark'] = Number(divParamResMark.innerText);;
        thisData['resultText'] = divParamResText.innerText;
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
        thisData['resultType'] = 0;
        thisData['resultMark'] = 0;
        thisData['resultText'] = '';
    }

    renewStatusElement();
    renewTypeElement();
    renewCaption();

    valueText.innerHTML = getNewLinesToBr(divParamResText);

    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();

    thisData['resultText'] = valueText.innerHTML.trim();

    valueText.innerHTML = getNewLinesToBr(divParamText);

    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();

    if(divParamResText.innerText !== '') {
        btnResult.click();
    }

    //++ 1-2-2-014 27/04/2022
    thisDataBefore['date'] = thisData['date'];//getTimeStampFromElement(valueDate);
    thisDataBefore['dateGoal'] = thisData['dateGoal'];//getTimeStampFromElement(valueDateGoal);
    thisDataBefore['id_sphere'] = thisData['id_sphere'];
    thisDataBefore['title'] = thisData['title'];
    thisDataBefore['text'] = divParamText.innerText;
    thisDataBefore['id_level'] = thisData['id_level'];
    thisDataBefore['status'] = thisData['status'];
    thisDataBefore['resultType'] = thisData['resultType'];
    thisDataBefore['resultMark'] = thisData['resultMark'];
    thisDataBefore['resultText'] = divParamResText.innerText;
    //-- 1-2-2-014 27/04/2022
});

//Events

btnText.onclick = function(e) {
    isText = btnText.classList.contains('btn-active');

    if(isText == false) {
        btnText.classList.add('btn-active');
        btnResult.classList.remove('btn-active');

        thisData['resultText'] = valueText.innerHTML.trim();
        valueText.innerHTML = thisData['text'];
    }
};

btnResult.onclick = function(e) {
    isText = btnResult.classList.contains('btn-active');

    if(isText == false) {
        btnResult.classList.add('btn-active');
        btnText.classList.remove('btn-active');

        thisData['text'] = valueText.innerHTML.trim();
        valueText.innerHTML = thisData['resultText'];
    }
};

setZachet.onclick = function(e) {
    if(setZachet.checked === false) {
        thisData['resultMark'] = 0;
    } else {
        thisData['resultMark'] = 1;
    }

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueMark.onchange = function(event){
    thisData['resultMark'] = valueMark.value;

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueDate.onchange = function(event){
    //++ 1-2-2-014 27/04/2022
    //*-
    //let curDate = new Date(this.value);
    //thisData['date'] = String(curDate.getTime()).substr(0, 10);
    //curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    //*+
    thisData['date'] = getTimeStampFromElement(this);

    WasModified();
    //-- 1-2-2-014 27/04/2022
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

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueTitle.onchange = function(event){
    thisData['title'] = this.value.trim();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
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

    if (thisData['id_level'] === 4) {
        GroupGoalResult.hidden = false;

       let nowTime = NowTimeStamp_Sec();
       let numDateGoal = Number(thisData['dateGoal']);
        if (numDateGoal < nowTime) {
            thisData['dateGoal'] = nowTime + 7*24*60*60;
            valueDateGoal.value = getStringDateFromTimeStamp(thisData['dateGoal']);
        }
    } else {
        GroupGoalResult.hidden = true;
    }

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueDateGoal.onchange = function(e) {
    //++ 1-2-2-014 27/04/2022
    //*-
    //let curDate = new Date(this.value);
    //thisData['dateGoal'] = String(curDate.getTime()).substr(0, 10);
    //*+
    thisData['dateGoal'] = getTimeStampFromElement(this);

    WasModified();
    //-- 1-2-2-014 27/04/2022
};

btnClearLevel.onclick = function(e) {
    valueLevel.value = '';

    thisData['id_level'] = 1;

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueIsArchive.onclick = function(e) {
    renewDataStatus();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueIsInProcess.onclick = function(e) {
    renewDataStatus();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueIsDone.onclick = function(e) {
    renewDataStatus();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueIsUsual.onclick = function(e) {
    renewDataType();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueIsZachet.onclick = function(e) {
    renewDataType();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

valueIsExam.onclick = function(e) {
    renewDataType();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
};

btnCancel.onclick = function(e) {
    //++ 1-2-2-014 27/04/2022
    let urlString = '';
    //-- 1-2-2-014 27/04/2022

    if(thisData['id_level'] == 1) {
        //++ 1-2-2-014 27/04/2022
        //*-
        //window.location.href = '/goal/dreams';
        //*+
        urlString = '/goal/dreams';
        //-- 1-2-2-014 27/04/2022
    }
    else if (thisData['id_level'] == 4) {
        if(thisData['result_type'] == 0) {
            //++ 1-2-2-014 27/04/2022
            //*-
            //window.location.href = '/goal/goals';
            //*+
            urlString = '/goal/goals';
            //-- 1-2-2-014 27/04/2022
        } else {
            //++ 1-2-2-014 27/04/2022
            //*-
            //window.location.href = '/goal/priority';
            //*+
            urlString = '/goal/priority';
            //-- 1-2-2-014 27/04/2022
        }

    }
    else if (thisData['id_level'] == 3) {
        //++ 1-2-2-014 27/04/2022
        //*-
        //window.location.href = '/goal/intents';
        //*+
        urlString = '/goal/intents';
        //-- 1-2-2-014 27/04/2022
    }
    else if (thisData['id_level'] == 2) {
        //++ 1-2-2-014 27/04/2022
        //*-
        //window.location.href = '/goal/wishes';
        //*+
        urlString = '/goal/wishes';
        //-- 1-2-2-014 27/04/2022
    }
    else if(thisData['id_level'] > 1) {
        //++ 1-2-2-014 27/04/2022
        //*-
        //window.location.href = '/goal/dreams?level='+thisData['id_level'];
        //*+
        urlString = '/goal/dreams?level='+thisData['id_level'];
        //-- 1-2-2-014 27/04/2022
    }

    //++ 1-2-2-014 27/04/2022
    if (IsModified === false) {
        window.location.href = urlString;
    } else {
        let ans = confirm('Не сохранять изменения?');
        if(ans === true) {
            window.location.href = urlString;
        }
    }
    //-- 1-2-2-014 27/04/2022
};

valueText.onblur = function (event){
    isText = btnText.classList.contains('btn-active');

    if(isText == true) {
        //++ 1-2-2-012 24/04/2022
        //*-
        //thisData['text'] = getBrToNewLines(this);
        //*+
        thisData['text'] = this.innerHTML.trim();
        //-- 1-2-2-012 24/04/2022
        //++ 1-2-2-012 24/04/2022
        //console.log(thisData['text']);
        //-- 1-2-2-012 24/04/2022
    } else {
        //++ 1-2-2-012 24/04/2022
        //*-
        //thisData['resultText'] = getBrToNewLines(this);
        //*+
        thisData['resultText'] = this.innerHTML.trim();
        //-- 1-2-2-012 24/04/2022
        //++ 1-2-2-012 24/04/2022
        //console.log(thisData['resultText']);
        //-- 1-2-2-012 24/04/2022
    }
    convertNewLinesToBr(this);
    DetectURLs(this);
    generatorURLs();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
}

btnSave.onclick = function(e) {
    valueText.innerHTML = getNewLinesToBr_Text(thisData['text']);
    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();
    thisData['text'] = getBrToNewLines(valueText);

    valueText.innerHTML = getNewLinesToBr_Text(thisData['resultText']);
    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();
    thisData['resultText'] = getBrToNewLines(valueText);

    //++ 1-2-2-012 24/04/2022
    //console.log(thisData.text);
    //console.log(thisData.resultText);
    //-- 1-2-2-012 24/04/2022

    runAjax('/goal/dream-save', thisData);
};

//Helpers

function renewDataStatus() {
    if(valueIsArchive.checked === true) {
        thisData['status'] = 2;
        let curDate = new Date();
        thisData['dateDone'] = String(curDate.getTime()).substr(0, 10);
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

function renewDataType() {
    if(valueIsUsual.checked === true) {
        thisData['resultType'] = 0;
        divMarkZachet.hidden = true;
        divMarkExam.hidden = true;
        divGoalMark.hidden = true;
    }
    if(valueIsZachet.checked === true) {
        thisData['resultType'] = 1;
        divMarkZachet.hidden = false;
        divMarkExam.hidden = true;
        divGoalMark.hidden = false;
    }
    if(valueIsExam.checked === true) {
        thisData['resultType'] = 2;
        divMarkZachet.hidden = true;
        divMarkExam.hidden = false;
        divGoalMark.hidden = false;
    }
    renewTypeElement();
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
                //console.log(thisData['id_level']);
                //console.log(data);
                //return;

                if(thisData['id_level'] == 1) {
                    window.location.href = '/goal/dreams';
                }
                else if(thisData['id_level'] == 4 && thisData['resultType'] > 0) {
                    window.location.href = '/goal/priority';
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

function renewTypeElement() {
    if (thisData['resultType'] === 0) {
        valueIsUsual.checked = true;
        valueIsZachet.checked = false;
        valueIsExam.checked = false;
    }
    if (thisData['resultType'] === 1) {
        valueIsUsual.checked = false;
        valueIsZachet.checked = true;
        valueIsExam.checked = false;

        if(thisData['resultMark'] > 0) {
            setZachet.checked = true;
        } else {
            setZachet.checked = false;
        }

    }
    if (thisData['resultType'] === 2) {
        valueIsUsual.checked = false;
        valueIsZachet.checked = false;
        valueIsExam.checked = true;

        valueMark.value = thisData['resultMark'];
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

    if (thisData['id_level'] === 4) {
        GroupGoalResult.hidden = false;
    } else {
        GroupGoalResult.hidden = true;
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

//++ 1-2-2-014 27/04/2022
function WasModified() {
    IsModified = false;

    let isText = btnText.classList.contains('btn-active');
    let thisText = '';
    let thisTextResult = '';

    if(isText == true) {
        valueText.innerHTML = getNewLinesToBr_Text(thisData['resultText']);
        convertNewLinesToBr(valueText);
        DetectURLs(valueText);
        generatorURLs();
        thisTextResult = getBrToNewLines(valueText);

        valueText.innerHTML = getNewLinesToBr_Text(thisData['text']);
        convertNewLinesToBr(valueText);
        DetectURLs(valueText);
        generatorURLs();
        thisText = getBrToNewLines(valueText);
    } else {
        valueText.innerHTML = getNewLinesToBr_Text(thisData['text']);
        convertNewLinesToBr(valueText);
        DetectURLs(valueText);
        generatorURLs();
        thisText = getBrToNewLines(valueText);

        valueText.innerHTML = getNewLinesToBr_Text(thisData['resultText']);
        convertNewLinesToBr(valueText);
        DetectURLs(valueText);
        generatorURLs();
        thisTextResult = getBrToNewLines(valueText);
    }

    if ((thisDataBefore['date'] == thisData['date']
        && thisDataBefore['dateGoal'] == thisData['dateGoal']
        && thisDataBefore['id_sphere'] == thisData['id_sphere']
        && thisDataBefore['title'] == thisData['title']
        && thisDataBefore['text'] == thisText
        && thisDataBefore['id_level'] == thisData['id_level']
        && thisDataBefore['status'] == thisData['status']
        && thisDataBefore['resultType'] == thisData['resultType']
        && thisDataBefore['resultMark'] == thisData['resultMark']
        && thisDataBefore['resultText'] == thisTextResult
    ) == false
    ) {
        IsModified = true;
    }

    if(IsModified === true) {
        if (btnCancel.classList.contains('col-back-rea-light') === false) {
            btnCancel.classList.add('col-back-rea-light');
        }
    } else {
        if (btnCancel.classList.contains('col-back-rea-light') === true) {
            btnCancel.classList.remove('col-back-rea-light');
        }
    }

}
//-- 1-2-2-014 27/04/2022