//let maxNum = 0;

let gID = 0;

let divRedComment;

function resize() {
    let divListAccounts = document.getElementById('listAccounts');
    let children = divListAccounts.childNodes;
    let divRow;

    let colName, colAmount, colComment, colPanel;

    for(child in children){
        divRow = children[child].childNodes;
        //console.log(divRow);
        for(column in divRow){
            if (divRow.length > 0){
                if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-acc-name') > -1) {
                    colName = divRow[column];
                }
                if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-acc-amount') > -1) {
                    colAmount = divRow[column];
                }
                if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-acc-comment') > -1) {
                    colComment = divRow[column];
                }
                if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-acc-panel') > -1) {
                    colPanel = divRow[column];
                }
            }

        }
        if(colName != undefined & colComment != undefined) {
            //console.dir(colName.clientHeight);
            colName.style.height = colComment.clientHeight + "px";
        }
        if(colAmount != undefined & colComment != undefined) {
            //console.dir(colAmount.clientHeight);
            colAmount.style.height = colComment.clientHeight + "px";
        }
        if(colPanel != undefined & colComment != undefined) {
            //console.dir(colPanel.clientHeight);
            colPanel.style.height = colComment.clientHeight + "px";
        }
        colName = undefined;
        colAmount = undefined;
        colComment = undefined;
        colPanel = undefined;

    }
}

function addAcc() {
    showFormNew(0, function(value) {
        if (value != null) {
            floatingCirclesGMain.hidden = false;
            $.ajax({
                // Метод отправки данных (тип запроса)
                type : 'post',
                // URL для отправки запроса
                url : '/fin/accounts-add',
                // Данные формы
                data : value
            }).done(function(data) {
                if (data.error == null) {
                    //console.log(data)
                    deleteForm();
                    confirm(data);
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    //console.log(data);
                    if (data.error != ''){
                        divRedComment = document.getElementById('red-comment');
                        divRedComment.hidden = false;
                        divRedComment.innerHTML = data.error;
                    }

                    //valueAccWrap.classList.add('redBorder');
                }
                floatingCirclesGMain.hidden = true;
            }).fail(function() {
                // Если произошла ошибка при отправке запроса
                //console.log(data.error);
                floatingCirclesGMain.hidden = true;
            });
        }
    });
};

function recalculateAllAcc() {
    let valueDel = document.getElementById('valueDel');
    value = {
        'is_deleted' : valueDel.checked,
        'calc' : 1,
    };
    floatingCirclesGMain.hidden = false;

    $.ajax({
        type : 'post',
        url : '/fin/accounts-get-all',
        data : value
    }).done(function(data) {
        if (data.error == null) {
            rerenderTable(data);
        } else {
            if (data.error != ''){
                divRedComment = document.getElementById('red-comment');
                divRedComment.hidden = false;
                divRedComment.innerHTML = data.error;
            }
        };
        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        floatingCirclesGMain.hidden = true;
    });
};

function editAcc(id) {
    showFormNew(id, function(value) {
        floatingCirclesGMain.hidden = false;
        if (value != null) {
            $.ajax({
                // Метод отправки данных (тип запроса)
                type : 'post',
                // URL для отправки запроса
                url : '/fin/accounts-edit',
                // Данные формы
                data : value
            }).done(function(data) {
                if (data.error == null) {
                    deleteForm();
                    confirmEdit(data);
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    //console.log(data);
                    if (data.error != ''){
                        divRedComment = document.getElementById('red-comment');
                        divRedComment.hidden = false;
                        divRedComment.innerHTML = data.error;
                    }
                };
                floatingCirclesGMain.hidden = true;
            }).fail(function() {
                // Если произошла ошибка при отправке запроса
                //console.log(data.error);
                floatingCirclesGMain.hidden = true;
            });
        }
    });
}

// Показать полупрозрачный DIV, чтобы затенить страницу
// (форма располагается не внутри него, а рядом, потому что она не должна быть полупрозрачной)
function showCover() {
    let coverDiv = document.createElement('div');
    coverDiv.id = 'cover-div';

    //let generalDiv = document.getElementById('general');

    let container = document.getElementById('prompt-form-container');
    container.style.display = 'block';

    // убираем возможность прокрутки страницы во время показа модального окна с формой
    document.body.style.overflowY = 'hidden';

    document.body.append(coverDiv);
}

function hideCover() {
    document.getElementById('cover-div').remove();
    document.body.style.overflowY = '';
}

function deleteForm(){
    let container = document.getElementById('prompt-form-container');

    hideCover();
    container.style.display = 'none';
    document.onkeydown = null;
}


