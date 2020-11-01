<?php

/* @var $this yii\web\View class="site-index"*/
use frontend\assets\AppAsset;

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

    function showFormNew(text, callback) {
        showCover();
        let form = document.getElementById('prompt-form');
        let container = document.getElementById('prompt-form-container');
        let btnClose = document.getElementById('btnClose');
        let valueAcc = document.getElementById('valueAcc');
        let valueAmo = document.getElementById('valueAmo');
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
            
            //alert(e.code);
            if(CtrlDown == false){
                if((e.key).length==1){
                    if (Number(this.innerHTML + e.key) != (this.innerHTML + e.key)) {
                        e.preventDefault();
                    }  
                }
            }
            
        };
        
        valueAmo.onkeyup = function(e) {
            if(e.code == 'ControlLeft'){
                CtrlDown = false;
            }   
        };
        
        valueAmo.onblur = function(e) {
            this.innerHTML = isNaN(Number(this.innerHTML)) ? 0 : Number(this.innerHTML);
        };
        
        btnClose.onclick = function(e) {
            complete(null);
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
        showFormNew(\"Введите что-нибудь<br>...умное :)\", function(value) {
            //alert(\"Вы ввели: \" + value);
        });
    };

");
$this->registerJs($script, \yii\web\View::POS_READY);

?>
<div class="window window-border window-caption">Счета</div>

<div class="window window-border" id="content">
    <div class="fin-acc-name table-caption">
        Счет
    </div>
    <div class="fin-acc-amount table-caption">
        Сумма
    </div>
    <div class="fin-acc-comment table-caption">
        Комментарий
    </div>
    <div class="fin-acc-panel table-caption">

    </div>
    <div class="clearfix"><hr class="line"></div>
    <?php if (count($accounts) == 0) { ?>
        <div id="info" class="text-font text-center margin-v20">
            У вас пока нет ни одного счета.
        </div>
    <?php } else { ?>
    <?php foreach ($accounts as $account): ?>

            <div class="fin-acc-name">
                Счет
            </div>
            <div class="fin-acc-amount">
                Сумма
            </div>
            <div class="fin-acc-comment">
                Комментарий
            </div>
            <div class="fin-acc-panel">

            </div>
            <div class="clearfix"><hr class="line"></div>

    <?php endforeach; } ?>

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
                <div class="caption-line">Счет:</div><div class="message-wrapper-line window-border">
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

