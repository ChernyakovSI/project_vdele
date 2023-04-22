let divParamIDDiary = document.getElementById('paramIDDiary');

let list_settings = document.getElementById('list-settings');
let list_type = document.getElementById('list_type');

let btnNewRecord = document.getElementById('new-record');
let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let thisFields = [];
let IsModified = false;

let thisData = {
    'id' : 0,
    'fields': []
};

$(document).ready( function() {
    if(divParamIDDiary.innerText !== '') {
        thisData['id'] = divParamIDDiary.innerText;
    }

    resize();

    loadThisFields();
    //for(field in thisFields){
        //renewTypeName(thisFields[field]);
    //}
})

//Events ---------------------------------------------------------------------------------------------------------------

btnNewRecord.onclick = function(e) {
    reshowTable(true);

    IsModified = true;
    WasModified();
};

function afterEditValue(elem) {
    loadThisFields();

    IsModified = true;
    WasModified();
}

btnSave.onclick = function(e) {
    thisFieldsObj = [];
    for(field in thisFields){
        newObj = {
            'id' : thisFields[field]['id'],
            'title' : thisFields[field]['title'],
            'type' : thisFields[field]['type'],
            'is_show' : thisFields[field]['is_show'],
            'show_priority' : thisFields[field]['show_priority'],
            'is_active' : thisFields[field]['is_active'],
        }
        thisFieldsObj.push(newObj);
    }

    thisJSONFields = JSON.stringify(thisFieldsObj);

    thisData['fields'] = thisJSONFields;

    runAjax('/goal/diary-settings-save', thisData);
};

btnCancel.onclick = function(e) {

    closeForm()

};

//Helpers --------------------------------------------------------------------------------------------------------------

