<?php
    use yii\helpers\Html;
    use common\models\Image;
    use common\models\User;
    use common\models\Message;


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
                                    
                                    //console.dir(data.data[i]);
                                    
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
                                    
                                    //let divText = document.createElement('div');
                                    //if (data.data[i]['name'] == 'Я:'){
                                    //    divText.className = 'window-border-0 dialog-my';
                                    //}
                                    //else
                                    //{
                                    //    divText.className = 'window-border-0 dialog-caller';
                                    //}; 
                                    //divText.innerText = data.data[i]['text'];
                                    
                                    let divText = document.createElement('div');
                                    if (data.data[i]['name'] == 'Я:'){
                                        let divTxt = document.createElement('div');
                                        divTxt.className = 'window-border-0 dialog-my-txt';
                                        divTxt.innerText = data.data[i]['text'];
                                        
                                        let divPanel = document.createElement('div');
                                        divPanel.className = 'window-border-0 dialog-my-panel';
                                        divPanel.innerHTML = '<span class=\"glyphicon glyphicon-remove symbol_style interactive\" data-tooltip2=\"Удалить\" onclick=\"confirmDelete('+data.data[i]['id']+')\">'; 
                                         
                                        divText.className = 'window-border-0 dialog-my';
                                        divText.append(divTxt);
                                        divText.append(divPanel);
                                    }
                                    else
                                    {
                                        divText.className = 'window-border-0 dialog-caller';
                                        divText.innerText = data.data[i]['text'];
                                    };
                                    
                                    let divField = document.createElement('div');
                                    divField.className = 'dialog-field';
                                    divField.append(divCaption);
                                    divField.append(divText);
                                    
                                    let divRow = document.createElement('div');
                                    divRow.className = 'row';
                                    divRow.append(divField);
                                    
                                    let divItem = document.createElement('div');
                                    if(data.data[i]['is_new'] == true)
                                    {
                                        divItem.className = 'dialog-item dialog-message-new';
                                    }
                                    else
                                    {
                                        divItem.className = 'dialog-item';
                                    }                                 
                                    divItem.append(divRow);
                                    divItem.created_at = data.data[i]['created_at']; 
                                    divItem.setAttribute('id', data.data[i]['id']);        
    
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
                                     
                                     if (flimit.value > data.data.length) {
                                         var btn_load = document.querySelector('#loadMessages');
                                         btn_load.remove();
                                        
                                         var btn_load = document.querySelector('#loadMessages_Wrap');
                                         btn_load.className = 'dialog-center dialog-date';
                                         btn_load.innerText = 'Все сообщения загружены';
                                     };
                                 }
                                 else
                                 {
                                    let divDateText = document.createElement('div');
                                     divDateText.className = 'dialog-center dialog-date';
                                     divDateText.innerText = 'Пока еще нет сообщений';
                                     fPanel.prepend(divDateText);
                                     
                                     if (flimit.value > data.data.length) {
                                         var btn_load = document.querySelector('#loadMessages');
                                         btn_load.remove();
                                     };
                                 }
                                 
                                 
                                 
                                 if(offset == 0){
                                    $('#messager')[0].scrollTop = $('#messager')[0].scrollHeight;
                                 }
                                 
                                
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
                    
                    confirmDelete = function(id) {
                        console.log(id);
                        let ans = confirm(\"Удалить сообщение?\"); 
                        
                        if(ans == true) {
                            deleteText(id);
                        }
                        
                        return 1;
                    }
                    
                    let tooltipElem;

                    document.onmouseover = function(event) {
                      let target = event.target;
                
                      // если у нас есть подсказка...
                      let tooltipHtml = target.dataset.tooltip2;
                      if (!tooltipHtml) return;
                
                      // ...создадим элемент для подсказки
                
                      tooltipElem = document.createElement('div');
                      tooltipElem.className = 'tooltip2';
                      tooltipElem.innerHTML = tooltipHtml;
                      document.body.append(tooltipElem);
                
                      // спозиционируем его сверху от аннотируемого элемента (top-center)
                      let coords = target.getBoundingClientRect();
                
                      let left = coords.left + (target.offsetWidth - tooltipElem.offsetWidth) / 2;
                      if (left < 0) left = 0; // не заезжать за левый край окна
                
                      let top = coords.top - tooltipElem.offsetHeight - 5;
                      if (top < 0) { // если подсказка не помещается сверху, то отображать её снизу
                        top = coords.top + target.offsetHeight + 5;
                      }
                
                      tooltipElem.style.left = left + 'px';
                      tooltipElem.style.top = top + 'px';
                    };
                
                    document.onmouseout = function(e) {
                
                      if (tooltipElem) {
                        tooltipElem.remove();
                        tooltipElem = null;
                      }
                
                    };
                    
                    
            }
            
       loadMessages();     
          
       
    ");
    $this->registerJs($script, \yii\web\View::POS_READY);
 ?>

