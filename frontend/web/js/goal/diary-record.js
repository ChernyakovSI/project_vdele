let divParamDate = document.getElementById('paramDate');
let divParamID = document.getElementById('paramID');
let divParamIDDiary = document.getElementById('paramIdDiary');
let divParamText = document.getElementById('paramText');
let divParamIsGuest = document.getElementById('paramIsGuest');

let valueText = document.getElementById('valueText');
let valueDate = document.getElementById('valueDate');

let DataContent = document.getElementById('DataContent');

let btnDelete = document.getElementById('button-delete');
let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let thisData = {
    'id' : 0,
    'date' : 0,
    'id_diary' : 0,
    'text' : ''
};

let IsModified = false;

let thisDataBefore = {
    'date' : 0,
    'text' : ''
};

let thisFields = [];

$(document).ready( function() {
    if(divParamDate.innerText == '') {
        divParamDate.innerText = NowTimeStamp_Sec();
    }

    let strDate = convertTimeStampWithTime(divParamDate.innerText);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    thisData['date'] = divParamDate.innerText;

    valueDate.value = curDate.toISOString().substring(0, 16);

    if(divParamID.innerText) {
        thisData['id'] = Number(divParamID.innerText);
        thisData['text'] = valueText.value;
    }

    thisData['id_diary'] = Number(divParamIDDiary.innerText);

    valueText.innerHTML = getNewLinesToBr(divParamText);
    convertNewLinesToBr(valueText);
    DetectURLs(valueText);

    thisDataBefore['date'] = getTimeStampFromElement(valueDate);
    thisDataBefore['text'] = divParamText.innerText;

    renewButtons();

    getUserFields();
    generatorURLs();
})

//Events=======================================================================================================

valueDate.onchange = function(event){
    thisData['date'] = getTimeStampFromElement(this);

    WasModified();
};

valueText.onblur = function (event){
    thisData['text'] = this.innerHTML.trim();
    convertNewLinesToBr(this);
    DetectURLs(this);
    generatorURLs();

    WasModified();
}

btnDelete.onclick = function(e) {
    let ans = confirm('Удалить запись?');
    if(ans === true) {
        runAjax('/goal/diary-record-delete', thisData);
    }
};

btnSave.onclick = function(e) {
    rebuildURL();
    thisData.text = getBrToNewLines(valueText);
    loadThisFields();

    let inpContent;

    thisFieldsObj = [];
    for (numRow in thisFields) {
        thisRow = thisFields[numRow];

        inpContent = document.getElementById('elem'+thisRow['id']);

        if (thisRow['type'] == '4') {
            //text
            thisRow['value_txt'] = getBrToNewLines(inpContent);
        }

        newObj = {
            'id' : thisRow['id'],
            'type' : thisRow['type'],
            'value_str' : thisRow['value_str'],
            'value_int' : thisRow['value_int'],
            'value_boo' : thisRow['value_boo'],
            'value_txt' : thisRow['value_txt'],
            'value_dat' : thisRow['value_dat'],
        }
        thisFieldsObj.push(newObj);
    }

    thisJSONFields = JSON.stringify(thisFieldsObj);

    thisData['fields'] = thisJSONFields;

    runAjax('/goal/diary-record-save', thisData);
};

btnCancel.onclick = function(e) {
    closeForm()
};

function afterEditValue(elem) {

    loadThisFields();

    IsModified = true;
    WasModified();//TODO addFields
}

//Helpers=======================================================================================================

function renewButtons() {
    if ((thisData['id'] > 0) && (divParamIsGuest.innerText == '0')) {
        if(btnDelete.classList.contains('visible-not') == true) {
            btnDelete.classList.remove('visible-not');
        }
    } else {
        if(btnDelete.classList.contains('visible-not') == false) {
            btnDelete.classList.add('visible-not');
        }
    }
}

