let ParamID = document.getElementById('paramID').innerText;
let ParamIDSphere = document.getElementById('paramIDSphere').innerText;
let ParamDescription = document.getElementById('paramDescription');
let ParamTitle = document.getElementById('paramTitle').innerText;
let ParamPublic = document.getElementById('paramPublic').innerText;
let ParamPriority = document.getElementById('paramPriority').innerText;
let ParamDateFrom = document.getElementById('paramDateFrom').innerText;
let ParamDateTo = document.getElementById('paramDateTo').innerText;

let valueDateFrom = document.getElementById('valueDateFrom');
let valueDateTo = document.getElementById('valueDateTo');

let setPublic = document.getElementById('setPublic');

let btnAddRecord = document.getElementById('new-record');
let btnSave = document.getElementById('button-save');
let btnCancel = document.getElementById('button-cancel');
let btnSettings = document.getElementById('button-settings');
let btnClearSphere = document.getElementById('ClearSphere');

let list_sphere = document.getElementById('list_sphere');
let list_cards = document.getElementById('list-cards');
//++ 1-3-1-005 28/04/2023
let finalValues = document.getElementById('finalValues');
//-- 1-3-1-005 28/04/2023

let IsModified = false;

let thisData = {
    'id' : 0,
    'id_sphere' : 0,
    'description' : '',
    'title' : '',
    'priority' : 0,
    'is_public' : 0
};

let thisDataBefore = {
    'id_sphere' : 0,
    'description' : '',
    'title' : '',
    'priority' : 0,
    'is_public' : 0
};

$(document).ready( function() {
    let strDate = convertTimeStampWithTime(ParamDateFrom);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);

    valueDateFrom.value = curDate.toISOString().substring(0, 10);

    strDate = convertTimeStampWithTime(ParamDateTo);
    curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);

    valueDateTo.value = curDate.toISOString().substring(0, 10);

    if(ParamID !== '') {
        thisData['id'] = Number(ParamID);
        thisData['id_sphere'] = Number(ParamIDSphere);
        thisData['description'] = valueText.value;
        thisData['title'] = valueTitle.value;
        thisData['is_public'] = Number(ParamPublic);
        thisData['priority'] = Number(ParamPriority);
    }

    thisDataBefore['id_sphere'] = thisData['id_sphere'];
    thisDataBefore['title'] = thisData['title'];
    thisDataBefore['is_public'] = thisData['is_public'];
    thisDataBefore['description'] = thisData['description'];
    thisDataBefore['priority'] = thisData['priority'];

    renewFlags(true);

    valueText.innerText = getNewLinesToBr(ParamDescription);
    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();

    //++ 1-3-1-005 28/04/2023
    //*-
    //refreshRecords();
    //*+
    refreshRecords(1);
    //-- 1-3-1-005 28/04/2023
    resize();
})

//Events ---------------------------------------------------------------------------------------------------------------

btnAddRecord.onclick = function(e) {
    if(ParamID == '' || ParamID == 0) {
        alert('Сначала необходимо сохранить дневник');
    }
    else {
        if (IsModified === false) {
            window.location.href = '/goal/diary-record?dia=' + ParamID;
        } else {
            let ans = confirm('Не сохранять изменения?');
            if(ans === true) {
                window.location.href = '/goal/diary-record?dia=' + ParamID;
            }
        }
    }

};

btnSave.onclick = function(e) {
    rebuildURL();
    thisData.description = getBrToNewLines(valueText);

    runAjax('/goal/diary-save', thisData);
};

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;

    WasModified();
};

btnCancel.onclick = function(e) {

    closeForm()

};

btnSettings.onclick = function(e) {

    if(ParamID == '' || ParamID == 0) {
        alert('Сначала необходимо сохранить дневник');
    }
    else {
        if (IsModified === false) {
            window.location.href = '/goal/diary-settings/' + ParamID;
        } else {
            let ans = confirm('Не сохранять изменения?');
            if(ans === true) {
                window.location.href = '/goal/diary-settings/' + ParamID;
            }
        }
    }

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

    WasModified();
};

valuePriority.onchange = function(event){
    thisData['priority'] = Number(valuePriority.value);

    WasModified();
};

setPublic.onchange = function(event){
    renewFlags();

    WasModified();
};

valueTitle.onchange = function(event){
    thisData['title'] = this.value.trim();

    WasModified();
};

valueText.onblur = function (event){
    thisData['description'] = this.innerHTML.trim();
    convertNewLinesToBr(this);
    DetectURLs(this);
    generatorURLs();

    WasModified();
}

valueDateFrom.onchange = function(event){
    refreshRecords();
}

valueDateTo.onchange = function(event){
    refreshRecords();
}

//Helpers --------------------------------------------------------------------------------------------------------------

