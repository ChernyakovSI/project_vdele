<?php

/* @var $this yii\web\View class="site-index"*/
use frontend\assets\AppAsset;
use common\models\fin\Account;

AppAsset::register($this);

$this->title = 'Финансы: Счета';
$this->params['breadcrumbs'][] = $this->title;

$script = new \yii\web\JsExpression("
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

    function showFormNew(callback) {
        showCover();
        let form = document.getElementById('prompt-form');
        let container = document.getElementById('prompt-form-container');
        let btnClose = document.getElementById('btnClose');
        let valueAcc = document.getElementById('valueAcc');
        let valueAmo = document.getElementById('valueAmo');
        let valueCom = document.getElementById('valueCom');
        let buttonAdd = document.getElementById('button-add');
        //document.getElementById('prompt-message').innerHTML = text;
        //form.text.value = '';
        
        let CtrlDown = false;

        function complete(value) {
            hideCover();
            container.style.display = 'none';
            document.onkeydown = null;
            callback(value);
        }

        /*form.onsubmit = function() {
            let value = form.text.value;
            if (value == '') return false; // игнорируем отправку пустой формы

            complete(value);
            return false;
        };

        form.cancel.onclick = function() {
            complete(null);
        };*/

        document.onkeydown = function(e) {
            if (e.key == 'Escape') {
                complete(null);
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
            complete(null);
        };
        
        buttonAdd.onclick = function(e) {
            if(valueAcc.innerHTML.trim() == '') {
                valueAccWrap.classList.add('redBorder');  
                return 0;
            }
        
            let newAccount = {
                'name' : valueAcc.innerHTML,
                'amount' : Number(valueAmo.innerHTML),
                'comment' : valueCom.innerHTML,
            };
            
            valueAcc.innerHTML = 'Новый счет';
            valueAmo.innerHTML = '0';
            valueCom.innerHTML = '';
            
            complete(newAccount);
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

        container.style.display = 'block';
        //form.elements.text.focus();
    }

    document.getElementById('button-new').onclick = function() {
        showFormNew(function(value) {
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
                                console.log(data);
                                
                                let listAccounts = document.getElementById('listAccounts'); 
                                
                                let info = document.getElementById('info');
                                if (info != undefined){
                                    info.remove();
                                };
                                
                                let divRow = document.createElement('div');
                                divRow.className = 'fin-acc-row';
                                
                                let divMainName = document.createElement('div');
                                divMainName.className = 'fin-acc-name table-text';
                                
                                let divWrapName = document.createElement('div');
                                divWrapName.className = 'message-wrapper-title';
                                
                                let divTextName = document.createElement('div');
                                divTextName.className = 'message-text-line'; 
                                divTextName.innerHTML = data.data['name'];    
                                
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
                                divTextPanel.className = 'message-text-line';     
                                
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
                                
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                console.log(data);
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        console.log(data.error);
                    });    
            }
        });
    };
    
    
    
    $(document).ready( function() {
        resize();    
    })
    
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

");
$this->registerJs($script, \yii\web\View::POS_READY);

?>
<div class="window window-border window-caption">Счета</div>

<div class="window window-border" id="content">
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
        </div>
    <?php } else { ?>
    <?php foreach ($accounts as $account): ?>
            <div class="fin-acc-row">
                <div class="fin-acc-name table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line"><?= $account['name'] ?></div>
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
                <div class="fin-acc-panel table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line"></div>
                    </div>
                </div>
                <div class="clearfix"><hr class="line"></div>
            </div>

    <?php endforeach; } ?>
    </div>

    <div class="clearfix"></div>
    <div class="window-button window-border" id="button-new">Добавить</div>

    <div id="prompt-form-container">
        <div id="prompt-form" class="window window-border">
            <div class="caption-wrap">
                <div class="caption-begin">&nbsp;</div>
                <div class="caption-text">Новый счет</div>
                <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
            </div>
            <div>
                <div class="caption-line">Счет:</div><div class="message-wrapper-line window-border" id="valueAccWrap">
                    <div class="message-text-line" contentEditable id="valueAcc" >Новый счет</div>
                </div>
            </div>
            <div>
                <div class="caption-line">Сумма:</div><div class="message-wrapper-line window-border">
                    <div class="message-text-line" contentEditable id="valueAmo" >0</div>
                </div>
            </div>
            <div>
                <div class="caption-line">Комментарий:</div><div class="message-wrapper-line window-border">
                    <div class="message-text-line" contentEditable id="valueCom" ></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="window-button window-border" id="button-add">Добавить</div>
        </div>

    </div>
</div>

