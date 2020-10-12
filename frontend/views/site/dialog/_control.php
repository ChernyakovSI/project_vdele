<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\Message $message
 * @var \yii\db\ActiveQuery $messagesQuery
 */
?>

<?php

$script = new \yii\web\JsExpression("
    
    window.onload = function() {
    
        $('.dialog-item').detach();    

        let socket = new WebSocket('ws://yavdele.local:8080');
               
        socket.onopen = function(event){
            console.log('соединение установлено!');
            
            sendText = function() {  
            
                let ftext = document.querySelector('#message-text');
                let fid_dialog = document.querySelector('#id_dialog');
                let fid_user = document.querySelector('#id_user');
                let fname = document.querySelector('#name');
                
                let text_without_enter = ftext.innerText.replace(/\s/gi, '');
                text_without_enter = text_without_enter.replace(/<br>/gi, '');
                //console.log('text_without_enter 33 '+text_without_enter);
                if(text_without_enter == ''){
                    return;
                }
                
                let message = {
                    text: ftext.innerText,
                    id_dialog: fid_dialog.value,
                    id_user: fid_user.value,
                    name: fname.value,
                };
                
                socket.send(JSON.stringify(message));
            
                ftext.innerText = '';
                
              //console.log(fid_dialog);
              
                $.ajax({
                    // Метод отправки данных (тип запроса)
                    type : 'post',
                    // URL для отправки запроса
                    url : '/dialog-send',
                    // Данные формы
                    data : message
                }).done(function(data) {
                        if (data.error == null) {
                            // Если ответ сервера успешно получен
                            //console.log(data);
                            $(\"#output\").text(data.data.text)
                        } else {
                            // Если при обработке данных на сервере произошла ошибка
                            console.log(data);
                            $(\"#output\").text(data.error)
                        }
                }).fail(function() {
                    // Если произошла ошибка при отправке запроса
                    console.log(data);
                    $(\"#output\").text(\"error3\");
                })
    
            } 
        }
        
        socket.onclose = function(event){
            //var status_value = '';
            //if( event.wasClean) {
            //    status_value = 'соединение закрыто!';
            //}
            //else {
            //    status_value = 'соединение как-то закрыто!';
            //}
            
            //status.value = status_value + ' код: ' + event.code + '; причина: ' + event.reason;
        } 
        
        socket.onmessage = function(event){
            let message = JSON.parse(event.data);
            //status.value = 'Пришли данные: ' + message.text;   
            
            //console.log('Пришли данные: ' + message.text);
            
            let fid_user = document.querySelector('#id_user');
            
            //console.log('Пришли данные: ' + message.name); 
            
            
            
            var fPanel = document.querySelector('#w0'); 
                                
            let divAuthor = document.createElement('div');
            divAuthor.className = 'dialog-capture-start';
            if(fid_user.value == message.id_user)
            {
                divAuthor.innerText = 'Я:';
            }
            else
            {
                divAuthor.innerText = message.name;
            }
                        
            var curDate = new Date();
                                    
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
            if (fid_user.value == message.id_user){
                divCaption.className = 'dialog-caption-my';
            }
            else
            {
                divCaption.className = 'dialog-caption-caller';
            };
            divCaption.append(divAuthor);
            divCaption.append(divTime);
                                    
            let divText = document.createElement('div');
            if (fid_user.value == message.id_user){
                divText.className = 'window-border-0 dialog-my';
            }
            else
            {
                divText.className = 'window-border-0 dialog-caller';
            }; 
            divText.innerText = message.text;
                                    
            let divField = document.createElement('div');
            divField.className = 'dialog-field';
            divField.append(divCaption);
            divField.append(divText);
                                    
            let divRow = document.createElement('div');
            divRow.className = 'row';
            divRow.append(divField);
                                    
            let divItem = document.createElement('div');
            divItem.className = 'dialog-item dialog-message-new';
            divItem.append(divRow);
    
            fPanel.append(divItem);
            $('#messager').scrollTop($('#messager')[0].scrollHeight);
        
        }
        
        socket.onerror = function(event){
           //status.value = 'Ошибка: ' + event.message;
        }   
    }
    
    
");
$this->registerJs($script, \yii\web\View::POS_READY);

$script2 = new \yii\web\JsExpression("
    $('.dialog-item').detach();
        
        $('#message-text').keypress(function(e) {
                //13 maps to the enter key
                //console.log('Ввод');
                var evtobj = window.event ? event : e;
                
                if(evtobj.ctrlKey && evtobj.keyCode == 13)
                {
                    console.log('ctrl');
                    $('#message-text').append('<br>');
                    return;
                //    e.preventDefault();
                }
                else if(e.shiftKey && e.keyCode == 13)
                {
                    //console.log('shift');
                    $('#message-text').append('<br>');
                    return;
                    //e.preventDefault();
                }
                else if (e.keyCode == 13) {
                    sendText();
                    e.preventDefault(); 
                    //console.log('---00---');
                }
            })

    sendText = function() {  
                
                let ftext = document.querySelector('#message-text');
                let fid_dialog = document.querySelector('#id_dialog');
                let fid_user = document.querySelector('#id_user');
                let fname = document.querySelector('#name');
                
                let text_without_enter = ftext.innerText.replace(/\s/gi, '');
                text_without_enter = text_without_enter.replace(/<br>/gi, '');
                //console.log('text_without_enter '+text_without_enter);
                if(text_without_enter == ''){
                    return;
                }
                
                let message = {
                    text: ftext.innerText,
                    id_dialog: fid_dialog.value,
                    id_user: fid_user.value,
                    name: fname.value,
                };   
            
                ftext.innerText = '';
              
                $.ajax({
                    // Метод отправки данных (тип запроса)
                    type : 'post',
                    // URL для отправки запроса
                    url : '/dialog-send',
                    // Данные формы
                    data : message
                }).done(function(data) {
                        if (data.error == null) {
                            var fPanel = document.querySelector('#w0'); 
                                    
                            let divAuthor = document.createElement('div');
                            divAuthor.className = 'dialog-capture-start';
                            if(fid_user.value == message.id_user)
                            {
                                divAuthor.innerText = 'Я:';
                            }
                            else
                            {
                                divAuthor.innerText = message.name;
                            }
                                        
                            var curDate = new Date();
                                                    
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
                            if (fid_user.value == message.id_user){
                                divCaption.className = 'dialog-caption-my';
                            }
                            else
                            {
                                divCaption.className = 'dialog-caption-caller';
                            };
                            divCaption.append(divAuthor);
                            divCaption.append(divTime);
                                                    
                            let divText = document.createElement('div');
                            if (fid_user.value == message.id_user){
                                divText.className = 'window-border-0 dialog-my';
                            }
                            else
                            {
                                divText.className = 'window-border-0 dialog-caller';
                            }; 
                            divText.innerText = message.text;
                                                    
                            let divField = document.createElement('div');
                            divField.className = 'dialog-field';
                            divField.append(divCaption);
                            divField.append(divText);
                                                    
                            let divRow = document.createElement('div');
                            divRow.className = 'row';
                            divRow.append(divField);
                                                    
                            let divItem = document.createElement('div');
                            divItem.className = 'dialog-item dialog-message-new';
                            divItem.append(divRow);
                    
                            fPanel.append(divItem);
                            $('#messager').scrollTop($('#messager')[0].scrollHeight);
                        } else {
                            // Если при обработке данных на сервере произошла ошибка
                            console.log(data);
                            $(\"#output\").text(data.error)
                        }
                }).fail(function() {
                    // Если произошла ошибка при отправке запроса
                    console.log(data);
                    $(\"#output\").text(\"error3\");
                })
    
            }
");
$this->registerJs($script2, \yii\web\View::POS_READY);


/*$script = new \yii\web\JsExpression("
    function handleSubmitButton() {    
        var textInput = $('#message-text')[0]; 
        var hiddenInput = $('#text');
        hiddenInput.val(textInput.innerText);

        textInput.innerText = '';
    };
    
    
");*/
//$this->registerJs($script, \yii\web\View::POS_HEAD);
?>

<?php $form = \yii\widgets\ActiveForm::begin(['options' => ['class' => 'pjax-form']]) ?>

    <div class="chat-wrapper">
        <div <?= ($dialog_id != 0?'class="message-wrapper window-border"':'class="message-wrapper-gray window-border"')?>>
            <div class="message-text" <?= ($dialog_id != 0?'contentEditable':'') ?> id="message-text" ></div>
        </div>
        <div class="message-send">
            <i class="fa fa-play-circle-o" aria-hidden="true" id="sendForm" onclick = "sendText()"></i>
        </div>
        <br/>
    </div>
<?= $form->field($message, 'id_dialog')->hiddenInput(['id' => 'id_dialog'])->label(false) ?>
<?= $form->field($message, 'id_user')->hiddenInput(['id' => 'id_user'])->label(false) ?>
    <input type="hidden" name="name" id="name" value=<?= $option['name']?>>
    <input type="hidden" name="limit" id="limit" value=<?= $option['limit']?>>
<?php \yii\widgets\ActiveForm::end() ?>