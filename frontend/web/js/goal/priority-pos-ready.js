
let divParamID = document.getElementById('paramID');
let divParamDate = document.getElementById('paramDate');
let divParamDateFinish = document.getElementById('paramDateFinish');
let divParamName = document.getElementById('paramName');
let divParamStart = document.getElementById('paramStart');
let divParamFinish = document.getElementById('paramFinish');

let divArrowBack = document.getElementById('arrow-back');
let divArrowForward = document.getElementById('arrow-forward');
let divArrowBackH = document.getElementById('arrow-back-high');
let divArrowForwardH = document.getElementById('arrow-forward-high');
let divSymForward = document.getElementById('symForward');
let divSymBack = document.getElementById('symBack');

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');
let valueName = document.getElementById('valueName');

let tblExams = document.getElementById('list-dreams1');
let tblZachet = document.getElementById('list-dreams2');

let btnSave = document.getElementById('btnSave');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');


let thisData = {
    'id' : 0,
    'date' : 0,
    'dateFinish' : 0,
    'name' : '',
    'next': 0
};

$(document).ready( function() {
    thisData['date'] = divParamDate.innerText;
    valuePeriodFrom.value = getStringDateFromTimeStamp(divParamDate.innerText, false);
    if( thisData['date'] == 0){
        thisData['date'] = getTimeStampFromElement(valuePeriodFrom);
    }

    thisData['dateFinish'] = divParamDateFinish.innerText;
    valuePeriodTo.value = getStringDateFromTimeStamp(divParamDateFinish.innerText, false);
    if( thisData['dateFinish'] == 0){
        thisData['dateFinish'] = getTimeStampFromElement(valuePeriodTo);
    }

    thisData['name'] = divParamName.innerText;
    valueName.value = divParamName.innerText;

    thisData['id'] = divParamID.innerText;

    if(divParamID.innerText == ''){
        btnSave.hidden = false;
    }

    resize();

    renewEditable();
});

//Events -------------------------------------------------------------------------------------

valueName.onchange = function(event){
    thisData['name'] = this.value;
    btnSave.hidden = false;
};

valuePeriodFrom.onchange = function(event){
    thisData['date'] = getTimeStampFromElement(this);
    btnSave.hidden = false;

    HideAllError();
};

valuePeriodTo.onchange = function(event){
    thisData['dateFinish'] = getTimeStampFromElement(this);
    btnSave.hidden = false;

    HideAllError();
};

divArrowBack.onclick = function(event){
    if(divArrowBackH.classList.contains('ia-background')) {
        thisData['date'] = Number(thisData['date'])-24*60*60;
        thisData['next'] = 0;

        window.location.href = '/goal/priority?date='+thisData['date']+'&next='+thisData['next'];
    }
};

divArrowForward.onclick = function(event){
    if(divArrowForwardH.classList.contains('ia-background')) {
        thisData['date'] = Number(thisData['dateFinish']) + 24 * 60 * 60;
        thisData['next'] = 1;

        window.location.href = '/goal/priority?date=' + thisData['date'] + '&next=' + thisData['next'];
    }
};

btnSave.onclick = function(event){
    btnSave.hidden = true;

    let eData = isCorrect();

    if (eData.error == false) {
        runAjax('/goal/semester-save', thisData, floatingCirclesGMain);
    } else {
        showError(eData);
    }

};

//HELPERS ------------------------------------------------------------------------------------

function renewEditable() {
    if(thisData['date'] <= Number(divParamStart.innerText)) {
        divArrowBackH.classList.remove('ia-background');
        divArrowBackH.classList.add('ia-background-off');

        divSymBack.classList.remove('arrow');
    }

    if(thisData['dateFinish'] > Number(divParamFinish.innerText)) {
        divArrowForwardH.classList.remove('ia-background');
        divArrowForwardH.classList.add('ia-background-off');

        divSymForward.classList.remove('arrow');
    }

}

function resize(mode = 0) {
    let children = tblExams.childNodes;
    resizeTable(children, mode);

    children = tblZachet.childNodes;
    resizeTable(children, mode);

}

function resizeTable(children, mode = 0) {
    if (children.length > 0) {
        let divRow;

        let colNum, colADate, colDream, colMark, colDateDone, maxHeight;

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
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameNum') > -1) {
                        colNum = divRow[column];
                        if(maxHeight < colNum.clientHeight) {
                            maxHeight = colNum.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDeadline') > -1) {
                        colADate = divRow[column];
                        if(maxHeight < colADate.clientHeight) {
                            maxHeight = colADate.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDream') > -1) {
                        colDream = divRow[column];
                        if(maxHeight < colDream.clientHeight) {
                            maxHeight = colDream.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameMark') > -1) {
                        colMark = divRow[column];
                        if(maxHeight < colMark.clientHeight) {
                            maxHeight = colMark.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDateDone') > -1) {
                        colDateDone = divRow[column];
                        if(maxHeight < colDateDone.clientHeight) {
                            maxHeight = colDateDone.clientHeight;
                        }
                    }
                }
            }
            if(colNum != undefined && colNum.clientHeight != undefined < maxHeight) {
                colNum.style.height = maxHeight + "px";
            }
            if(colADate != undefined && colADate.clientHeight != undefined < maxHeight) {
                colADate.style.height = maxHeight + "px";
            }
            if(colDream != undefined && colDream.clientHeight != undefined < maxHeight) {
                colDream.style.height = maxHeight + "px";
            }
            if(colMark != undefined && colMark.clientHeight != undefined < maxHeight) {
                colMark.style.height = maxHeight + "px";
            }
            if(colDateDone != undefined && colDateDone.clientHeight != undefined < maxHeight) {
                colDateDone.style.height = maxHeight + "px";
            }
            colNum = undefined;
            colADate = undefined;
            colDream = undefined;
            colMark = undefined;
            colDateDone = undefined;

        }
    }
}

function render(data) {
    //window.location.href = '/goal/priority';
    window.location.href = '/goal/priority?date=' + thisData['dateFinish'] + '&next=1';
}

function isCorrect() {
    let eData = [];
    let eMessages = [];
    let eElements = [];

    if(thisData['date'] <= 0) {
        eMessages.push('Необходимо заполнить начало периода');
        eElements.push('PeriodFrom');
    }
    if(thisData['dateFinish'] <= 0) {
        eMessages.push('Необходимо заполнить окончание периода');
        eElements.push('PeriodTo');
    }
    if(thisData['date'] > thisData['dateFinish']) {
        eMessages.push('Окончание периода должно быть больше начала периода');
        eElements.push('PeriodTo');
    }

    eData['message'] = eMessages;
    eData['element'] = eElements;
    if(eMessages.length > 0) {
        eData['error'] = true;
    } else {
        eData['error'] = false;
    }

    return eData;
}