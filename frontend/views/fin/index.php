<?php

/* @var $this yii\web\View class="site-index"*/
use frontend\assets\AppAsset;
use common\models\fin\Account;

AppAsset::register($this);

$this->title = 'Финансы: Счета';
$this->params['breadcrumbs'][] = $this->title;

$script = new \yii\web\JsExpression("
    $(document).ready( function() {
        resize();       
    })
");
$this->registerJs($script, \yii\web\View::POS_READY);

$script = new \yii\web\JsExpression("
    let maxNum = " .$maxNum.";
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
                colName.style.height = colComment.clientHeight + \"px\";
            }
            if(colAmount != undefined & colComment != undefined) {
                //console.dir(colAmount.clientHeight);
                colAmount.style.height = colComment.clientHeight + \"px\";
            }
            if(colPanel != undefined & colComment != undefined) {
                //console.dir(colPanel.clientHeight);
                colPanel.style.height = colComment.clientHeight + \"px\";
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
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/accounts-add',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                deleteForm();
                                confirm(data)                       
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
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    });    
            } 
        });
    };
   
    function editAcc(id) {
        showFormNew(id, function(value) {
            if (value != null) {
                console.dir(value);
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
                                confirmEdit(data)                       
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    divRedComment = document.getElementById('red-comment');
                                    divRedComment.hidden = false;
                                    divRedComment.innerHTML = data.error;
                                }
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    });    
            }
        });
    }
    
    // Показать полупрозрачный DIV, чтобы затенить страницу
    // (форма располагается не внутри него, а рядом, потому что она не должна быть полупрозрачной)
    function showCover() {
        let coverDiv = document.createElement('div');
        coverDiv.id = 'cover-div';

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
                                floatingCirclesG.hidden = true;                  
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                console.log(data);
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        console.log(data.error);
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
            
            let newAccount = {
                    'id' : id,
                    'name' : valueAcc.innerHTML,
                    'amount' : Number(valueAmo.innerHTML),
                    'comment' : valueCom.innerHTML,
                    'num' : valueNum.value,
                    'is_deleted' : valueDel.checked,
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
                                divRow.className = 'fin-acc-row white-back';
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
                                divTextAmount.innerHTML = data.total;    
                                
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
            divRow.className = 'fin-acc-row white-back';
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
                                divTextAmount.innerHTML = data['amount'];    
                                
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
        //floatingCirclesG.hidden = false;
        let chkDeleted = document.getElementById('setVisibleDeleted');      
        
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
                    //floatingCirclesG.hidden = true;                  
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    console.log(data);
                }
            }).fail(function() {
                // Если произошла ошибка при отправке запроса
                console.log(data.error);
        });
    };

");
$this->registerJs($script, \yii\web\View::POS_BEGIN);

?>
<div class="window window-border window-caption">Счета</div>

<div class="window window-border" id="content">

    <div class="clearfix"><hr class="line"></div>

    <div class="fin-acc-name table-text">
        <div class="message-wrapper-title">
            <div class="message-text-line table-caption"><?= 'Счет' ?></div>
        </div>
    </div>
    <div class="fin-acc-amount table-text">
        <div class="message-wrapper-title">
            <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
        </div>
    </div>
    <div class="fin-acc-comment table-text">
        <div class="message-wrapper-title">
            <div class="message-text-line table-caption"><?= 'Комментарий' ?></div>
        </div>
    </div>
    <div class="fin-acc-panel table-caption">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= '' ?></div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="fin-acc-name table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= 'Всего:' ?></div>
        </div>
    </div>
    <div class="fin-acc-amount table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line right-text" id="total"><?= Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user)) ?></div>
        </div>
    </div>
    <div class="fin-acc-comment table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= '' ?></div>
        </div>
    </div>
    <div class="fin-acc-panel table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= '' ?></div>
        </div>
    </div>

    <div class="clearfix"><hr class="line"></div>

    <div id="listAccounts">
        <?php if (count($accounts) == 0) { ?>
        <div id="info" class="text-font text-center margin-v20">
            У вас пока нет ни одного счета.
            <br>
            Добавьте несколько, например, "Кошелек", "Карта Тинькофф" и "Резервный Фонд"
        </div>
        <?php } else { ?>
        <?php foreach ($accounts as $account): ?>
            <div class="fin-acc-row white-back" ondblclick="editAcc(<?= $account['id'] ?>)" id="<?= $account['id'] ?>">
                <div class="fin-acc-name table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line"><?= $account['num'] ?>. <?= $account['name'] ?></div>
                    </div>
                </div>
                <div class="fin-acc-amount table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line right-text"><?= Account::formatNumberToMoney($account['amount']) ?></div>
                    </div>
                </div>
                <div class="fin-acc-comment table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line"><?= $account['comment'] ?></div>
                    </div>
                </div>
                <div class="fin-acc-panel">
                    <div class="message-wrapper-title">
                        <div class="message-text-line unactive">
                            <span class="glyphicon glyphicon-pencil symbol_style interactive text-center" onclick="editAcc(<?= $account['id'] ?>)"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"><hr class="line"></div>
            </div>

        <?php endforeach; } ?>
    </div>

    <div class="clearfix"></div>
    <div class="window-button window-border" id="button-new" onclick="addAcc()">Добавить</div>

    <div class="height-3em">
        <input type="checkbox" id="setVisibleDeleted" class="custom-checkbox" onclick="setVisibleDeleted()">
        <label for="setVisibleDeleted">Показать скрытые</label>
    </div>


    <div id="prompt-form-container">
        <div id="prompt-form" class="window window-border">
            <div class="caption-wrap">
                <div class="caption-begin">
                    <div id="floatingCirclesG">
                        <div class="f_circleG" id="frotateG_01"></div>
                        <div class="f_circleG" id="frotateG_02"></div>
                        <div class="f_circleG" id="frotateG_03"></div>
                        <div class="f_circleG" id="frotateG_04"></div>
                        <div class="f_circleG" id="frotateG_05"></div>
                        <div class="f_circleG" id="frotateG_06"></div>
                        <div class="f_circleG" id="frotateG_07"></div>
                        <div class="f_circleG" id="frotateG_08"></div>
                    </div>
                    <div><?='&nbsp;'?></div>
                </div>
                <div class="caption-text" id="form-caption">Новый счет</div>
                <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
            </div>
            <div class="clearfix"></div>

            <div>
                <div class="caption-line">Счет:</div><div class="message-wrapper-line window-border" id="valueAccWrap">
                    <div class="message-text-line" contentEditable id="valueAcc" >Новый счет</div>
                </div>
            </div>
            <div class="half_width">
                <div class="caption-line-half">Начальный остаток:</div><div class="message-wrapper-line-half window-border">
                    <div class="message-text-line" contentEditable id="valueAmo" >0</div>
                </div>
            </div>
            <div class="half_width">
                <div class="caption-line-half">Порядок:</div><div class="message-wrapper-line-half window-border">
                    <input type="number" class="message-text-line" contentEditable id="valueNum" value="1" min="1">
                </div>
            </div>
            <div>
                <div class="caption-line">Комментарий:</div><div class="message-wrapper-line window-border">
                    <div class="message-text-line" contentEditable id="valueCom" ></div>
                </div>
            </div>
            <div>
                <div class="caption-line">
                    <input type="checkbox" id="valueDel" class="custom-checkbox">
                    <label for="valueDel">Скрыть счет</label>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="red-comment" id="red-comment"></div>
            <div class="window-button window-border" id="button-add">Подтвердить</div>
        </div>

    </div>
</div>

