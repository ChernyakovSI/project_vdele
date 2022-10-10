let divParamDate = document.getElementById('paramDate');
let divParamID = document.getElementById('paramID');
let divParamIDSphere = document.getElementById('paramIDSphere');
let divParamNum = document.getElementById('paramNum');
let divParamActive = document.getElementById('paramActive');
let divParamParent = document.getElementById('paramParent');

let valueDate = document.getElementById('valueDate');
let valueTitle = document.getElementById('valueTitle');

let setActive = document.getElementById('setActive');

let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');
let btnClearSphere = document.getElementById('ClearSphere');
let btnAddCard = document.getElementById('add-card');

let list_sphere = document.getElementById('list_sphere');
let list_card = document.getElementById('list-cards');

let thisData = {
    'id' : 0,
    'date' : 0,
    'id_sphere' : 0,
    'parent' : 0,
    'title' : '',
    'num' : 0,
    'is_active' : 1,
    'cards': []
};

let thisCards = [];
    /*{
    'value1' : '',
    'value2' : '',
    'image1' : 0,
    'image2' : 0,
    'image1_src' : '',
    'image2_src' : '',
};*/

let IsModified = false;
let thisDataBefore = {
    'id' : 0,
    'date' : 0,
    'id_sphere' : 0,
    'title' : '',
    'num' : 0,
    'is_active' : 1,
};

urlFotoLoad = '/edu/foto-card-load';

$(document).ready( function() {
    let strDate = convertTimeStampWithTime(divParamDate.innerText);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);

    valueDate.value = curDate.toISOString().substring(0, 16);
    thisData['date'] = getTimeStampFromElement(valueDate);

    if(divParamID.innerText !== '') {
        thisData['id'] = divParamID.innerText;
        thisData['id_sphere'] = divParamIDSphere.innerText;
        thisData['title'] = valueTitle.value;
        thisData['num'] = divParamNum.innerText;
        thisData['is_active'] = Number(divParamActive.innerText);
        thisData['parent'] = Number(divParamParent.innerText);
    }

    thisDataBefore['date'] = getTimeStampFromElement(valueDate);
    thisDataBefore['id_sphere'] = thisData['id_sphere'];
    thisDataBefore['title'] = thisData['title'];
    thisDataBefore['is_active'] = thisData['is_active'];

    renewActive(true);

    resize();

    loadThisCards();
})

valueTitle.onchange = function(event){
    thisData['title'] = this.value.trim();

    WasModified();
};

btnCancel.onclick = function(e) {

    closeForm()

};

setActive.onchange = function(e) {
    renewActive();

    WasModified();
};

btnSave.onclick = function(e) {
    thisCardsObj = [];
    for(card in thisCards){
        newObj = {
            'id' : thisCards[card]['id'],
            'value1' : thisCards[card]['value1'],
            'value2' : thisCards[card]['value2'],
            'image1' : thisCards[card]['image1'],
            'image2' : thisCards[card]['image2'],
        }
        thisCardsObj.push(newObj);
    }

    thisJSONCards = JSON.stringify(thisCardsObj);

    thisData['cards'] = thisJSONCards;
    runAjax('/edu/card-save', thisData);
};

//Events ---------------------------------------------------------------------------------------------------------------

valueDate.onchange = function(event){
    thisData['date'] = getTimeStampFromElement(this);

    WasModified();
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

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['id_sphere'] = 0;

    WasModified();
};

btnAddCard.onclick = function(e) {
    reshowTable(true);

    WasModified();
};

function afterEditValue(elem) {
    loadThisCards();
}

//Helpers --------------------------------------------------------------------------------------------------------------

