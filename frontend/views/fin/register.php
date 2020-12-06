<?php
use yii\helpers\Html;
use common\models\fin\Account;

$this->title = 'Финансы: Движения';
//$this->params['breadcrumbs'][] = $this->title;

?>

<div class="submenu">
    <span class="btn-submenu"><a href="/fin/accounts">Счета</a></span>
    <span class="btn-submenu"><a href="/fin/categories">Категории</a></span>
    <span class="btn-submenu btn-active">Движения</span>
    <span class="btn-submenu"><a href="#">Отчеты</a></span>
</div>

<?php
$script = new \yii\web\JsExpression("
    let isExpense = " .$isExpense.";
    let isProfit = " .$isProfit.";
    let isReplacement = " .$isReplacement.";
    
    function expense(){
        let divExpense = document.getElementById('btn-expense');
    
        if(isExpense == 0){
            isExpense = 1;
            divExpense.className = 'btn-submenu btn-active-expense btn-submenu-interactive';
        }
        else
        {
            isExpense = 0;
            divExpense.className = 'btn-submenu btn-submenu-interactive';
        }
    };
    
    function profit(){
        let divProfit = document.getElementById('btn-profit');
    
        if(isProfit == 0){
            isProfit = 1;
            divProfit.className = 'btn-submenu btn-active-profit btn-submenu-interactive';
        }
        else
        {
            isProfit = 0;
            divProfit.className = 'btn-submenu btn-submenu-interactive';
        }
    };
    
    function trans(){
        let divReplacement = document.getElementById('btn-replacement');
    
        if(isReplacement == 0){
            isReplacement = 1;
            divReplacement.className = 'btn-submenu btn-active-replacement btn-submenu-interactive';
        }
        else
        {
            isReplacement = 0;
            divReplacement.className = 'btn-submenu btn-submenu-interactive';
        }
    };
    
    function minType() {
        if(isExpense == 1){
            return 0;
        }
        if(isProfit == 1){
            return 1;
        }
        if(isReplacement == 1){
            return 2;
        }
    }
    
    function addReg() {
        showFormNew(0, minType(), function(value) {
            if (value != null) {
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/reg-add',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                //deleteForm();
                                //confirm(data)                       
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
    
    function showFormNew(id, type, callback) {
        showCover();
        let form = document.getElementById('prompt-form');
        let container = document.getElementById('prompt-form-container');
        let btnClose = document.getElementById('btnClose');
        let valueDate = document.getElementById('valueDate');
        let valueAmo = document.getElementById('valueAmo');
        let valueType = document.getElementById('valueType');
        let valueAcc = document.getElementById('valueType');
        let valueCat = document.getElementById('valueCat');
        let valueSub = document.getElementById('valueSub');
        let valueCom = document.getElementById('valueCom');
      
        let buttonAdd = document.getElementById('button-add');
        let floatingCirclesG = document.getElementById('floatingCirclesG');
        
        let fromCaption =  document.getElementById('form-caption');
        
        let nowServer = new Date();
        let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60; 
        nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset); 
        
        divRedComment = document.getElementById('red-comment');
        divRedComment.hidden = true;
        
        if(id == 0){
            if(type == 0){
                fromCaption.innerHTML = 'Новый расход';
            }
            else if(type == 1){
                fromCaption.innerHTML = 'Новый доход';
            }
            else
            {
                fromCaption.innerHTML = 'Новое перемещение';
            }
            
            valueDate.value = nowServer.toISOString().substring(0, 16);          
                     
            floatingCirclesG.hidden = true;
            
            buttonAdd.onclick = function(e) {
                initBtnConfirm();
            };
        }
        else
        {
            floatingCirclesG.hidden = false;
            if(type == 0){
                fromCaption.innerHTML = 'Редактирование расхода';
            }
            else if(type == 1){
                fromCaption.innerHTML = 'Редактирование дохода';
            }
            else
            {
                fromCaption.innerHTML = 'Редактрование перемещения';
            }
            buttonAdd.onclick = null;
            
            $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/reg-get',
                        // Данные формы
                        data : {id : id}
                    }).done(function(data) {
                            if (data.error == null) {
                                //fullData(data);     
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
            callback(value);
        }
        
        

        document.onkeydown = function(e) {
            if (e.key == 'Escape') {
                complete(null);
                deleteForm();
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
        
        container.ondblclick = function(e) {
            if (event.defaultPrevented) return;
            
            closeFrom();
        };
        
        form.ondblclick = function(e) {
            e.preventDefault();
            return false;
        };
        
        function closeFrom() { 
            complete(null);
            deleteForm();
        }; 
        
        function fullData(data) {
        
            valueDate.innerHTML = data.data.date;
            
            buttonAdd.onclick = function(e) {
                initBtnConfirm();
            };
        };
        
        function initBtnConfirm() {
        
            /*if(valueAcc.innerHTML.trim() == '') {
                valueAccWrap.classList.add('redBorder');  
                return 0;
            }*/
            
            let newReg = {
                    'id' : id,
                    'date' : valueDate.innerHTML,    
            };
        
            complete(newAccount);
        };

        container.style.display = 'block';
        //form.elements.text.focus();
    }
    
    ");
$this->registerJs($script, \yii\web\View::POS_BEGIN);
?>

<div class="window window-border window-caption" id="caption">Движения</div>

<div class="submenu">
    <?php if ($isExpense == 0){ ?>
        <span class="btn-submenu btn-submenu-interactive" id="btn-expense" onclick="expense()">Расходы</span>
    <?php } else { ?>
        <span class="btn-submenu btn-active-expense btn-submenu-interactive" id="btn-expense" onclick="expense()">Расходы</span>
    <?php } ?>
    <?php if ($isProfit == 0){ ?>
        <span class="btn-submenu btn-submenu-interactive" id="btn-profit" onclick="profit()">Доходы</span>
    <?php } else { ?>
        <span class="btn-submenu btn-active-profit btn-submenu-interactive" id="btn-profit" onclick="profit()">Доходы</span>
    <?php } ?>
    <?php if ($isReplacement == 0){ ?>
        <span class="btn-submenu btn-submenu-interactive" id="btn-replacement" onclick="trans()">Перемещения</span>
    <?php } else { ?>
        <span class="btn-submenu btn-active-replacement btn-submenu-interactive" id="btn-replacement" onclick="trans()">Перемещения</span>
    <?php } ?>
</div>

<div class="content">
    <div class="fin-container-wrap">
        <div class="window-border">
            <div class="url-categoryList-header window-subcaption">
                Настройки
            </div>
            <div id="list-categories">



            </div>
        </div>
        <div class="window window-border gap-v" id="main-window">
            <div class="window-button window-border" id="new-reg" onclick="addReg()">Добавить</div>
            <div class="clearfix gap-v"><hr class="line"></div>
            <div class="fin-reg-date table-text">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Дата' ?></div>
                </div>
            </div>
            <div class="fin-reg-acc table-text">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Счет' ?></div>
                </div>
            </div>
            <div class="fin-reg-cat table-text">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Категория' ?></div>
                </div>
            </div>
            <div class="fin-reg-sub table-text">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Подкатегория' ?></div>
                </div>
            </div>
            <div class="fin-reg-amount table-text">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
                </div>
            </div>
            <div class="fin-reg-com table-text">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Примечание' ?></div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="fin-reg-date table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-acc table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-cat table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-sub table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-amount table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line right-text"><?= Account::formatNumberToMoney($total) ?></div>
                </div>
            </div>
            <div class="fin-reg-com table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>

            <div class="clearfix"><hr class="line"></div>
            <div id="list-subcategories">
                <?php if (count($transactions) == 0){ ?>
                    <div id="info" class="text-font text-center margin-v20">
                        Нет движений
                    </div>
                <?php } else { ?>

                <?php } ?>
            </div>
        </div>
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
                <div class="caption-text" id="form-caption">Новое движение</div>
                <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
            </div>
            <div class="clearfix"></div>

            <div class="half_width">
                <div class="caption-line-half-20">Дата:</div><div class="message-wrapper-line-half window-border">
                    <input type="datetime-local" class="message-text-line" contentEditable id="valueDate">
                </div>
            </div>
            <div class="half_width">

                    <div class="radio-container">
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isExpense" checked>
                            <label for="isExpense">Расход</label>
                        </div>
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isProfit">
                            <label for="isProfit">Доход</label>
                        </div>
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isReplacement">
                            <label for="isReplacement">Перемещение</label>
                        </div>
                    </div>

                    <!--<select size="1" class="message-text-line" contentEditable id="valueType">
                        <option selected value="0">Расход</option>
                        <option  value="1">Доход</option>
                        <option value="2">Перемещение</option>
                    </select>-->

            </div>
            <div class="clearfix"></div>
            <div>
                <div class="caption-line-10">Счет:</div><div class="message-wrapper-line window-border" id="valueAccWrap">
                    <input type="text" class="message-text-line" list="list_accounts" id="valueAcc" contentEditable />
                    <datalist id="list_accounts">
                        <?php foreach ($accs as $account): ?>
                            <option data-id=<?= $account['id'] ?>><?= $account['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>

                </div>
            </div>
            <div class="clearfix"></div>
            <div>
                <div class="caption-line-10">Категория:</div><div class="message-wrapper-line window-border" id="valueCatWrap">
                    <input type="text" class="message-text-line" list="list_categories" id="valueCat" contentEditable />
                    <datalist id="list_categories">
                        <?php foreach ($cats as $category): ?>
                            <option data-id=<?= $category['id'] ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
            </div>
            <div class="clearfix"></div>
            <div>
                <div class="caption-line-10">Подкатегория:</div><div class="message-wrapper-line window-border" id="valueSubWrap">
                    <input type="text" class="message-text-line" list="list_subcategories" id="valueSub" contentEditable />
                    <datalist id="list_subcategories">
                        <?php foreach ($subs as $category): ?>
                            <option data-id=<?= $category['id'] ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="half_width">
                <div class="caption-line-half-20">Сумма:</div><div class="message-wrapper-line-half window-border">
                    <div class="message-text-line" contentEditable id="valueAmo" >0</div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div>
                <div class="caption-line-10">Примечание:</div><div class="message-wrapper-line window-border">
                    <div class="message-text-line" contentEditable id="valueCom" ></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="red-comment" id="red-comment"></div>
            <div class="window-button-panel">
                <div class="window-button-in-panel window-border" id="button-add">Подтвердить</div>
                <div class="window-button-in-panel window-border" id="button-del">Удалить</div>
            </div>
        </div>

    </div>
</div>