//++ 1-3-1-005 28/04/2023
//*-
//function refreshRecords() {
//*+
function refreshRecords(minElem = 0) {
//-- 1-3-1-005 28/04/2023

    if(thisData.id > 0) {
        let dateFrom = getTimeStampFromElement(valueDateFrom);
        let dateTo = endDay_timestamp(getTimeStampFromElement(valueDateTo));
        let optData = {};

        optData['dateFrom'] = Number(dateFrom);
        optData['dateTo'] = Number(dateTo);
        optData['id_diary'] = thisData.id;
        //++ 1-3-1-005 28/04/2023
        optData['minElem'] = minElem;
        //-- 1-3-1-005 28/04/2023

        runAjax('/goal/diary', optData);
    }
}

function renewFlags(fromDB = false) {
    if(fromDB == false) {
        thisData['is_public'] = Number(setPublic.checked);
    } else {
        if(thisData['is_public'] == 1) {
            setPublic.checked = true
        } else {
            setPublic.checked = false
        }

    }
}

function render(data, Parameters) {
    IsModified = false;

    if(Parameters.url == '/goal/diary') {
        renewRecords(data)
    } else {
        closeForm()
    }
}

function closeForm() {
    if (IsModified === false) {
        window.location.href = '/goal/diaries';
    } else {
        let ans = confirm('Не сохранять изменения?');
        if(ans === true) {
            window.location.href = '/goal/diaries';
        }
    }
}

