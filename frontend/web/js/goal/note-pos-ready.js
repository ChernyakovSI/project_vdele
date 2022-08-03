let valueDate = document.getElementById('valueDate');
let divParamDate = document.getElementById('paramDate');
let divParamText = document.getElementById('paramText');
//++ 1-2-3-006 28/07/2022
let divParamPublic = document.getElementById('paramPublic');
let paramDomain = document.getElementById('paramDomain');
//-- 1-2-3-006 28/07/2022

let valueSphere = document.getElementById('valueSphere');
let list_sphere = document.getElementById('list_sphere');
let btnClearSphere = document.getElementById('ClearSphere');

//++ 1-2-3-006 28/07/2022
let setPublic = document.getElementById('setPublic');
let setPublicLink = document.getElementById('setPublicLink');
let PublicURL = document.getElementById('PublicURL');
let PublicURLGap = document.getElementById('PublicURLgap');
//-- 1-2-3-006 28/07/2022

let valueTitle = document.getElementById('valueTitle');
let valueText = document.getElementById('valueText');

let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let divParamID = document.getElementById('paramID');
let divParamIDSphere = document.getElementById('paramIDSphere');
let divParamNum = document.getElementById('paramNum');

//++ 1-2-2-014 27/04/2022
//let nowServer = new Date();
//let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
//nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);
//-- 1-2-2-014 27/04/2022

let thisData = {
    'id' : 0,
    'date' : 0,
    'id_sphere' : 0,
    'title' : '',
    'text' : '',
    'num' : 0,
    //++ 1-2-3-006 28/07/2022
    'isPublic' : 0,
    //-- 1-2-3-006 28/07/2022
};

//++ 1-2-2-014 27/04/2022
let IsModified = false;
let thisDataBefore = {
    'date' : 0,
    'id_sphere' : 0,
    'title' : '',
    'text' : '',
    //++ 1-2-3-006 28/07/2022
    'isPublic' : 0,
    //-- 1-2-3-006 28/07/2022
};
//-- 1-2-2-014 27/04/2022

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
        //++ 1-2-3-006 28/07/2022
        thisData['isPublic'] = Number(divParamPublic.innerText);
        //-- 1-2-3-006 28/07/2022
    }

    valueText.innerHTML = getNewLinesToBr(divParamText);

    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();

    //++ 1-2-2-014 27/04/2022
    thisDataBefore['date'] = getTimeStampFromElement(valueDate);
    thisDataBefore['id_sphere'] = thisData['id_sphere'];
    thisDataBefore['title'] = thisData['title'];
    thisDataBefore['text'] = divParamText.innerText;
    //-- 1-2-2-014 27/04/2022
    //++ 1-2-3-006 28/07/2022
    thisDataBefore['isPublic'] = Number(divParamPublic.innerText);

    renewPublic(true);
    //-- 1-2-3-006 28/07/2022
})

//Events

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
            thisData['id_sphere'] = idSphere;
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

valueText.onblur = function (event){
    thisData['text'] = this.innerHTML.trim();
    convertNewLinesToBr(this);
    DetectURLs(this);
    generatorURLs();

    //++ 1-2-2-014 27/04/2022
    WasModified();
    //-- 1-2-2-014 27/04/2022
}

btnCancel.onclick = function(e) {
    //++ 1-2-2-014 27/04/2022
    if (IsModified === false) {
    //-- 1-2-2-014 27/04/2022
        window.location.href = '/goal/notes';
    //++ 1-2-2-014 27/04/2022
    } else {
        let ans = confirm('Не сохранять изменения?');
        if(ans === true) {
            window.location.href = '/goal/notes';
        }
    }
    //-- 1-2-2-014 27/04/2022
};

btnSave.onclick = function(e) {
    //++ 1-2-2-010 19/04/2022
    rebuildURL();
    //-- 1-2-2-010 19/04/2022
    thisData.text = getBrToNewLines(valueText);
    runAjax('/goal/note-save', thisData);
};

//++ 1-2-3-006 28/07/2022
setPublic.onchange = function(e) {
    renewPublic();
};
//-- 1-2-3-006 28/07/2022

//Helpers

//++ 1-2-3-006 28/07/2022
function renewPublic(fromDB = false) {
    if(fromDB == false) {
        thisData['isPublic'] = Number(setPublic.checked);
    } else {
        setPublic.checked = thisData['isPublic']
    }

    if (thisData['isPublic'] == 1 && thisData['id'] > 0) {
        setPublicLink.innerText = 'Снять публикацию';
        PublicURL.innerText = 'Опубликовано по ссылке: ' + paramDomain.innerText + 'public/' + thisData['id'];
        if(PublicURLGap.classList.contains('visible-not') == true) {
            PublicURLGap.classList.remove('visible-not');
        }
    } else {
        setPublicLink.innerText = 'Опубликовать';
        PublicURL.innerText = '';
        if(PublicURLGap.classList.contains('visible-not') == false) {
            PublicURLGap.classList.add('visible-not');
        }
    }
};
//-- 1-2-3-006 28/07/2022

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

//++ 1-2-2-014 27/04/2022
function WasModified() {
    IsModified = false;

    rebuildURL();
    let thisText = getBrToNewLines(valueText);
    generatorURLs();

    if ((thisDataBefore['date'] == thisData['date']
        && thisDataBefore['id_sphere'] == thisData['id_sphere']
        && thisDataBefore['title'] == thisData['title']
        && thisDataBefore['text'] == thisText
        //++ 1-2-3-006 28/07/2022
        && thisDataBefore['isPublic'] == thisData['isPublic']
        //-- 1-2-3-006 28/07/2022
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