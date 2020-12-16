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
    $(document).ready( function() {
        let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');
        
        resize();       
    })
");
$this->registerJs($script, \yii\web\View::POS_READY);

$script = new \yii\web\JsExpression("
    let isExpense = " .$isExpense.";
    let isProfit = " .$isProfit.";
    let isReplacement = " .$isReplacement.";
    
    let divRedComment = document.getElementById('red-comment');
    let divWrapError = null;
    
    function resize() {
        let divListRegs = document.getElementById('list-register');  
        let children = divListRegs.childNodes;
        let divRow;
        
        let colDate, colAcc, colCat, colSub, colSum, colCom;

        for(child in children){
            divRow = children[child].childNodes;
            let maxHeight = 0;
            for(column in divRow){
                if (divRow.length > 0){
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-date') > -1) {
                        colDate = divRow[column]; 
                        if (colDate.clientHeight > maxHeight){
                            maxHeight = colDate.clientHeight;
                        } 
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-acc') > -1) {
                        colAcc = divRow[column];
                        if (colAcc.clientHeight > maxHeight){
                            maxHeight = colAcc.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-cat') > -1) {
                        colCat = divRow[column];
                        if (colCat.clientHeight > maxHeight){
                            maxHeight = colCat.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-sub') > -1) {
                        colSub = divRow[column];
                        if (colSub.clientHeight > maxHeight){
                            maxHeight = colSub.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-amount') > -1) {
                        colSum = divRow[column];
                        if (colSum.clientHeight > maxHeight){
                            maxHeight = colSum.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-com') > -1) {
                        colCom = divRow[column];
                        if (colCom.clientHeight > maxHeight){
                            maxHeight = colCom.clientHeight;
                        }
                    }
                }
                
            } 
   
            if(colDate != undefined) {
                colDate.style.height = maxHeight + \"px\";
            }
            if(colAcc != undefined) {
                colAcc.style.height = maxHeight + \"px\";
            }
            if(colCat != undefined) {
                colCat.style.height = maxHeight + \"px\";
            }
            if(colSub != undefined) {
                colSub.style.height = maxHeight + \"px\";
            }
            if(colSum != undefined) {
                colSum.style.height = maxHeight + \"px\";
            }
            if(colCom != undefined) {
                colCom.style.height = maxHeight + \"px\";
            }
              
            colDate = undefined;
            colAcc = undefined;
            colCat = undefined;
            colSub = undefined;
            colSum = undefined;
            colCom = undefined;            
        }  
    }
    
    function showError(data) {
        divRedComment.hidden = false;
        divRedComment.innerHTML = data.error;
           
        if(data['element'] != null) {
            
            divWrapError = document.getElementById('value'+data['element']+'Wrap');
            divWrapError.classList.add('redBorder');
        }                             
    };   
    
    function HideError(data) {
        divRedComment.hidden = true;
        divRedComment.innerHTML = data.error;
           
        if(data['element'] != null) {
            let divWrap = document.getElementById('value'+data['element']+'Wrap');
            divWrap.classList.remove('redBorder');
        }
        else if(divWrapError != null) {
            divWrapError.classList.remove('redBorder');
            divWrapError = null;
        }                             
    };
    
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
        
        readTable();
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
        
        readTable();
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
        
        readTable();
    };
    
    function readTable(){
        value = {
            'isExpense' : isExpense,
            'isProfit' : isProfit,
            'isReplacement' : isReplacement,    
        };
        
        floatingCirclesGMain.hidden = false;
        
        $.ajax({
            type : 'post',
            url : '/fin/register',
            data : value
            }).done(function(data) {
                if (data.error == null) {
                    rerender(data);                       
                } else {
                    if (data.error != ''){
                        console.dir(data);
                    }
                }
                floatingCirclesGMain.hidden = true;
            }).fail(function() {
                floatingCirclesGMain.hidden = true;
            });    
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
                floatingCirclesGMain.hidden = false;
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/reg-add',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                //console.dir(data);
                                deleteForm();
                                rerender(data)                       
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    showError(data);
                                }
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
    
    function editReg(id) {
        showFormNew(id, 0, function(value) {
            if (value != null) {
                floatingCirclesGMain.hidden = false;
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/reg-edit',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                //console.dir(data);
                                deleteForm();
                                rerender(data)                       
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    showError(data);
                                }
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
    
    function deleteReg(thisData) {
                floatingCirclesGMain.hidden = false;
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/reg-delete',
                        // Данные формы
                        data : thisData
                    }).done(function(data) {
                            if (data.error == null) {
                                deleteForm();
                                rerender(data);                       
                            } else {
                                showError(data);
                            }
                            floatingCirclesGMain.hidden = true;
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                        floatingCirclesGMain.hidden = true;
                    });    

    }
    
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
        let valIsExpense = document.getElementById('isExpense');
        let valIsProfit = document.getElementById('isProfit');
        let valIsReplacement = document.getElementById('isReplacement');
        let valueAcc = document.getElementById('valueAcc');
        let valueAccTo = document.getElementById('valueAccTo');
        let valueCat = document.getElementById('valueCat');
        let valueSub = document.getElementById('valueSub');
        let valueCom = document.getElementById('valueCom');
      
        let textAcc = document.getElementById('textAcc');
        let ClearAcc = document.getElementById('ClearAcc');
        let textAccTo = document.getElementById('textAccTo');
        let ClearAccTo = document.getElementById('ClearAccTo');
        let textCat = document.getElementById('textCat');
        let ClearCat = document.getElementById('ClearCat');
        let textSub = document.getElementById('textSub');
        let ClearSub = document.getElementById('ClearSub');
        
        let fieldAccTo = document.getElementById('fieldAccTo');
        let fieldCat = document.getElementById('fieldCat');
        let fieldSub = document.getElementById('fieldSub');
        let fieldAcc = document.getElementById('fieldAcc');
      
        let buttonAdd = document.getElementById('button-add');
        let buttonDel = document.getElementById('button-del'); 
        let floatingCirclesG = document.getElementById('floatingCirclesG');
        
        let fromCaption =  document.getElementById('form-caption');
        
        let nowServer = new Date();
        let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60; 
        nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset); 
        
        divRedComment = document.getElementById('red-comment');
        divRedComment.hidden = true;
        
        let thisData = {
            'id' : 0,
            'date' : 0,
            'type' : 0,
            'AccId' : 0,
            'AccName' : '',
            'CatId' : 0,
            'CatName' : '',
            'SubId' : 0,
            'SubName' : '',
            'Amount' : 0,
            'Com' : '',
            'AccToId' : 0,
            'AccToName' : '',
            'isExpense' : isExpense,
            'isProfit' : isProfit,
            'isReplacement' : isReplacement,
        };
        
        valueAcc.value = '';
        valueAccTo.value = '';
        valueCat.value = '';
        valueSub.value = '';
        valueAmo.innerHTML = '';
        valueCom.innerHTML = '';
        
        textAcc.innerHTML = '';
        textAccTo.innerHTML = '';
        textCat.innerHTML = '';
        textSub.innerHTML = '';
        
        if(id == 0){
            if(type == 0){
                fromCaption.innerHTML = 'Новый расход';
                valIsExpense.checked = true;
                thisData['type'] = 0;
                
                fieldAccTo.hidden = true;
                fieldCat.hidden = false;
                fieldSub.hidden = false;
                
                fieldAcc.innerHTML = 'Счет';
            }
            else if(type == 1){
                fromCaption.innerHTML = 'Новый доход';
                valIsProfit.checked = true;
                thisData['type'] = 1;
                
                fieldAccTo.hidden = true;
                fieldCat.hidden = false;
                fieldSub.hidden = false;
                
                fieldAcc.innerHTML = 'Счет';
            }
            else
            {
                fromCaption.innerHTML = 'Новое перемещение';
                valIsReplacement.checked = true;
                thisData['type'] = 2;
                
                fieldAccTo.hidden = false;
                fieldCat.hidden = true;
                fieldSub.hidden = true;
                
                fieldAcc.innerHTML = 'Со счета';
            }
            
            valueDate.value = nowServer.toISOString().substring(0, 16); 
            let curDate = new Date(valueDate.value); 
            curDate.setHours(curDate.getHours() - currentTimeZoneOffset); 
            thisData['date'] = String(curDate.getTime()).substr(0, 10);                  
                     
            floatingCirclesG.hidden = true;
            buttonDel.hidden = true;
            
            buttonAdd.onclick = function(e) {
                initBtnConfirm();
            };
        }
        else
        {
            buttonDel.hidden = false;
        
            floatingCirclesG.hidden = false;
            fromCaption.innerHTML = 'Редактирование движения';
            
            buttonAdd.onclick = null;
            buttonDel.onclick = null;
            
            thisData['id'] = id;
            
            $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/reg-get',
                        // Данные формы
                        data : {id : id}
                    }).done(function(data) {
                            if (data.error == null) {
                                rerenderListCats(data.categories);
                                rerenderListSubs(data.subs);
                                fullData(data);     
                                                  
                            } else {
                                showError(data);
                            }
                            floatingCirclesG.hidden = true;
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        console.log(data.error);
                        floatingCirclesG.hidden = true;
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
        
        /*valueAmo.onkeydown = function(e) {
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
        };*/    
        
        valueAmo.onchange = function(event) {
            this.value = Number(this.value).toFixed(2);
            
            data = {
                'element': 'Amo',
            };
            HideError(data);
        
            thisData['Amount'] = Number(this.value);
        }
        
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
            
            let strDate = convertTimeStampWithTime(data.data.date);  
            let curDate = new Date(strDate);
            //curDate.setHours(curDate.getHours() + currentTimeZoneOffset); 
            valueDate.value = curDate.toISOString().substring(0, 16);
            thisData['date'] = data.data.date;
            
            valueAcc.value = data.data.AccName;
            thisData['AccId'] = data.data.AccId;
            thisData['AccName'] = data.data.AccName;
            
            if (data.data.type != '2') {
                if (data.data.type == 0) {
                    fromCaption.innerHTML = 'Редактирование расхода';
                    valIsExpense.checked = true;
                    thisData['type'] = 0;
 
                    
                }
                else {
                    fromCaption.innerHTML = 'Редактирование дохода';
                    valIsProfit.checked = true;
                    thisData['type'] = 1; 
                };
                
                valueCat.value = data.data.CatName;
                thisData['CatId'] = data.data.CatId;
                thisData['CatName'] = data.data.CatName;
                    
                valueSub.value = data.data.SubName;
                thisData['SubId'] = data.data.SubId;
                thisData['SubName'] = data.data.SubName;
                 
                fieldAccTo.hidden = true;
                fieldCat.hidden = false;
                fieldSub.hidden = false;
                    
                fieldAcc.innerHTML = 'Счет';      
            }
            else
            {
                fromCaption.innerHTML = 'Редактирование перемещения';
                valIsReplacement.checked = true;
                thisData['type'] = 2;
                
                fieldAccTo.hidden = false;
                fieldCat.hidden = true;
                fieldSub.hidden = true;
                
                fieldAcc.innerHTML = 'Со счета';
                
                valueAccTo.value = data.data.AccToName;
                thisData['AccToId'] = data.data.AccToId;
                thisData['AccToName'] = data.data.AccToName;
            };
            
            valueAmo.value = data.data.Amount;
            thisData['Amount'] = data.data.Amount;
            
            valueCom.innerHTML = data.data.Com;
            thisData['Com'] = data.data.Com;
            
            buttonAdd.onclick = function(e) {
                initBtnConfirm();
            };
            
            buttonDel.onclick = function(e) {
                let ans = confirm('Удалить движение?'); 
                        
                if(ans == true) {
                    deleteReg(thisData);
                }
                                    
                return 1;
            };
        };
        
        function initBtnConfirm() {
            complete(thisData);
        };
        
        valueAcc.onchange = function(event) {
            data = {
                'element': 'Acc',
            };
            HideError(data);
        
            let nameAcc = this.value.trim();
            let idAcc = 0;
        
            let idList = this.getAttribute('list');
            let divList = document.getElementById(idList);
            
            let children = divList.childNodes;

            for(column in children){ 
                if(children[column].nodeName == 'OPTION' & children[column].innerHTML == nameAcc) { 
                    idAcc = children[column].getAttribute('data-id');        
                    break;
                }      
            } 
            
            let value = {
                'name' : nameAcc,
                'id' : idAcc
            };
            if((value['id'] == 0) && (value['name'] != '')){
                textAcc.innerHTML = 'Будет создан новый счет!';
            }
            else if (value['id'] > 0) {
                floatingCirclesG.hidden = false;
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/accounts-get',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                floatingCirclesG.hidden = true;
                                textAcc.innerHTML = '(' + data.data.sum + ')';                           
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    showError(data);
                                }
                                
                                //valueAccWrap.classList.add('redBorder');
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    });    
            } 
            else
            {
                textAcc.innerHTML = '';
            } 
            
            thisData['AccId'] = value['id'];
            thisData['AccName'] = value['name'];   
        };
        
        ClearAcc.onclick = function(e) {
            data = {
                'element': 'Acc',
            };
            HideError(data);
            
            valueAcc.value = '';
            textAcc.innerHTML = '';
            
            thisData['AccId'] = 0;
            thisData['AccName'] = '';
        };
        
        valueAccTo.onchange = function(event) {
            data = {
                'element': 'AccTo',
            };
            HideError(data);
        
            let nameAcc = this.value.trim();
            let idAcc = 0;
        
            let idList = this.getAttribute('list');
            let divList = document.getElementById(idList);
            
            let children = divList.childNodes;

            for(column in children){ 
                if(children[column].nodeName == 'OPTION' & children[column].innerHTML == nameAcc) { 
                    idAcc = children[column].getAttribute('data-id');        
                    break;
                }      
            } 
            
            let value = {
                'name' : nameAcc,
                'id' : idAcc
            };
            if((value['id'] == 0) && (value['name'] != '')){
                textAccTo.innerHTML = 'Будет создан новый счет!';
            }
            else if (value['id'] > 0) {
                floatingCirclesG.hidden = false;
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/accounts-get',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                floatingCirclesG.hidden = true;
                                textAccTo.innerHTML = '(' + data.data.sum + ')';                           
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    showError(data);
                                }
                                
                                //valueAccWrap.classList.add('redBorder');
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    });    
            } 
            else
            {
                textAccTo.innerHTML = '';
            } 
            
            thisData['AccToId'] = value['id'];
            thisData['AccToName'] = value['name'];   
        };
        
        ClearAccTo.onclick = function(e) {
            data = {
                'element': 'AccTo',
            };
            HideError(data);
            
            valueAccTo.value = '';
            textAccTo.innerHTML = '';
            
            thisData['AccToId'] = 0;
            thisData['AccToName'] = '';
        };
        
        valueCat.onchange = function(event) {
            data = {
                'element': 'Cat',
            };
            HideError(data);
            
            let nameCat = this.value.trim();
            let idCat = 0;
        
            let idList = this.getAttribute('list');
            let divList = document.getElementById(idList);
            
            let children = divList.childNodes;

            for(column in children){ 
                if(children[column].nodeName == 'OPTION' & children[column].innerHTML == nameCat) { 
                    idCat = children[column].getAttribute('data-id');        
                    break;
                }      
            } 
            
            let value = {
                'name' : nameCat,
                'id' : idCat
            };
            if((value['id'] == 0) && (value['name'] != '')){
                textCat.innerHTML = 'Будет создана новая категория!';
                rerenderListSubs([]);
                textSub.innerHTML = '';
                valueSub.value = '';
            }
            else if (value['id'] > 0) {
                floatingCirclesG.hidden = false;
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/cat-get',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                floatingCirclesG.hidden = true;
                                textCat.innerHTML = ''; 
                                rerenderListSubs(data.subs);                          
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    showError(data);
                                }
                                
                                //valueAccWrap.classList.add('redBorder');
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    });    
            } 
            else
            {
                textCat.innerHTML = '';
                rerenderListSubs([]);
                textSub.innerHTML = '';
                valueSub.value = '';
            }   
            
            thisData['CatId'] = value['id'];
            thisData['CatName'] = value['name'];
            thisData['SubId'] = 0;
            thisData['SubName'] = ''; 
        };
        
        ClearCat.onclick = function(e) {
            data = {
                'element': 'Cat',
            };
            HideError(data);
            
            valueCat.value = '';
            textCat.innerHTML = '';
            
            valueSub.value = '';
            textSub.innerHTML = '';
            
            thisData['CatId'] = 0;
            thisData['CatName'] = '';
            thisData['SubId'] = 0;
            thisData['SubName'] = '';
            
            rerenderListSubs([]);
        };
        
        valueSub.onchange = function(event) {
            data = {
                'element': 'Sub',
            };
            HideError(data);
            
            let nameSub = this.value.trim();
            let idSub = 0;
        
            let idList = this.getAttribute('list');
            let divList = document.getElementById(idList);
            
            let children = divList.childNodes;

            for(column in children){ 
                if(children[column].nodeName == 'OPTION' & children[column].innerHTML == nameSub) { 
                    idSub = children[column].getAttribute('data-id');        
                    break;
                }      
            } 
            
            if((idSub == 0) && (nameSub != '')){
                textSub.innerHTML = 'Будет создана новая подкатегория!';
            }
            else
            {
                textCat.innerHTML = '';
            }    
            
            thisData['SubId'] = idSub;
            thisData['SubName'] = nameSub;
        };
        
        ClearSub.onclick = function(e) {
            data = {
                'element': 'Sub',
            };
            HideError(data);
            
            valueSub.value = '';
            textSub.innerHTML = '';
            
            thisData['SubId'] = 0;
            thisData['SubName'] = '';
        };
        
        valIsExpense.onchange = function(event) {
            data = { 
            };
            HideError(data);
            
            if(this.checked == true) {
                //if (thisData['type'] == 1){              
                    floatingCirclesG.hidden = false;
                    
                    value = {
                        'isProfit' : 0,
                        'id_category' : 0,
                    };
                    
                    $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/categories',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                floatingCirclesG.hidden = true;
                                textCat.innerHTML = ''; 
                                valueCat.value = '';
                                rerenderListCats(data.categories);                          
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    showError(data);
                                }
                                
                                //valueAccWrap.classList.add('redBorder');
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    }); 
                    
                    rerenderListSubs([]);
                    textSub.innerHTML = ''; 
                    valueSub.value = '';   
                //}
                thisData['type'] = 0;
                if(thisData['id'] == 0) {
                    fromCaption.innerHTML = 'Новый расход';
                }
                else
                {
                    fromCaption.innerHTML = 'Редактирование расход';
                }  
                
                fieldAccTo.hidden = true;
                fieldCat.hidden = false;
                fieldSub.hidden = false; 
                
                fieldAcc.innerHTML = 'Счет';   
            }    
        };
        
        valIsProfit.onchange = function(event) {
            data = { 
            };
            HideError(data);
            if(this.checked == true) {
                //if (thisData['type'] == 0){              
                    floatingCirclesG.hidden = false;
                    
                    value = {
                        'isProfit' : 1,
                        'id_category' : 0,
                    };
                    
                    $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/fin/categories',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
                                floatingCirclesG.hidden = true;
                                textCat.innerHTML = ''; 
                                valueCat.value = '';
                                rerenderListCats(data.categories);                          
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                if (data.error != ''){
                                    showError(data);
                                }
                                
                                //valueAccWrap.classList.add('redBorder');
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    }); 
                    
                    rerenderListSubs([]);
                    textSub.innerHTML = ''; 
                    valueSub.value = '';   
                //}
            
            
                thisData['type'] = 1;
                if(thisData['id'] == 0) {
                    fromCaption.innerHTML = 'Новый доход';
                }
                else
                {
                    fromCaption.innerHTML = 'Редактирование дохода';
                }    
                
                fieldAccTo.hidden = true;
                fieldCat.hidden = false;
                fieldSub.hidden = false;
                
                fieldAcc.innerHTML = 'Счет';  
            }    
        };
        
        valIsReplacement.onchange = function(event) {
            data = { 
            };
            HideError(data);
            if(this.checked == true) {
                thisData['type'] = 2;
                if(thisData['id'] == 0) {
                    fromCaption.innerHTML = 'Новое перемещение';
                }
                else
                {
                    fromCaption.innerHTML = 'Редактирование перемещения';
                }    
                
                fieldAccTo.hidden = false;
                fieldCat.hidden = true;
                fieldSub.hidden = true;  
                
                fieldAcc.innerHTML = 'Со счета';
            }    
        };
        
        valueDate.onchange = function(event){
            let curDate = new Date(this.value); 
            curDate.setHours(curDate.getHours() - currentTimeZoneOffset); 
            thisData['date'] = String(curDate.getTime()).substr(0, 10);
        }
        
        valueCom.onblur = function(event){
            thisData['Com'] = this.innerHTML;
        }

        container.style.display = 'block';
        //form.elements.text.focus();
    }
    
    function rerenderListSubs(dataSet) {
        let listSubs = document.getElementById('list_subcategories'); 
        listSubs.innerHTML = '';
        
        if(dataSet.length > 0){
            dataSet.forEach(function(data, i, arrData){ 
                let divOpt = document.createElement('option');
                divOpt.setAttribute('data-id', data['id']); 
                divOpt.innerHTML = data['name'];          
                                    
                listSubs.append(divOpt);  
            });
        }  
    }
    
    function rerenderListCats(dataSet) {
        let listSubs = document.getElementById('list_categories'); 
        listSubs.innerHTML = '';
        
        if(dataSet.length > 0){
            dataSet.forEach(function(data, i, arrData){ 
                let divOpt = document.createElement('option');
                divOpt.setAttribute('data-id', data['id']); 
                divOpt.innerHTML = data['name'];          
                                    
                listSubs.append(divOpt);  
            });
        }  
    }
    
    function rerender(dataSet) {
        let listReg = document.getElementById('list-register'); 
        listReg.innerHTML = '';
        
        let SumFormat = dataSet.SumFormat;
        
        if(dataSet.data.length > 0){
            dataSet.data.forEach(function(data, i, arrData){ 
                let divRow = document.createElement('div');
                if (data['id_type'] == 0) {
                    divRow.className = 'fin-acc-row expense-back interactive-only';
                } else if(data['id_type'] == 1) {
                    divRow.className = 'fin-acc-row profit-back interactive-only';
                } else {
                    divRow.className = 'fin-acc-row white-back interactive-only';
                } 
                divRow.setAttribute('id', data['id']);
                divRow.addEventListener('dblclick', function() {
                    editReg(data['id']);
                }, false);
                
                let divMainDate = document.createElement('div');
                divMainDate.className = 'fin-reg-date table-text';
                                
                let divWrapDate = document.createElement('div');
                divWrapDate.className = 'message-wrapper-title';
                                
                let divTextDate = document.createElement('div');
                divTextDate.className = 'message-text-line'; 
                
                divTextDate.innerHTML = convertTimeStamp(data['date']);    
                                
                divWrapDate.append(divTextDate);
                divMainDate.append(divWrapDate);
                divRow.append(divMainDate);
                
                
                let divMainAcc = document.createElement('div');
                divMainAcc.className = 'fin-reg-acc table-text';
                                
                let divWrapAcc = document.createElement('div');
                divWrapAcc.className = 'message-wrapper-title';
                                
                let divTextAcc = document.createElement('div');
                divTextAcc.className = 'message-text-line'; 
                divTextAcc.innerHTML = data['AccName'];    
                                
                divWrapAcc.append(divTextAcc);
                divMainAcc.append(divWrapAcc);
                divRow.append(divMainAcc);
                
                let divMainCat = document.createElement('div');
                divMainCat.className = 'fin-reg-cat table-text';
                                    
                let divWrapCat = document.createElement('div');
                divWrapCat.className = 'message-wrapper-title';
                                    
                let divTextCat = document.createElement('div');
                divTextCat.className = 'message-text-line';
                
                if (data['id_type'] != 2) { 
                    divTextCat.innerHTML = data['CatName'];     
                }
                else
                {
                    divTextCat.innerHTML = data['AccToName'];
                }
                
                divWrapCat.append(divTextCat);
                divMainCat.append(divWrapCat);
                divRow.append(divMainCat);
                
                
                let divMainSub = document.createElement('div');
                divMainSub.className = 'fin-reg-sub table-text';
                                    
                let divWrapSub = document.createElement('div');
                divWrapSub.className = 'message-wrapper-title';
                                    
                let divTextSub = document.createElement('div');
                divTextSub.className = 'message-text-line';
                
                if (data['id_type'] != 2) { 
                    divTextSub.innerHTML = data['SubName'];     
                }
                else
                {
                    divTextSub.innerHTML = '';
                }
                
                divWrapSub.append(divTextSub);
                divMainSub.append(divWrapSub);
                divRow.append(divMainSub);
                
                
                let divMainAmount = document.createElement('div');
                divMainAmount.className = 'fin-reg-amount table-text';
                                
                let divWrapAmount = document.createElement('div');
                divWrapAmount.className = 'message-wrapper-title';
                                
                let divTextAmount = document.createElement('div');
                divTextAmount.className = 'message-text-line right-text'; 
                //console.log(SumFormat);
                divTextAmount.innerHTML = SumFormat[data['id']];    
                                
                divWrapAmount.append(divTextAmount);
                divMainAmount.append(divWrapAmount);
                divRow.append(divMainAmount);
                
                let divMainComment = document.createElement('div');
                divMainComment.className = 'fin-reg-com table-text';
                                
                let divWrapComment = document.createElement('div');
                divWrapComment.className = 'message-wrapper-title';
                                
                let divTextComment = document.createElement('div');
                divTextComment.className = 'message-text-line'; 
                divTextComment.innerHTML = data['comment'];    
                                
                divWrapComment.append(divTextComment);
                divMainComment.append(divWrapComment);
                divRow.append(divMainComment);

                let hrLine = document.createElement('hr');
                hrLine.className = 'line';
                                
                let divClear = document.createElement('div');
                divClear.className = 'clearfix';
                                
                divClear.append(hrLine);
                divRow.append(divClear);
                                
                listReg.append(divRow);
            });
                                    
            let divtotal = document.getElementById('total');
            divtotal.innerHTML = dataSet.total;
            
            resize();
        }
        else
        {
            let divInfo = document.createElement('div');
            divInfo.className = 'text-font text-center margin-v20';
            divInfo.setAttribute('id', 'info');
            divInfo.innerHTML = 'Нет движений';
            
            listReg.append(divInfo);
        }
    }
    
    function convertTimeStamp(timestamp) {
          var condate = new Date(timestamp*1000);
          
          return [
            condate.getFullYear(),           // Get day and pad it with zeroes
            ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
            ('0' + condate.getDate()).slice(-2)                          // Get full year
          ].join('.');                                  // Glue the pieces together
    }
    
    function convertTimeStampWithTime(timestamp) {
          var condate = new Date(timestamp*1000);
          
          strDate = [
            condate.getFullYear(),           // Get day and pad it with zeroes
            ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
            ('0' + condate.getDate()).slice(-2)                          // Get full year
          ].join('-');  // Glue the pieces together
                         
          strDate = strDate + 'T';
          
          strTime = [
            ('0' + (condate.getHours())).slice(-2),     
            ('0' + condate.getMinutes()).slice(-2)                         
          ].join(':');              
                                          
          return strDate+strTime;
    }
    
    ");