function loadThisFields() {
    thisFields = [];

    children = list_settings.childNodes;
    let i = 0;

    for (child in children) {
        divRow = children[child].childNodes;

        try {
            children[child].hasAttribute('id');
        } catch (err) {
            continue;
        }
        if (children[child].hasAttribute('id') == false) {
            continue;
        }
        if (Number(children[child].id) == NaN || children[child].id == 'info1') {
            continue;
        }

        thisFields[i] = [];
        thisFields[i]['id'] = Number(children[child].id);

        let idType = 0;
        let childrenType = list_type.childNodes;

        for (column in divRow) {
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colTitle') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'INPUT') {

                                thisFields[i]['title'] = divRow3[column3].value;
                            }
                        }
                    }
                }
            }


            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colType') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'INPUT') {

                                curType = divRow3[column3].value.trim();
                                idType = 0;

                                for(childType in childrenType){
                                    if(childrenType[childType].nodeName === 'OPTION' && childrenType[childType].innerText === curType) {
                                        idType = childrenType[childType].getAttribute('data-id');
                                        thisFields[i]['type'] = Number(idType);
                                        break;
                                    }
                                }

                                if (idType === 0) {
                                    divRow3[column3].value = '';
                                    thisFields[i]['type'] = 0;
                                }
                            }
                        }
                    }
                }
            }

            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colPrio') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'INPUT') {

                                thisFields[i]['show_priority'] = Number(divRow3[column3].value);
                            }
                        }
                    }
                }
            }

            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colShow') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV' & (' ' + divRow3[column3].className + ' ').indexOf('message-text-line') > -1) {
                                divRow4 = divRow3[column3].childNodes;

                                for (column4 in divRow4) {
                                    if (divRow4[column4].nodeName === 'INPUT') {

                                        if (divRow4[column4].checked == true) {
                                            thisFields[i]['is_show'] = 1;
                                        } else {
                                            thisFields[i]['is_show'] = 0;
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colActive') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV' & (' ' + divRow3[column3].className + ' ').indexOf('message-text-line') > -1) {
                                divRow4 = divRow3[column3].childNodes;

                                for (column4 in divRow4) {
                                    if (divRow4[column4].nodeName === 'INPUT') {

                                        if (divRow4[column4].checked == true) {
                                            thisFields[i]['is_active'] = 1;
                                        } else {
                                            thisFields[i]['is_active'] = 0;
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }

        }

        i = i + 1;

    }
}

function reshowTable(newRow = false, delRow = 0){
    if (newRow == true) {
        newRow = [];
        newRow['title'] = '';
        newRow['type'] = 0;
        newRow['is_show'] = 0;
        newRow['show_priority'] = 0;
        newRow['is_active'] = 0;
        newRow['id'] = 0;
        thisFields.push(newRow);
    }
    if (delRow > 0) {

    }

    drawTable();
}

function drawTable() {
    list_settings.innerHTML = '';

    let types = [];
    types[0] = "Строка";
    types[1] = "Число";
    types[2] = "Флаг";
    types[3] = "Текст";
    types[4] = "Время";

    number = 0;
    for (numRow in thisFields) {
        thisRow = thisFields[numRow];
        number = number + 1;

        index = thisRow["id"];
        if (index == 0) {
            index = number;
        }

        let divRow = document.createElement('div');
        divRow.className = 'fin-acc-row movement-back interactive-only reg_'+thisRow["id"];
        divRow.id = thisRow.id;

        let divColNum = document.createElement('div');
        divColNum.className = 'column-5 border-1px-bottom colNum colResize';
        let divColNumWrap = document.createElement('div');
        divColNumWrap.className = 'message-wrapper-title';
        let divColNumWrapCentr = document.createElement('div');
        divColNumWrapCentr.className = 'message-text-line text-center';
        let divColNumContent = document.createElement('div');
        divColNumContent.innerText = number;

        let divColTitle = document.createElement('div');
        divColTitle.className = 'column-45 border-1px-bottom colTitle colResize';
        let divColTitleWrap = document.createElement('div');
        divColTitleWrap.className = 'wrapper-line';
        let divColTitleContent = document.createElement('input');
        divColTitleContent.className = 'message-text-line';
        divColTitleContent.id = 'title_'+index;
        divColTitleContent.setAttribute('type', 'text');
        divColTitleContent.setAttribute('contentEditable', 'contentEditable');
        divColTitleContent.setAttribute('onchange', 'afterEditValue(this)');
        divColTitleContent.value = thisRow.title;

        let divColType = document.createElement('div');
        divColType.className = 'column-20 border-1px-bottom colType colResize';
        let divColTypeWrap = document.createElement('div');
        divColTypeWrap.className = 'wrapper-line';
        let divColTypeContent = document.createElement('input');
        divColTypeContent.className = 'message-text-line';
        divColTypeContent.id = 'type_'+index;
        divColTypeContent.setAttribute('type', 'text');
        divColTypeContent.setAttribute('contentEditable', 'contentEditable');
        divColTypeContent.setAttribute('onchange', 'afterEditValue(this)');
        divColTypeContent.setAttribute('list', 'list_type');
        if(thisRow.type > 0) {
            divColTypeContent.value = types[thisRow.type-1];
        }

        let divColShow = document.createElement('div');
        divColShow.className = 'column-10 border-1px-bottom colShow colResize';
        let divColShowWrap = document.createElement('div');
        divColShowWrap.className = 'wrapper-line';
        let divColShowWrapCentr = document.createElement('div');
        divColShowWrapCentr.className = 'message-text-line text-center';
        let divColShowContent = document.createElement('input');
        divColShowContent.className = 'custom-checkbox';
        divColShowContent.id = 'is_show_'+index;
        divColShowContent.setAttribute('type', 'checkbox');
        divColShowContent.setAttribute('contentEditable', 'contentEditable');
        divColShowContent.setAttribute('onchange', 'afterEditValue(this)');
        if(thisRow.is_show > 0) {
            divColShowContent.setAttribute('checked', 'checked');
        }
        let divColShowLabel = document.createElement('label');
        divColShowLabel.setAttribute('for', 'is_show_'+index);
        divColShowLabel.className = 'interactive-only';

        let divColPrio = document.createElement('div');
        divColPrio.className = 'column-10 border-1px-bottom colPrio colResize';
        let divColPrioWrap = document.createElement('div');
        divColPrioWrap.className = 'wrapper-line';
        let divColPrioContent = document.createElement('input');
        divColPrioContent.className = 'message-text-line';
        divColPrioContent.id = 'show_priority_'+index;
        divColPrioContent.setAttribute('type', 'number');
        divColPrioContent.setAttribute('contentEditable', 'contentEditable');
        divColPrioContent.setAttribute('onchange', 'afterEditValue(this)');
        divColPrioContent.value = thisRow.show_priority;

        let divColActive = document.createElement('div');
        divColActive.className = 'column-10 border-1px-all colActive colResize';
        let divColActiveWrap = document.createElement('div');
        divColActiveWrap.className = 'wrapper-line';
        let divColActiveWrapCentr = document.createElement('div');
        divColActiveWrapCentr.className = 'message-text-line text-center';
        let divColActiveContent = document.createElement('input');
        divColActiveContent.className = 'custom-checkbox';
        divColActiveContent.id = 'is_active_'+index;
        divColActiveContent.setAttribute('type', 'checkbox');
        divColActiveContent.setAttribute('contentEditable', 'contentEditable');
        divColActiveContent.setAttribute('onchange', 'afterEditValue(this)');
        if(thisRow.is_active > 0 || thisRow["id"] == 0) {
            divColActiveContent.setAttribute('checked', 'checked');
        }
        let divColActiveLabel = document.createElement('label');
        divColActiveLabel.setAttribute('for', 'is_active_'+index);
        divColActiveLabel.className = 'interactive-only';

        divColNumWrapCentr.append(divColNumContent);
        divColNumWrap.append(divColNumWrapCentr);
        divColNum.append(divColNumWrap);
        divRow.append(divColNum);

        divColTitleWrap.append(divColTitleContent);
        divColTitle.append(divColTitleWrap);
        divRow.append(divColTitle);

        divColTypeWrap.append(divColTypeContent);
        divColType.append(divColTypeWrap);
        divRow.append(divColType);

        divColShowWrapCentr.append(divColShowContent);
        divColShowWrapCentr.append(divColShowLabel);
        divColShowWrap.append(divColShowWrapCentr);
        divColShow.append(divColShowWrap);
        divRow.append(divColShow);

        divColPrioWrap.append(divColPrioContent);
        divColPrio.append(divColPrioWrap);
        divRow.append(divColPrio);

        divColActiveWrapCentr.append(divColActiveContent);
        divColActiveWrapCentr.append(divColActiveLabel);
        divColActiveWrap.append(divColActiveWrapCentr);
        divColActive.append(divColActiveWrap);
        divRow.append(divColActive);

        list_settings.append(divRow);
    }

    resize();
}

function WasModified() {
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

    closeForm()
}

function closeForm() {
    if (IsModified === false) {
        window.location.href = '/goal/diary/' + thisData['id'];
    } else {
        let ans = confirm('Не сохранять изменения?');
        if(ans === true) {
            window.location.href = '/goal/diary/' + thisData['id'];
        }
    }
}