function loadThisCards() {
    thisCards = [];
    let thisString = [];
    let isFound = false;

    children = list_card.childNodes;
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

        thisCards[i] = [];
        thisCards[i]['id'] = Number(children[child].id);

        for (column in divRow) {
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameValue1') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'INPUT') {

                                thisCards[i]['value1'] = divRow3[column3].value;
                            }
                        }
                    }
                }
            }
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameValue2') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'INPUT') {

                                thisCards[i]['value2'] = divRow3[column3].value;
                            }
                        }
                    }
                }
            }
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameImage1') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('add-img') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV' & (' ' + divRow3[column3].className + ' ').indexOf('foto-input-string') > -1) {
                                divRow4 = divRow3[column3].childNodes;
                                for (column4 in divRow4) {
                                    if (divRow4[column4].nodeName === 'DIV' & (' ' + divRow4[column4].className + ' ').indexOf('form-group') > -1) {
                                        divRow5 = divRow4[column4].childNodes;
                                        for (column5 in divRow5) {
                                            if (divRow5[column5].nodeName === 'LABEL' & (' ' + divRow5[column5].className + ' ').indexOf('label') > -1) {
                                                divRow6 = divRow5[column5].childNodes;
                                                for (column6 in divRow6) {
                                                    if (divRow6[column6].nodeName === 'DIV') {
                                                        divRow7 = divRow6[column6].childNodes;
                                                        for (column7 in divRow7) {
                                                            if (divRow7[column7].nodeName === 'SPAN' & (' ' + divRow7[column7].className + ' ').indexOf('title') > -1) {

                                                                thisCards[i]['image1'] = 0;
                                                                thisCards[i]['image1_src'] = '';
                                                            } else if (divRow7[column7].nodeName === 'IMG') {

                                                                thisCards[i]['image1'] = Number(divRow7[column7].getAttribute('data-id'));
                                                                thisCards[i]['image1_src'] = divRow7[column7].getAttribute('src');
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            }
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameImage2') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('add-img') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV' & (' ' + divRow3[column3].className + ' ').indexOf('foto-input-string') > -1) {
                                divRow4 = divRow3[column3].childNodes;
                                for (column4 in divRow4) {
                                    if (divRow4[column4].nodeName === 'DIV' & (' ' + divRow4[column4].className + ' ').indexOf('form-group') > -1) {
                                        divRow5 = divRow4[column4].childNodes;
                                        for (column5 in divRow5) {
                                            if (divRow5[column5].nodeName === 'LABEL' & (' ' + divRow5[column5].className + ' ').indexOf('label') > -1) {
                                                divRow6 = divRow5[column5].childNodes;
                                                for (column6 in divRow6) {
                                                    if (divRow6[column6].nodeName === 'DIV') {
                                                        divRow7 = divRow6[column6].childNodes;
                                                        for (column7 in divRow7) {
                                                            if (divRow7[column7].nodeName === 'SPAN' & (' ' + divRow7[column7].className + ' ').indexOf('title') > -1) {

                                                                thisCards[i]['image2'] = 0;
                                                                thisCards[i]['image2_src'] = '';
                                                            } else if (divRow7[column7].nodeName === 'IMG') {

                                                                thisCards[i]['image2'] = Number(divRow7[column7].getAttribute('data-id'));
                                                                thisCards[i]['image2_src'] = divRow7[column7].getAttribute('src');
                                                            }
                                                        }
                                                    }
                                                }
                                            }
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

    console.log(thisCards)
    /*for (str in thisCards) {
        console.log('value1 = ' + thisCards[str]['value1'])
    }*/
}

function renewActive(fromDB = false) {
    if(fromDB == false) {
        thisData['is_active'] = Number(setActive.checked);
    } else {
        if(thisData['is_active'] == 1) {
            setActive.checked = true
        } else {
            setActive.checked = false
        }

    }
}

function WasModified() {
    IsModified = false;

    if ((thisDataBefore['date'] == thisData['date']
        && thisDataBefore['id_sphere'] == thisData['id_sphere']
        && thisDataBefore['title'] == thisData['title']
        && thisDataBefore['is_active'] == thisData['is_active']
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

function render(data) {
    IsModified = false;

    closeForm()
}

function rerender(data) {
    //console.log(data);

    let group = 0;
    let num = 0;

    let id_elem = data.element.id;
    if (id_elem.includes('fotos1_') === true) {
        group = 1;
        num = id_elem.replace('fotos1_', '');
        num = Number(num);
    }
    if (id_elem.includes('fotos2_') === true) {
        group = 2;
        num = id_elem.replace('fotos2_', '');
        num = Number(num);
    }

    //console.log(group);
    //console.log(num);

    if (group === 0 || num === 0) {
        return;
    }

    let el_wrap;
    if(group === 1) {
        el_wrap = document.getElementById('foto-wrap1-'+num);
    } else {
        el_wrap = document.getElementById('foto-wrap2-'+num);
    }
    el_wrap.innerHTML = '';

    if(data.hasOwnProperty('newPath') && data.newPath !== '') {
        let divGroup = document.createElement('div');
        divGroup.className = 'form-group w-50 float-left';

        let labelGroup = document.createElement('label');
        labelGroup.className = 'label p-0 m-0 h-37px';

        let divImg = document.createElement('div');
        divImg.id = 'labelImg'+group+'_'+num;

        let imgFoto = document.createElement('img');
        imgFoto.className = 'img-wrap-33';
        imgFoto.id = 'image'+group+'_'+num;
        imgFoto.setAttribute('src', data.newPath[0].replace('./', '/'));
        imgFoto.setAttribute('data-id', data.newPath[2]);

        let form = document.createElement('form');
        form.id = 'form'+group+'_'+num;
        form.setAttribute('enctype', 'multipart/form-data');
        form.setAttribute('method', 'POST');
        form.setAttribute('action', '');
        form.setAttribute('hidden', 'hidden');

        let inputFile = document.createElement('input');
        inputFile.id = 'fotos'+group+'_'+num;
        inputFile.setAttribute('type', 'file');
        inputFile.setAttribute('onchange', 'handleFiles(this.files, this)');

        let divMesText = document.createElement('div');
        divMesText.className = 'message-text-line-half unactive w-50 p-8px';

        let spanDel = document.createElement('span');
        spanDel.className = 'glyphicon glyphicon-remove symbol_style interactive text-center';
        spanDel.setAttribute('onclick', 'deleteImg'+group+'('+num+')');

        divImg.append(imgFoto);
        form.append(inputFile);
        labelGroup.append(divImg);
        labelGroup.append(form);
        divGroup.append(labelGroup);
        el_wrap.append(divGroup);

        divMesText.append(spanDel);
        el_wrap.append(divMesText);
    }

    loadThisCards();
}

function closeForm() {
    if(thisData['parent'] > 0) {
        groupNum = '/'+ thisData['parent']
    } else {
        groupNum = ''
    }

    if (IsModified === false) {
        window.location.href = '/edu/cards' + groupNum;
    } else {
        let ans = confirm('Не сохранять изменения?');
        if(ans === true) {
            window.location.href = '/edu/cards' + groupNum;
        }
    }
}

function reshowTable(newRow = false, delRow = 0){
    if (newRow == true) {
        newRow = [];
        newRow['value1'] = '';
        newRow['image1'] = 0;
        newRow['value2'] = '';
        newRow['image2'] = 0;
        newRow['id'] = 0;
        thisCards.push(newRow);
    }
    if (delRow > 0) {

    }

    drawTable();
}

function drawTable() {
    list_card.innerHTML = '';

    number = 0;
    for (numRow in thisCards) {
        thisRow = thisCards[numRow];
        number = number + 1;

        let divRow = document.createElement('div');
        divRow.className = 'fin-acc-row interactive-only reg_'+number;
        divRow.id = thisRow.id;

        let divColNum = document.createElement('div');
        divColNum.className = 'column-5 border-1px-bottom col-back-nul colNameNum colResize';
        let divColNumWrap = document.createElement('div');
        divColNumWrap.className = 'message-wrapper-title';
        let divColNumWrapCentr = document.createElement('div');
        divColNumWrapCentr.className = 'message-text-line text-center';
        let divColNumContent = document.createElement('div');
        divColNumContent.innerText = number;

        let divColVal1 = document.createElement('div');
        divColVal1.className = 'column-30 border-1px-bottom col-back-nul colNameValue1 colResize';
        let divColVal1Wrap = document.createElement('div');
        divColVal1Wrap.className = 'wrapper-line';
        let divColVal1Content = document.createElement('input');
        divColVal1Content.className = 'message-text-line';
        divColVal1Content.id = 'value1_'+number;
        divColVal1Content.setAttribute('type', 'text');
        divColVal1Content.setAttribute('contentEditable', 'contentEditable');
        divColVal1Content.setAttribute('onchange', 'afterEditValue(this)');
        divColVal1Content.value = thisRow.value1;

        let divColImg1 = document.createElement('div');
        divColImg1.className = 'column-15 border-1px-all col-back-nul colNameImage1 colResize';
        let divColImg1WrIm = document.createElement('div');
        divColImg1WrIm.className = 'add-img';
        let divColImg1FotoWr = document.createElement('div');
        divColImg1FotoWr.className = 'foto-input-string';
        divColImg1FotoWr.id = 'foto-wrap1-'+number;
        let divColImg1FormGroup;
        let divColImg1LabelImg = document.createElement('div');
        divColImg1LabelImg.id = 'labelImg1_'+number;
        let divColImg1Label;
        let divColImg1Image;

        let divColImg1Form = document.createElement('form');
        divColImg1Form.id = 'form1_'+number;
        divColImg1Form.setAttribute('enctype', 'multipart/form-data');
        divColImg1Form.setAttribute('method', 'POST');
        divColImg1Form.setAttribute('action', '');
        divColImg1Form.setAttribute('hidden', 'hidden');
        let divColImg1Content = document.createElement('input');
        divColImg1Content.id = 'fotos1_'+number;
        divColImg1Content.setAttribute('type', 'file');
        divColImg1Content.setAttribute('onchange', 'handleFiles(this.files, this)');
        let divColImg1WrDel;
        let divColImg1Del;
        if(thisRow.image1 == 0) {
            divColImg1FormGroup = document.createElement('div');
            divColImg1FormGroup.className = 'form-group';

            divColImg1Label = document.createElement('label');
            divColImg1Label.className = 'label p-10px m-0';

            divColImg1Image = document.createElement('span');
            divColImg1Image.className = 'title';
            divColImg1Image.innerText = 'Добавить фото';

            divColImg1WrDel = document.createElement('div');
            divColImg1Del = document.createElement('span');
        } else {
            divColImg1FormGroup = document.createElement('div');
            divColImg1FormGroup.className = 'form-group w-50 float-left';

            divColImg1Label = document.createElement('label');
            divColImg1Label.className = 'label p-0 m-0 h-37px';

            divColImg1Image = document.createElement('img');
            divColImg1Image.className = 'img-wrap-33';
            divColImg1Image.id = 'image1_'+number;
            divColImg1Image.setAttribute('src', thisRow.image1_src);
            divColImg1Image.setAttribute('data-id', thisRow.image1);

            divColImg1WrDel = document.createElement('div');
            divColImg1WrDel.className = 'message-text-line-half unactive w-50 p-8px';

            divColImg1Del = document.createElement('span');
            divColImg1Del.className = 'glyphicon glyphicon-remove symbol_style interactive text-center';
            divColImg1Del.setAttribute('onclick', 'deleteImg1('+number+')');
        }

        //--2

        let divColSep = document.createElement('div');
        divColSep.className = 'column-5 border-1px-bottom col-back-nul colNameSep colResize';
        let divColSepWrap = document.createElement('div');
        divColSepWrap.className = 'message-wrapper-title';
        let divColSepWrap1 = document.createElement('div');
        divColSepWrap1.className = 'message-text-line text-center';
        let divColSepWrap2 = document.createElement('div');
        divColSepWrap2.className = 'message-text-line-half unactive w-100';
        let divColSepDel = document.createElement('span');
        divColSepDel.className = 'glyphicon glyphicon-remove symbol_style interactive text-center';
        divColSepDel.setAttribute('onclick', 'deleteCard('+number+')');

        let divColVal2 = document.createElement('div');
        divColVal2.className = 'column-30 border-1px-bottom col-back-nul colNameValue2 colResize';
        let divColVal2Wrap = document.createElement('div');
        divColVal2Wrap.className = 'wrapper-line';
        let divColVal2Content = document.createElement('input');
        divColVal2Content.className = 'message-text-line';
        divColVal2Content.id = 'value2_'+number;
        divColVal2Content.setAttribute('type', 'text');
        divColVal2Content.setAttribute('contentEditable', 'contentEditable');
        divColVal2Content.setAttribute('onchange', 'afterEditValue(this)');
        divColVal2Content.value = thisRow.value2;

        let divColImg2 = document.createElement('div');
        divColImg2.className = 'column-15 border-1px-all col-back-nul colNameImage2 colResize';
        let divColImg2WrIm = document.createElement('div');
        divColImg2WrIm.className = 'add-img';
        let divColImg2FotoWr = document.createElement('div');
        divColImg2FotoWr.className = 'foto-input-string';
        divColImg2FotoWr.id = 'foto-wrap2-'+number;
        let divColImg2FormGroup;
        let divColImg2Label;
        let divColImg2LabelImg = document.createElement('div');
        divColImg2LabelImg.id = 'labelImg2_'+number;
        let divColImg2Image;

        let divColImg2Form = document.createElement('form');
        divColImg2Form.id = 'form2_'+number;
        divColImg2Form.setAttribute('enctype', 'multipart/form-data');
        divColImg2Form.setAttribute('method', 'POST');
        divColImg2Form.setAttribute('action', '');
        divColImg2Form.setAttribute('hidden', 'hidden');
        let divColImg2Content = document.createElement('input');
        divColImg2Content.id = 'fotos2_'+number;
        divColImg2Content.setAttribute('type', 'file');
        divColImg2Content.setAttribute('onchange', 'handleFiles(this.files, this)');
        let divColImg2WrDel;
        let divColImg2Del;
        if(thisRow.image2 == 0) {
            divColImg2FormGroup = document.createElement('div');
            divColImg2FormGroup.className = 'form-group';

            divColImg2Label = document.createElement('label');
            divColImg2Label.className = 'label p-10px m-0';

            divColImg2Image = document.createElement('span');
            divColImg2Image.className = 'title';
            divColImg2Image.innerText = 'Добавить фото';

            divColImg2WrDel = document.createElement('div');
            divColImg2Del = document.createElement('span');
        } else {
            divColImg2FormGroup = document.createElement('div');
            divColImg2FormGroup.className = 'form-group w-50 float-left';

            divColImg2Label = document.createElement('label');
            divColImg2Label.className = 'label p-0 m-0 h-37px';

            divColImg2Image = document.createElement('img');
            divColImg2Image.className = 'img-wrap-33';
            divColImg2Image.id = 'image2_'+number;
            divColImg2Image.setAttribute('src', thisRow.image2_src);
            divColImg2Image.setAttribute('data-id', thisRow.image2);

            divColImg2WrDel = document.createElement('div');
            divColImg2WrDel.className = 'message-text-line-half unactive w-50 p-8px';

            divColImg2Del = document.createElement('span');
            divColImg2Del.className = 'glyphicon glyphicon-remove symbol_style interactive text-center';
            divColImg2Del.setAttribute('onclick', 'deleteImg2('+number+')');
        }

        divColNumWrapCentr.append(divColNumContent);
        divColNumWrap.append(divColNumWrapCentr);
        divColNum.append(divColNumWrap);
        divRow.append(divColNum);

        divColVal1Wrap.append(divColVal1Content);
        divColVal1.append(divColVal1Wrap);
        divRow.append(divColVal1);

        divColImg1LabelImg.append(divColImg1Image);
        divColImg1Label.append(divColImg1LabelImg);
        divColImg1Form.append(divColImg1Content);
        divColImg1Label.append(divColImg1Form);
        divColImg1FormGroup.append(divColImg1Label);
        divColImg1FotoWr.append(divColImg1FormGroup);
        divColImg1WrDel.append(divColImg1Del);
        divColImg1FotoWr.append(divColImg1WrDel);
        divColImg1WrIm.append(divColImg1FotoWr);
        divColImg1.append(divColImg1WrIm);
        divRow.append(divColImg1);

        divColSepWrap2.append(divColSepDel);
        divColSepWrap1.append(divColSepWrap2);
        divColSepWrap.append(divColSepWrap1);
        divColSep.append(divColSepWrap);
        divRow.append(divColSep);

        divColVal2Wrap.append(divColVal2Content);
        divColVal2.append(divColVal2Wrap);
        divRow.append(divColVal2);

        divColImg2LabelImg.append(divColImg2Image);
        divColImg2Label.append(divColImg2LabelImg);
        divColImg2Form.append(divColImg2Content);
        divColImg2Label.append(divColImg2Form);
        divColImg2FormGroup.append(divColImg2Label);
        divColImg2FotoWr.append(divColImg2FormGroup);
        divColImg2WrDel.append(divColImg2Del);
        divColImg2FotoWr.append(divColImg2WrDel);
        divColImg2WrIm.append(divColImg2FotoWr);
        divColImg2.append(divColImg2WrIm);
        divRow.append(divColImg2);

        list_card.append(divRow);
    }

    resize();
}

function deleteCard(number) {
    index = Number(number);
    removeElemFromThisCards(index);
    drawTable();
}

function removeElemFromThisCards(index) {
    let newthisCards = [];

    number = 0;
    for (numRow in thisCards) {
        thisRow = thisCards[numRow];
        if(number+1 !== index) {
            newthisCards.push(thisRow);
        }

        number = number + 1;
    }

    thisCards = newthisCards;
}

function deleteImg1(number){
    let index = Number(number) - 1;

    removeImage(index, 1);
    drawTable();
}

function deleteImg2(number){
    let index = Number(number) - 1;

    removeImage(index, 2);
    drawTable();
}

function removeImage(index, imageIndex) {
    thisCards[index]['image'+imageIndex] = 0;
    thisCards[index]['image'+imageIndex+'_src'] = '';
}

function restartAjax(){
    thisCardsObj = [];
    for(card in thisCards){
        newObj = {
            'id' : thisCards[card]['id'],
            'value1' : thisCards[card]['value1'],
            'value2' : thisCards[card]['value2'],
            'image1' : thisCards[card]['image1'],
            'image2' : thisCards[card]['image2'],
        }
        thisCardsObj.push(newObj);
    }

    thisJSONCards = JSON.stringify(thisCardsObj);

    thisData['cards'] = thisJSONCards;
    runAjax('/edu/card-save', thisData);
}