$this->registerJs($script, \yii\web\View::POS_BEGIN);
?>

<div class="window window-border window-caption-2em caption-wrap">
    <div class="caption-begin"><?='&nbsp;'?></div>
    <div class="caption-text-new">Движения<div><?='&nbsp;'?></div></div>
    <div class="caption-close-new">
        <div id="floatingCirclesGMain" hidden>
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
</div>
<div class="clearfix"></div>


<div class="content">
    <div class="fin-container-wrap">
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
        <div class="window window-border gap-v" id="main-window">
            <!--<div class="window-border">
                <div class="url-categoryList-header window-subcaption">
                    Настройки
                </div>
            </div>-->
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
                    <div class="message-text-line right-text" id="total"><?= Account::formatNumberToMoney($total) ?></div>
                </div>
            </div>
            <div class="fin-reg-com table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>

            <div class="clearfix"><hr class="line"></div>
            <div id="list-register">
                <?php if (count($transactions) == 0){ ?>
                    <div id="info" class="text-font text-center margin-v20">
                        Нет движений
                    </div>
                <?php } else {  foreach ($transactions as $reg): ?>
                    <?php if ($reg['id_type'] == 0){ ?>
                    <div class="fin-acc-row expense-back interactive-only" ondblclick="editReg(<?= $reg['id'] ?>)" id="<?= $reg['id'] ?>">
                    <?php }?>

                     <?php if ($reg['id_type'] == 1){ ?>
                     <div class="fin-acc-row profit-back interactive-only" ondblclick="editReg(<?= $reg['id'] ?>)" id="<?= $reg['id'] ?>">
                     <?php }?>

                     <?php if ($reg['id_type'] == 2){ ?>
                     <div class="fin-acc-row white-back interactive-only" ondblclick="editReg(<?= $reg['id'] ?>)" id="<?= $reg['id'] ?>">
                     <?php }?>

                        <div class="fin-reg-date table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= date("Y.m.d", $reg['date']) ?></div>
                            </div>
                        </div>
                        <div class="fin-reg-acc table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $reg['AccName'] ?></div>
                            </div>
                        </div>
                        <?php if ($reg['id_type'] == 2){ ?>
                            <div class="fin-reg-cat table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= $reg['AccToName'] ?></div>
                                </div>
                            </div>
                            <div class="fin-reg-sub table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= '' ?></div>
                                </div>
                            </div>
                        <?php } else {?>
                            <div class="fin-reg-cat table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= $reg['CatName'] ?></div>
                                </div>
                            </div>
                            <div class="fin-reg-sub table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= $reg['SubName'] ?></div>
                                </div>
                            </div>
                        <?php }?>
                        <div class="fin-reg-amount table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line right-text"><?= Account::formatNumberToMoney($reg['sum']) ?></div>
                            </div>
                        </div>
                        <div class="fin-reg-com table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $reg['comment'] ?></div>
                            </div>
                        </div>
                        <div class="clearfix"><hr class="line"></div>
                    </div>
                <?php endforeach; } ?>
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
                <div class="caption-line-10" id="fieldAcc">Счет:</div><div class="message-wrapper-line-half window-border" id="valueAccWrap">
                    <input type="text" class="message-text-line" list="list_accounts" id="valueAcc" contentEditable />
                    <datalist id="list_accounts">
                        <?php foreach ($accs as $account): ?>
                            <option data-id=<?= $account['id'] ?>><?= $account['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearAcc">х</div>
                <div class="caption-line-left" id="textAcc"></div>
            </div>
            <div class="clearfix"></div>
            <div hidden="hidden" id="fieldAccTo">
                <div class="caption-line-10">На счет:</div><div class="message-wrapper-line-half window-border" id="valueAccToWrap">
                    <input type="text" class="message-text-line" list="list_accountsTo" id="valueAccTo" contentEditable />
                    <datalist id="list_accountsTo">
                        <?php foreach ($accs as $account): ?>
                            <option data-id=<?= $account['id'] ?>><?= $account['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearAccTo">х</div>
                <div class="caption-line-left" id="textAccTo"></div>
            </div>
            <div class="clearfix"></div>
            <div id="fieldCat">
                <div class="caption-line-10">Категория:</div><div class="message-wrapper-line-half window-border" id="valueCatWrap">
                    <input type="text" class="message-text-line" list="list_categories" id="valueCat" contentEditable />
                    <datalist id="list_categories">
                        <?php foreach ($cats as $category): ?>
                            <option data-id=<?= $category['id'] ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearCat">х</div>
                <div class="caption-line-left" id="textCat"></div>
            </div>
            <div class="clearfix"></div>
            <div id="fieldSub">
                <div class="caption-line-10">Подкатегория:</div><div class="message-wrapper-line-half window-border" id="valueSubWrap">
                    <input type="text" class="message-text-line" list="list_subcategories" id="valueSub" contentEditable />
                    <datalist id="list_subcategories">
                        <?php foreach ($subs as $category): ?>
                            <option data-id=<?= $category['id'] ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearSub">х</div>
                <div class="caption-line-left" id="textSub"></div>
            </div>
            <div class="clearfix"></div>
            <div class="half_width">
                <div class="caption-line-half-20">Сумма:</div><div class="message-wrapper-line-half window-border" id="valueAmoWrap">
                    <!--<div class="message-text-line" contentEditable id="valueAmo" >0</div>-->
                    <input type="number" class="message-text-line" id="valueAmo" step="0.01" contentEditable />
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