function WasModified() {
    IsModified = false;

    rebuildURL();
    let thisText = getBrToNewLines(valueText);
    generatorURLs();

    if ((thisDataBefore['date'] == thisData['date']
        && thisDataBefore['text'] == thisText
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

function render(data, Parameters) {
    IsModified = false;

    if(Parameters['url'] == '/goal/diary-record-fields' ) {
        thisFields = data.data;
        renewUserFields();
    } else {
        closeForm();
    }

}

function closeForm() {
    if (IsModified === false) {
        window.location.href = '/goal/diary/'+thisData['id_diary'];
    } else {
        let ans = confirm('Не сохранять изменения?');
        if(ans === true) {
            window.location.href = '/goal/diary/'+thisData['id_diary'];
        }
    }
}

function getUserFields(){
    runAjax('/goal/diary-record-fields', thisData);
}

function renewUserFields(){
    DataContent.innerHTML = '';

    let divWrap;
    let inpContent;
    let lblCaption;

    let strDate;
    let curDate;

    for (numRow in thisFields) {
        thisRow = thisFields[numRow];

        //string
        if(thisRow['type'] == '1'){
            divWrap = document.createElement('div');
            divWrap.className = 'message-wrap-line window-border w-100 wrapper';
            divWrap.id = 'wrap'+thisRow['id'];

            lblCaption = document.createElement('div');
            lblCaption.className = 'message-caption-line p-t_b-0';
            lblCaption.innerText = thisRow['title'];

            inpContent = document.createElement('input');
            inpContent.className = 'message-text-line';
            inpContent.id = 'elem'+thisRow['id'];
            inpContent.setAttribute('type', 'text');
            inpContent.setAttribute('placeholder', thisRow['title']);
            inpContent.setAttribute('onchange', 'afterEditValue(this)');
            if(divParamIsGuest.innerText == '1') {
                inpContent.setAttribute('disabled', 'disabled');
            }
            if(thisRow['value_str'] !== null) {
                inpContent.value = thisRow['value_str'];
            }

            DataContent.append(lblCaption);
            divWrap.append(inpContent);
            DataContent.append(divWrap);
        } else if (thisRow['type'] == '2') {
            //number
            divWrap = document.createElement('div');
            divWrap.className = 'message-wrapper-line w-100 window-border w-m-43px wrapper';
            divWrap.id = 'wrap'+thisRow['id'];

            lblCaption = document.createElement('div');
            lblCaption.className = 'message-caption-line p-t_b-0';
            lblCaption.innerText = thisRow['title'];

            inpContent = document.createElement('input');
            inpContent.className = 'message-text-line';
            inpContent.id = 'elem'+thisRow['id'];
            inpContent.setAttribute('type', 'number');
            inpContent.setAttribute('placeholder', thisRow['title']);
            inpContent.setAttribute('onchange', 'afterEditValue(this)');
            if(divParamIsGuest.innerText == '1') {
                inpContent.setAttribute('disabled', 'disabled');
            }
            if(thisRow['value_int'] !== null) {
                inpContent.value = Number(thisRow['value_int']);
            }

            DataContent.append(lblCaption);
            divWrap.append(inpContent);
            DataContent.append(divWrap);
        } else if (thisRow['type'] == '3') {
            //boolean
            divWrapSep = document.createElement('div');
            divWrapSep.className = 'clearfix';

            divWrap = document.createElement('div');
            divWrap.className = 'm-t-20px m-l-10px wrapper';
            divWrap.id = 'wrap'+thisRow['id'];

            lblCaption = document.createElement('label');
            lblCaption.className = 'interactive-only';
            lblCaption.innerText = thisRow['title'];
            lblCaption.setAttribute('for', 'elem'+thisRow['id']);

            inpContent = document.createElement('input');
            inpContent.className = 'custom-checkbox';
            inpContent.id = 'elem'+thisRow['id'];
            inpContent.setAttribute('type', 'checkbox');
            inpContent.setAttribute('onchange', 'afterEditValue(this)');
            if(divParamIsGuest.innerText == '1') {
                inpContent.setAttribute('disabled', 'disabled');
            }
            if(thisRow['value_boo'] === '1') {
                inpContent.checked = true;
            } else {
                inpContent.checked = false;
            }

            divWrap.append(inpContent);
            divWrap.append(lblCaption);
            DataContent.append(divWrapSep);
            DataContent.append(divWrap);
        } else if (thisRow['type'] == '4') {
            //text
            divWrap = document.createElement('div');
            divWrap.className = 'new-message-wrapper width-full h-m-20em window-border m-t-10px back-cells wrapper';
            divWrap.id = 'wrap'+thisRow['id'];

            lblCaption = document.createElement('div');
            lblCaption.className = 'message-caption-line p-t_b-0';
            lblCaption.innerText = thisRow['title'];

            inpContent = document.createElement('div');
            inpContent.className = 'message-text-multistring resize_vertical_only h-m-20em ahref';
            inpContent.id = 'elem'+thisRow['id'];
            inpContent.setAttribute('contentEditable', 'true');
            inpContent.setAttribute('onblur', 'afterEditValue(this)');
            if(divParamIsGuest.innerText == '1') {
                inpContent.setAttribute('contentEditable', 'false');
            }
            if(thisRow['value_txt'] !== null) {
                inpContent.innerHTML = getNewLinesToBr_Text(thisRow['value_txt']);
            }

            DataContent.append(lblCaption);
            divWrap.append(inpContent);
            DataContent.append(divWrap);

            convertNewLinesToBr(inpContent);
            DetectURLs(inpContent);
        } else if (thisRow['type'] == '5') {
            //date-time
            divWrap = document.createElement('div');
            divWrap.className = 'message-wrapper-line-half w-100 window-border wrapper';
            divWrap.id = 'wrap'+thisRow['id'];

            lblCaption = document.createElement('div');
            lblCaption.className = 'message-caption-line p-t_b-0';
            lblCaption.innerText = thisRow['title'];

            inpContent = document.createElement('input');
            inpContent.className = 'message-text-line';
            inpContent.id = 'elem'+thisRow['id'];
            inpContent.setAttribute('type', 'time');
            inpContent.setAttribute('contentEditable', 'contentEditable');
            inpContent.setAttribute('onchange', 'afterEditValue(this)');
            if(divParamIsGuest.innerText == '1') {
                inpContent.setAttribute('disabled', 'disabled');
            }
            if(thisRow['value_dat'] !== null) {
                if(thisRow['value_dat'] == 0 || thisRow['value_dat'] == ''){
                    strTime = getNowTimeStampWithoutDate();
                } else {
                    strTime = convertMinutesToTime(Number(thisRow['value_dat']));
                }
            } else {
                strTime = getNowTimeStampWithoutDate();
            }

            inpContent.value = strTime;

            DataContent.append(lblCaption);
            divWrap.append(inpContent);
            DataContent.append(divWrap);
        }

    }
}

function loadThisFields() {
    let inpContent;
    let wasText = false;

    for (numRow in thisFields) {
        thisRow = thisFields[numRow];

        inpContent = document.getElementById('elem'+thisRow['id']);

        if(thisRow['type'] == '1'){
            //string
            thisRow['value_str'] = inpContent.value;
        } else if (thisRow['type'] == '2') {
            //number
            valueInt = inpContent.value.replace(/\,/g, '.');
            thisRow['value_int'] = String(Number(valueInt));
        } else if (thisRow['type'] == '3') {
            //boolean
            thisRow['value_boo'] = String(Number(inpContent.checked));
        } else if (thisRow['type'] == '4') {
            //text
            wasText = true;
            thisRow['value_txt'] = inpContent.innerHTML.trim();

            convertNewLinesToBr(inpContent);
            DetectURLs(inpContent);
        } else if (thisRow['type'] == '5') {
            //date-time
            thisRow['value_dat'] = getTimeStampFromElementTime(inpContent);
        }
    }

    if(wasText == true) {
        generatorURLs();
    }
}