function showFormNew(id, callback) {
    gID = id;
    showCover();
    let form = document.getElementById('prompt-form');

    form.classList.remove('form-off');
    form.classList.add('form-on');

    let container = document.getElementById('prompt-form-container');
    let btnClose = document.getElementById('btnClose');
    let valueAcc = document.getElementById('valueAcc');
    let valueAmo = document.getElementById('valueAmo');
    let valueCom = document.getElementById('valueCom');
    let valueDel = document.getElementById('valueDel');
    let buttonAdd = document.getElementById('button-add');
    let floatingCirclesG = document.getElementById('floatingCirclesG');
    let valueNum = document.getElementById('valueNum');

    let divContainer = document.getElementById('prompt-form-container');
    //document.getElementById('prompt-message').innerHTML = text;
    //form.text.value = '';

    let fromCaption =  document.getElementById('form-caption');

    divRedComment = document.getElementById('red-comment');
    divRedComment.hidden = true;

    if(id == 0){
        fromCaption.innerHTML = 'Новый счет';

        valueAcc.innerHTML = 'Новый счет';
        valueAmo.innerHTML = '0';
        valueCom.innerHTML = '';
        valueDel.checked = false;
        maxNum = Number(maxNum) + 1;
        //console.log(maxNum);
        valueNum.value = maxNum;


        floatingCirclesG.hidden = true;

        buttonAdd.onclick = function(e) {
            initBtnConfirm();
        };
    }
    else
    {
        floatingCirclesG.hidden = false;
        fromCaption.innerHTML = 'Редактирование счета';
        buttonAdd.onclick = null;

        $.ajax({
            // Метод отправки данных (тип запроса)
            type : 'post',
            // URL для отправки запроса
            url : '/fin/accounts-get',
            // Данные формы
            data : {id : id}
        }).done(function(data) {
            if (data.error == null) {
                fullData(data);
            } else {
                // Если при обработке данных на сервере произошла ошибка
                console.log(data);
            };
            floatingCirclesG.hidden = true;
        }).fail(function() {
            // Если произошла ошибка при отправке запроса
            //console.log(data.error);
            floatingCirclesG.hidden = true;
        });
    }

    let CtrlDown = false;

    function complete(value) {
        if(value !== null){
            //console.log('val = ', value);
            maxNum = Number(value.num)>maxNum?Number(value.num):maxNum;
        }

        callback(value);
    }



    document.onkeydown = function(e) {
        if (e.key == 'Escape') {
            complete(null);
            deleteForm();
        }
    };

    valueAcc.onkeydown = function(e) {
        if (e.key == 'Enter') {
            e.preventDefault();
        }
    };

    valueAmo.onkeydown = function(e) {
        if (e.key == 'Enter') {
            e.preventDefault();
        }

        if(e.code == 'ControlLeft'){
            CtrlDown = true;
        }

        if(e.key != ','){
            if(CtrlDown == false){
                if((e.key).length==1){
                    newString = this.innerHTML.replace(/,/, '.');
                    if (Number(newString + e.key) != (newString + e.key)) {
                        e.preventDefault();
                    }
                }
            }
        }
        else
        {
            newString = this.innerHTML.replace(/,/, '.');
            arrNum = newString.split('.');
            if(arrNum.length > 1){
                e.preventDefault();
            }
        }
    };

    valueAmo.onkeyup = function(e) {
        if(e.code == 'ControlLeft'){
            CtrlDown = false;
        }
    };

    valueAmo.onblur = function(e) {
        this.innerHTML = this.innerHTML.replace(/,/, '.');
        this.innerHTML = isNaN(Number(this.innerHTML)) ? 0 : Number(this.innerHTML);
    };

    btnClose.onclick = function(e) {
        closeFrom();
    };

    divContainer.ondblclick = function(e) {
        if (event.defaultPrevented) return;

        closeFrom();
    };

    form.ondblclick = function(e) {
        e.preventDefault();
        return false;
    };

    function closeFrom() {
        if(gID == 0){
            maxNum = Number(maxNum) - 1;
        }
        complete(null);
        deleteForm();
    };


    /*let lastElem = form.elements[form.elements.length - 1];
    let firstElem = form.elements[0];

    lastElem.onkeydown = function(e) {
        if (e.key == 'Tab' && !e.shiftKey) {
            firstElem.focus();
            return false;
        }
    };

    firstElem.onkeydown = function(e) {
        if (e.key == 'Tab' && e.shiftKey) {
            lastElem.focus();
            return false;
        }
    };*/

    function fullData(data) {

        valueAcc.innerHTML = data.data.name;
        valueAmo.innerHTML = data.data.amount;
        valueCom.innerHTML = data.data.comment;
        valueNum.value = data.data.num;
        valueDel.checked = data.data.is_deleted;

        buttonAdd.onclick = function(e) {
            initBtnConfirm();
        };
    };

    function initBtnConfirm() {

        if(valueAcc.innerHTML.trim() == '') {
            valueAccWrap.classList.add('redBorder');
            return 0;
        }

        let chkDeleted = document.getElementById('setVisibleDeleted');

        let newAccount = {
            'id' : id,
            'name' : valueAcc.innerHTML,
            'amount' : Number(valueAmo.innerHTML),
            'comment' : valueCom.innerHTML,
            'num' : valueNum.value,
            'is_deleted' : valueDel.checked,
            'option-deleted' : chkDeleted.checked,
        };

        complete(newAccount);
    };

    container.style.display = 'block';
    //form.elements.text.focus();
}