<div class="window window-border window-caption">Диалоги</div>

<div class="content">
    <div class="dialog-container-wrap">
        <div class="window window-border dialog-sidebar-left">
            <div class="dialog-dialogsList-header window-subcaption">
                Диалоги
            </div>
            <div class="dialog-dialogsList-list dialog-scroll" id="list-dialogs">
                <?php foreach ($usersWithDialogs as $strDU) {
                    $user = User::findIdentity($strDU['id_user']); ?>
                    <a href="/dialog?id=<?= $strDU['id_user'] ?>">
                        <div class="dialog-containerList-wrap <?= ($user->id == $user_id2)?' dialog-list-active':'' ?>">
                            <div class="dialog-list-avatar">
                                <?php
                                $image = new Image();
                                $path_avatar = $image->getPathAvatarForUser($strDU['id_user']);
                                if((isset($path_avatar)) && ($path_avatar != '')) { ?>
                                    <img src=<?= '/data/img/avatar/'.$path_avatar; ?> class="dialogs-avatar_font">
                                <?php }
                                else {
                                    if((isset($user->gender)) && ($user->gender == 2)) { ?>
                                        <img src=<?= '/data/img/avatar/avatar_default_w.jpg'; ?> class="dialogs-avatar_font">
                                    <?php }
                                    else { ?>
                                        <img src=<?= '/data/img/avatar/avatar_default.jpg'; ?> class="dialogs-avatar_font">
                                    <?php }
                                } ?>
                            </div>
                            <div class="dialog-list-name">
                                <?php $QUnreadMessages = Message::GetQuantityOfUnreadMessages(Yii::$app->user->identity->getId(), $user->id); ?>
                                <?= Html::encode("{$user->getFIO($user->id, true)}".(($QUnreadMessages != 0)?(' ('.$QUnreadMessages.')'):(''))) ?>
                            </div>
                        </div>
                    </a>

                <?php } ?>
            </div>
        </div>
        <div class="window window-border dialog-header window-subcaption">
            <?= ($dialog_id == 0?'Выберите диалог слева...':'Диалог c '.$dialog_name) ?>
        </div>
        <div class="window-border dialog-main window-gray dialog-scroll" id="messager">
            <div class="dialog-center dialog-button" id="loadMessages_Wrap"><a href="#" id="loadMessages" onclick = "loadMessages()" >Показать еще</a></div>
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
            <a href="/?id=<?= $user_id2 ?>">
                    <?php $user = User::findIdentity($user_id2); ?>
                    <div class="users-avatar">
                        <?php
                        $image = new Image();
                        $path_avatar = $image->getPathAvatarForUser($user_id2);
                        if((isset($path_avatar)) && ($path_avatar != '')) { ?>
                            <img src=<?= '/data/img/avatar/'.$path_avatar; ?> class="avatar_font">
                        <?php }
                        else {
                            if((isset($user->gender)) && ($user->gender == 2)) { ?>
                                <img src=<?= '/data/img/avatar/avatar_default_w.jpg'; ?> class="avatar_font">
                            <?php }
                            else { ?>
                                <img src=<?= '/data/img/avatar/avatar_default.jpg'; ?> class="avatar_font">
                            <?php }
                        } ?>
                    </div>
            </a>

        </div>
        <div class="dialog-footer">

            <?= $this->render('dialog/_control', compact('messagesQuery','message', 'option', 'dialog_id')) ?>

        </div>

    </div>
</div>