function WasModified() {
    IsModified = false;

    if ((thisDataBefore['id_sphere'] == thisData['id_sphere']
        && thisDataBefore['description'] == thisData['description']
        && thisDataBefore['title'] == thisData['title']
        && thisDataBefore['priority'] == thisData['priority']
        && thisDataBefore['is_public'] == thisData['is_public']
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

function renewRecords(dataSet) {
    list_cards.innerHTML = '';

    if(dataSet.data.length > 0){
        let numRow = 0;

        let divRowSum;
        let arrSum = [];
        arrSum['id'] = 0

        //++ 1-3-1-005 28/04/2023
        renewFinalValues(dataSet.dataRecords, dataSet.dataTable, dataSet.data.length);
        //-- 1-3-1-005 28/04/2023

        divRowSum = createRow(arrSum, 0, '', dataSet.data.length, dataSet.dataTable, dataSet.dataRecords);
        list_cards.append(divRowSum);

        dataSet.data.forEach((curData) => {
            if(curData['id'] > 0) {
                numRow = numRow + 1;
                divRow = createRow(curData, numRow, dataSet.pathDiaryRecords, dataSet.dates[curData['id']], dataSet.dataTable, dataSet.dataRecords);

                //++ 1-3-1-005 28/04/2023
                let strDate = convertTimeStampWithTime(curData['date']);
                let curDate = new Date(strDate);
                curDate.setHours(curDate.getHours() - currentTimeZoneOffset);

                valueDateFrom.value = curDate.toISOString().substring(0, 10);
                //-- 1-3-1-005 28/04/2023

                list_cards.append(divRow);
            }
        })
    }
    else {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font-5 text-center margin-v20';
        divInfo.innerText = 'Нет записей';
        list_cards.append(divInfo);
    }

    resize();
}

function createRow(curData, numRow, pathDiaryRecords, date, dataTable, dataRecords) {
    let divRow = document.createElement('div');
    if(numRow > 0) {
        divRow.className = 'tableResize fin-acc-row movement-back interactive-only reg_'+ curData['id'];
        divRow.id = curData['id'];
    } else {
        divRow.className = 'tableResize fin-acc-row brown-back interactive-only';
    }

    let aRecord;
    let divWrapDate;
    let divWrap;
    if(numRow > 0) {
        aRecord = document.createElement('a');
        aRecord.setAttribute('href', pathDiaryRecords + curData['id']);
    } else {
        aRecord = document.createElement('div');
    }

    divWrapDate = document.createElement('div');
    if(dataTable.length == 0) {
        divWrapDate.className = 'column-100 border-1px-all colDate colResize';
    } else {
        divWrapDate.className = 'column-10 border-1px-all colDate colResize';
    }

    let divWrap2 = document.createElement('div');
    divWrap2.className = 'message-wrapper-title';
    let divWrap3 = document.createElement('div');
    divWrap3.className = 'message-text-line text-center';
    divWrap3.innerHTML = date;
    if(numRow == 0) {
        divWrap3.innerHTML = divWrap3.innerHTML + ' записей'
    }
    divWrap2.append(divWrap3);
    divWrapDate.append(divWrap2);
    aRecord.append(divWrapDate);

    //++ 1-3-1-005 28/04/2023
    let numColumn = 0;
    //-- 1-3-1-005 28/04/2023

    dataTable.forEach((column) => {
        //++ 1-3-1-005 28/04/2023
        numColumn = numColumn + 1;
        if (numColumn <= 5) {
        //-- 1-3-1-005 28/04/2023

            divWrap = document.createElement('div');
            divWrap.className = column['widthClass'] + ' border-1px-all colResize';

            divWrap2 = document.createElement('div');
            divWrap2.className = 'message-wrapper-title';

            divWrap3 = document.createElement('div');
            divWrap3.className = 'message-text-line text-center';
            if (Number(column['param_type']) == 5) {
                //time
                if (numRow > 0) {
                    try {
                        divWrap3.innerText = convertMinutesToTime(dataRecords[curData['id']][column['param_id']]);
                    } catch {
                        divWrap3.innerText = '';
                    }
                } else {
                    divWrap3.innerHTML = 'мин.: <b>' + convertMinutesToTime(dataRecords[curData['id']][column['param_id']]['min_val']) + '</b>' +
                        '<br>сред.: <b>' + convertMinutesToTime(dataRecords[curData['id']][column['param_id']]['average_val']) + '</b>' +
                        '<br>макс.: <b>' + convertMinutesToTime(dataRecords[curData['id']][column['param_id']]['max_val']) + '</b>';
                }
            } else if (Number(column['param_type']) == 3) {
                try {
                    if (Number(dataRecords[curData['id']][column['param_id']]) == 1) {
                        divWrap3.innerHTML = '<i class="fa fa-check-circle symbol_style text-center text-color-green" aria-hidden="true"></i>';
                    } else if (Number(dataRecords[curData['id']][column['param_id']]) >= 1) {
                        divWrap3.innerHTML = '<span>' + dataRecords[curData['id']][column['param_id']] +
                            ' <i class="fa fa-check-circle symbol_style text-center text-color-green" style="display: inline" aria-hidden="true"></i>' +
                            ' / ' + date + '</span>';
                    } else {
                        divWrap3.innerHTML = '<i class="fa fa-ban symbol_style text-center text-color-red" aria-hidden="true"></i>';
                    }
                } catch {
                    divWrap3.innerHTML = '';
                }

            } else {
                if (numRow > 0) {
                    try {
                        divWrap3.innerText = dataRecords[curData['id']][column['param_id']];
                    } catch {
                        divWrap3.innerText = '';
                    }
                } else {
                    if (Number(column['param_type']) == 2) {
                        //Number
                        divWrap3.innerHTML = 'мин.: <b>' + dataRecords[curData['id']][column['param_id']]['min'] + '</b>' +
                            '<br>сред.: <b>' + dataRecords[curData['id']][column['param_id']]['average'] + '</b>' +
                            '<br>макс.: <b>' + dataRecords[curData['id']][column['param_id']]['max'] + '</b>' +
                            '<br>сум.: <b>' + dataRecords[curData['id']][column['param_id']]['sum'] + '</b>';
                    }
                }
            }

            divWrap2.append(divWrap3);
            divWrap.append(divWrap2);
            aRecord.append(divWrap);

        //++ 1-3-1-005 28/04/2023
        }
        //-- 1-3-1-005 28/04/2023
    });

    divRow.append(aRecord);

    return divRow;
}

//++ 1-3-1-005 28/04/2023
function renewFinalValues(dataRecordsAll, dataTable, total) {
    dataRecords = dataRecordsAll[0]
    finalValues.innerHTML = '';

    for(idField in dataRecords){

        let NameField = ''
        let param_type = 0
        for(idRec in dataTable){
            if(dataTable[idRec]['param_id'] === Number(idField)) {
                NameField = dataTable[idRec]['param_title']
                param_type = dataTable[idRec]['param_type']
                break
            }
        }

        if (NameField !== '') {
            let divBlock = document.createElement('div');
            divBlock.classList.add('clear')
            let divWrap = document.createElement('div');
            divWrap.innerHTML = '<div class="w-152px float-left"><b>' + NameField + '</b></div> '

            if (param_type === 5) {
                //time
                divWrap.innerHTML = divWrap.innerHTML + '<div class="w-152px float-left">мин.: <b>' + convertMinutesToTime(dataRecords[idField]['min_val']) + '</b></div>' +
                        '<div class="w-152px float-left">, сред.: <b>' + convertMinutesToTime(dataRecords[idField]['average_val']) + '</b></div>' +
                        '<div class="w-152px float-left">, макс.: <b>' + convertMinutesToTime(dataRecords[idField]['max_val']) + '</b></div>';
            } else if (param_type === 3) {
                divWrap.innerHTML = divWrap.innerHTML +'<span>' + dataRecords[idField] +
                    ' <i class="fa fa-check-circle symbol_style text-center text-color-green" style="display: inline" aria-hidden="true"></i>' +
                    ' / ' + total + '</span>';
            } else if (param_type === 2) {
                //Number
                console.log(dataRecords[idField])
                divWrap.innerHTML = divWrap.innerHTML + '<div class="w-152px float-left">мин.: <b>' + dataRecords[idField]['min'] + '</b></div>' +
                    '<div class="w-152px float-left">, сред.: <b>' + dataRecords[idField]['average'] + '</b></div>' +
                    '<div class="w-152px float-left">, макс.: <b>' + dataRecords[idField]['max'] + '</b></div>' +
                    '<div class="w-152px float-left">, сум.: <b>' + dataRecords[idField]['sum'] + '</b></div>';
            }

            divBlock.append(divWrap)
            finalValues.append(divBlock)
        }

    }

}
//-- 1-3-1-005 28/04/2023