function confirm(data) {

    if(data.changedNumId > 0)
    {
        rerenderTable(data);
        return;
    };

    let listAccounts = document.getElementById('listAccounts');

    let info = document.getElementById('info');
    if (info != undefined){
        info.remove();
    };

    let divRow = document.createElement('div');
    divRow.className = 'fin-acc-row white-back interactive-only';
    divRow.setAttribute('id', data.data.id);
    divRow.addEventListener('dblclick', function() {
        editAcc(data.data.id);
    }, false);

    let divMainName = document.createElement('div');
    divMainName.className = 'fin-acc-name table-text';

    let divWrapName = document.createElement('div');
    divWrapName.className = 'message-wrapper-title';

    let divTextName = document.createElement('div');
    divTextName.className = 'message-text-line';
    divTextName.innerHTML = data.data['num'] + '. ' + data.data['name'];

    divWrapName.append(divTextName);
    divMainName.append(divWrapName);
    divRow.append(divMainName);


    let divMainAmount = document.createElement('div');
    divMainAmount.className = 'fin-acc-amount table-text';

    let divWrapAmount = document.createElement('div');
    divWrapAmount.className = 'message-wrapper-title';

    let divTextAmount = document.createElement('div');
    divTextAmount.className = 'message-text-line right-text';
    divTextAmount.innerHTML = data.sum;

    divWrapAmount.append(divTextAmount);
    divMainAmount.append(divWrapAmount);
    divRow.append(divMainAmount);


    let divMainComment = document.createElement('div');
    divMainComment.className = 'fin-acc-comment table-text';

    let divWrapComment = document.createElement('div');
    divWrapComment.className = 'message-wrapper-title';

    let divTextComment = document.createElement('div');
    divTextComment.className = 'message-text-line';
    divTextComment.innerHTML = data.data['comment'];

    divWrapComment.append(divTextComment);
    divMainComment.append(divWrapComment);
    divRow.append(divMainComment);


    let divPanel = document.createElement('div');
    divPanel.className = 'fin-acc-panel table-text';

    let divWrapPanel = document.createElement('div');
    divWrapPanel.className = 'message-wrapper-title';

    let divTextPanel = document.createElement('div');
    divTextPanel.className = 'message-text-line unactive';

    let spanEdit = document.createElement('span');
    spanEdit.className = 'glyphicon glyphicon-pencil symbol_style interactive text-center';
    //spanEdit.onclick = editAcc;
    spanEdit.addEventListener('click', function() {
        editAcc(data.data.id);
    }, false);

    divTextPanel.append(spanEdit);
    divWrapPanel.append(divTextPanel);
    divPanel.append(divWrapPanel);
    divRow.append(divPanel);

    let hrLine = document.createElement('hr');
    hrLine.className = 'line';

    let divClear = document.createElement('div');
    divClear.className = 'clearfix';

    divClear.append(hrLine);
    divRow.append(divClear);

    listAccounts.append(divRow);

    let divtotal = document.getElementById('total');
    divtotal.innerHTML = data.totalAllAccounts;

    resize();
}

function confirmEdit(data) {
    if(data.changedNumId > 0)
    {
        rerenderTable(data);
        return;
    };

    let divRow = document.getElementById(data.data.id);

    let children = divRow.childNodes;

    for(column in children){


        if(children[column].nodeName == 'DIV' & (' ' + children[column].className + ' ').indexOf('fin-acc-name') > -1) {
            for(wrap in children[column].childNodes){
                if(children[column].childNodes[wrap].nodeName == 'DIV') {
                    for(element in children[column].childNodes[wrap].childNodes){
                        if(children[column].childNodes[wrap].childNodes[element].nodeName == 'DIV') {
                            children[column].childNodes[wrap].childNodes[element].innerHTML = data.data.num + '. ' + data.data.name;
                        }
                    }
                }
            }
        }
        if(children[column].nodeName == 'DIV' & (' ' + children[column].className + ' ').indexOf('fin-acc-amount') > -1) {
            for(wrap in children[column].childNodes){
                if(children[column].childNodes[wrap].nodeName == 'DIV') {
                    for(element in children[column].childNodes[wrap].childNodes){
                        if(children[column].childNodes[wrap].childNodes[element].nodeName == 'DIV') {
                            children[column].childNodes[wrap].childNodes[element].innerHTML = data.total;
                        }
                    }
                }
            }
        }
        if(children[column].nodeName == 'DIV' & (' ' + children[column].className + ' ').indexOf('fin-acc-comment') > -1) {
            for(wrap in children[column].childNodes){
                if(children[column].childNodes[wrap].nodeName == 'DIV') {
                    for(element in children[column].childNodes[wrap].childNodes){
                        if(children[column].childNodes[wrap].childNodes[element].nodeName == 'DIV') {
                            children[column].childNodes[wrap].childNodes[element].innerHTML = data.data.comment;
                        }
                    }
                }
            }
        }

    }

    let divtotal = document.getElementById('total');
    divtotal.innerHTML = data.totalAllAccounts;
}

