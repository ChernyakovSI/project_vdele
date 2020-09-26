<?php
    use yii\helpers\Html;


    $this->title = 'Диалог';
    $this->params['breadcrumbs'][] = $this->title;

    $script = new \yii\web\JsExpression("
        
       var offset = -1; 
       //console.log(offset);
        
            loadMessages = function () {
                var fid_dialog = document.querySelector('#id_dialog');
                var flimit = document.querySelector('#limit');
                offset = offset + 1;
                
                //console.log(offset);
                //console.log(flimit.value);
                
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/dialog-get-messages',
                        // Данные формы
                        data : {
                            'id_dialog' : fid_dialog.value,
                            'limit' : flimit.value,
                            'offset' : offset,
                        }
                    }).done(function(data) {
                            if (data.error == null) {
                                // Если ответ сервера успешно получен
                                //console.log(data);
                                
                                var fPanel = document.querySelector('#w0'); 
                                
                                let TimeNow = new Date();
                                let TextDate = '';
                                let newDay = false;
                                let divItemPrev = 'undefined';
                                let TimePrev = new Date();
                                let curDate = new Date();
                                
                                for (var i = 0; i < data.data.length; i++) {
                                    let divAuthor = document.createElement('div');
                                    divAuthor.className = 'dialog-capture-start';
                                    divAuthor.innerText = data.data[i]['name']; 
                                    
                                    curDate = new Date(parseInt(data.data[i]['created_at'])*1000);
                                    
                                    let divTime = document.createElement('div');
                                    divTime.className = 'dialog-capture-time';
                                    
                                    let qHours = String(curDate.getHours()).length;
                                    let qMinutes = String(curDate.getMinutes()).length;
                                    let qSeconds = String(curDate.getSeconds()).length;
            
                                    let strTime = curDate.getHours();
            
                                    if(qMinutes == 1){
                                        strTime = strTime + ':0' + curDate.getMinutes();
                                    }
                                    else
                                    {
                                         strTime = strTime + ':' + curDate.getMinutes();
                                    }
                                    
                                    if(qSeconds == 1){
                                        strTime = strTime + ':0' + curDate.getSeconds();
                                    }
                                    else
                                    {
                                         strTime = strTime + ':' + curDate.getSeconds();
                                    }
                                    
                                    divTime.innerText = strTime;
                                    
                                    let divCaption = document.createElement('div');
                                    if (data.data[i]['name'] == 'Я:'){
                                        divCaption.className = 'dialog-caption-my';
                                    }
                                    else
                                    {
                                        divCaption.className = 'dialog-caption-caller';
                                    };
                                    divCaption.append(divAuthor);
                                    divCaption.append(divTime);
                                    
                                    let divText = document.createElement('div');
                                    if (data.data[i]['name'] == 'Я:'){
                                        divText.className = 'window-border-0 dialog-my';
                                    }
                                    else
                                    {
                                        divText.className = 'window-border-0 dialog-caller';
                                    }; 
                                    divText.innerText = data.data[i]['text'];
                                    
                                    let divField = document.createElement('div');
                                    divField.className = 'dialog-field';
                                    divField.append(divCaption);
                                    divField.append(divText);
                                    
                                    let divRow = document.createElement('div');
                                    divRow.className = 'row';
                                    divRow.append(divField);
                                    
                                    let divItem = document.createElement('div');
                                    divItem.className = 'dialog-item';
                                    divItem.append(divRow);
                                    divItem.created_at = data.data[i]['created_at'];         
    
                                    if(divItemPrev !== 'undefined'){
                                        TimePrev = new Date(parseInt(divItemPrev.created_at)*1000); 
                                        
                                        if((TimePrev.getFullYear() !== curDate.getFullYear()) || 
                                            (TimePrev.getMonth() !== curDate.getMonth()) || 
                                            (TimePrev.getDate() !== curDate.getDate())){
                                            
                                            TextDate = getTextDate(TimePrev); 
                                            TextDateClass = getTextDateClass(TimePrev);
                                            newDay = true;      
                                        }       
                                    }
                                    
                                    
                                    if (newDay == true){
                                        newDay = false;
                                        
                                        $('.'+TextDateClass).empty();
                                        
                                        let divDateText = document.createElement('div');
                                        divDateText.className = 'dialog-center dialog-date '+TextDateClass;
                                        divDateText.innerText = TextDate;
                                        fPanel.prepend(divDateText);           
                                    }
                                    fPanel.prepend(divItem);
                                    
                                    divItemPrev = divItem;
                                 };
                                 
                                 if(divItemPrev !== 'undefined'){
                                     TimePrev = new Date(parseInt(divItemPrev.created_at)*1000);
                                     TextDate = getTextDate(TimePrev);
                                     TextDateClass = getTextDateClass(TimePrev);
                                     
                                     $('.'+TextDateClass).empty();
                                     let divDateText = document.createElement('div');
                                     divDateText.className = 'dialog-center dialog-date '+TextDateClass;
                                     divDateText.innerText = TextDate;
                                     fPanel.prepend(divDateText);
                                 }
                                 else
                                 {
                                    let divDateText = document.createElement('div');
                                     divDateText.className = 'dialog-center dialog-date';
                                     divDateText.innerText = 'Пока еще нет сообщений';
                                     fPanel.prepend(divDateText);
                                 }
                                 
                                 if (flimit.value > data.data.length) {
                                    var btn_load = document.querySelector('#loadMessages');
                                    btn_load.remove();
                                 };
                                
                                $(\"#output\").text(data.data)
                            } else {
                                // Если при обработке данных на сервере произошла ошибка
                                //console.log(data);
                                $(\"#output\").text(data.error)
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                        $(\"#output\").text(\"error3\");
                    });
                    
                    getTextDate = function(TimePrev) {
                        let curMonth = TimePrev.getMonth();
                        let stringMonth = '';
                        if(curMonth == 0){
                            stringMonth = 'января';
                        }
                        if(curMonth == 1){
                            stringMonth = 'февраля';
                        }
                        if(curMonth == 2){
                            stringMonth = 'марта';
                        }
                        if(curMonth == 3){
                            stringMonth = 'апреля';
                        }
                        if(curMonth == 4){
                            stringMonth = 'мая';
                        }
                        if(curMonth == 5){
                            stringMonth = 'июня';
                        }
                        if(curMonth == 6){
                            stringMonth = 'июля';
                        }
                        if(curMonth == 7){
                            stringMonth = 'августа';
                        }
                        if(curMonth == 8){
                            stringMonth = 'сентября';
                        }
                        if(curMonth == 9){
                            stringMonth = 'октября';
                        }
                        if(curMonth == 10){
                            stringMonth = 'ноября';
                        }
                        if(curMonth == 11){
                            stringMonth = 'декабря';
                        }
                        
                        let TextDate = TimePrev.getDate()+' '+stringMonth+' '+TimePrev.getFullYear()+' г.';
                        
                        return TextDate;
                    }
                    
                    getTextDateClass = function(TimePrev) {
                        let curMonth = TimePrev.getMonth();
                        let stringMonth = '';
                        if(curMonth == 0){
                            stringMonth = '01';
                        }
                        if(curMonth == 1){
                            stringMonth = '02';
                        }
                        if(curMonth == 2){
                            stringMonth = '03';
                        }
                        if(curMonth == 3){
                            stringMonth = '04';
                        }
                        if(curMonth == 4){
                            stringMonth = '05';
                        }
                        if(curMonth == 5){
                            stringMonth = '06';
                        }
                        if(curMonth == 6){
                            stringMonth = '07';
                        }
                        if(curMonth == 7){
                            stringMonth = '08';
                        }
                        if(curMonth == 8){
                            stringMonth = '09';
                        }
                        if(curMonth == 9){
                            stringMonth = '10';
                        }
                        if(curMonth == 10){
                            stringMonth = '11';
                        }
                        if(curMonth == 11){
                            stringMonth = '12';
                        }
                        
                        let TextDate = TimePrev.getDate()+stringMonth+TimePrev.getFullYear();
                        
                        return TextDate;
                    }
            }
            
       loadMessages();        
       
    ");
    $this->registerJs($script, \yii\web\View::POS_READY);

    $this->registerJs(<<<JS
    
    $('#messager').scrollTop($('#messager')[0].scrollHeight);
    
JS
) ?>

<div class="window window-border window-caption">Диалоги</div>

<div class="content">
    <div class="dialog-container-wrap">
        <div class="window window-border dialog-sidebar-left">
        </div>
        <div class="window window-border dialog-header window-subcaption">
            Диалог c <?= $dialog_name ?>
        </div>
        <div class="window-border dialog-main window-gray dialog-scroll" id="messager">
            <div class="dialog-center dialog-button"><a href="#" id="loadMessages" onclick = "loadMessages()" >Показать еще</a></div>
            <?php \yii\widgets\Pjax::begin([
                'timeout' => 3000,
                'enablePushState' => false,
                'linkSelector' => false,
                'formSelector' => '.pjax-form'
            ]) ?>
            <?= $this->render('dialog/_chat', compact('messagesQuery', 'message', 'dialog_id', 'option')) ?>
            <?php \yii\widgets\Pjax::end() ?>
        </div>
        <div class="window window-border dialog-sidebar">
            <div class="dialog-header window-subcaption">
                Диалоги
            </div>

        </div>
        <div class="dialog-footer">

            <?= $this->render('dialog/_control', compact('messagesQuery','message', 'option')) ?>

        </div>

    </div>
</div>