function rerenderTable(dataSet) {
    let listAccounts = document.getElementById('listAccounts');
    listAccounts.innerHTML = '';

    dataSet.data.forEach(function(data, i, arrData){
        let divRow = document.createElement('div');
        if (data['is_deleted'] == 1) {
            divRow.className = 'fin-acc-row rose-back interactive-only';
        } else {
            divRow.className = 'fin-acc-row white-back interactive-only';
        }
        divRow.setAttribute('id', data['id']);
        divRow.addEventListener('dblclick', function() {
            editAcc(data['id']);
        }, false);

        let divMainName = document.createElement('div');
        divMainName.className = 'fin-acc-name table-text';

        let divWrapName = document.createElement('div');
        divWrapName.className = 'message-wrapper-title';

        let divTextName = document.createElement('div');
        divTextName.className = 'message-text-line';
        divTextName.innerHTML = data['num'] + '. ' + data['name'];

        divWrapName.append(divTextName);
        divMainName.append(divWrapName);
        divRow.append(divMainName);


        let divMainAmount = document.createElement('div');
        divMainAmount.className = 'fin-acc-amount table-text';

        let divWrapAmount = document.createElement('div');
        divWrapAmount.className = 'message-wrapper-title';

        let divTextAmount = document.createElement('div');
        divTextAmount.className = 'message-text-line right-text';
        divTextAmount.innerHTML = data['sum'];

        divWrapAmount.append(divTextAmount);
        divMainAmount.append(divWrapAmount);
        divRow.append(divMainAmount);


        let divMainComment = document.createElement('div');
        divMainComment.className = 'fin-acc-comment table-text';

        let divWrapComment = document.createElement('div');
        divWrapComment.className = 'message-wrapper-title';

        let divTextComment = document.createElement('div');
        divTextComment.className = 'message-text-line';
        divTextComment.innerHTML = data['comment'];

        divWrapComment.append(divTextComment);
        divMainComment.append(divWrapComment);
        divRow.append(divMainComment);


        let divPanel = document.createElement('div');
        divPanel.className = 'fin-acc-panel table-text';

        let divWrapPanel = document.createElement('div');
        divWrapPanel.className = 'message-wrapper-title';

        let divTextPanel = document.createElement('div');
        divTextPanel.className = 'message-text-line unactive';

        let spanEdit = document.createElement('span');
        spanEdit.className = 'glyphicon glyphicon-pencil symbol_style interactive text-center';
        //spanEdit.onclick = editAcc;
        spanEdit.addEventListener('click', function() {
            editAcc(data['id']);
        }, false);

        divTextPanel.append(spanEdit);
        divWrapPanel.append(divTextPanel);
        divPanel.append(divWrapPanel);
        divRow.append(divPanel);

        let hrLine = document.createElement('hr');
        hrLine.className = 'line';

        let divClear = document.createElement('div');
        divClear.className = 'clearfix';

        divClear.append(hrLine);
        divRow.append(divClear);

        listAccounts.append(divRow);
    });

    let divtotal = document.getElementById('total');
    divtotal.innerHTML = dataSet.totalAllAccounts;

    resize();
}

function setVisibleDeleted(e) {
    let chkDeleted = document.getElementById('setVisibleDeleted');
    floatingCirclesGMain.hidden = false;

    $.ajax({
        // Метод отправки данных (тип запроса)
        type : 'post',
        // URL для отправки запроса
        url : '/fin/accounts-get-all',
        // Данные формы
        data : {is_deleted : chkDeleted.checked}
    }).done(function(data) {
        if (data.error == null) {
            rerenderTable(data);
        } else {
            // Если при обработке данных на сервере произошла ошибка
            console.log(data);
        }
        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        // Если произошла ошибка при отправке запроса
        //console.log(data.error);
        floatingCirclesGMain.hidden = true;
    });
};