<?php
use yii\helpers\Html;

$this->title = 'Веб-ссылки';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs($script, \yii\web\View::POS_READY);

$script = new \yii\web\JsExpression("
    let maxNum = " .$maxNumInCategory.";
    let id_category = " .$id_category.";
    let gID = 0;
    
    let divRedComment; 

    function addUrl() {
        showFormNew(0, function(value) {
            if (value != null) {
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/url/url-add',
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
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    });    
            } 
        });
    };
    
    function editUrl(id) {
        showFormNew(id, function(value) {
            if (value != null) {
                $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/url/url-edit',
                        // Данные формы
                        data : value
                    }).done(function(data) {
                            if (data.error == null) {
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
                            }
                    }).fail(function() {
                        // Если произошла ошибка при отправке запроса
                        //console.log(data.error);
                    });    
            }
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
    
    function showFormNew(id, callback) {
        gID = id;
        showCover();
        let form = document.getElementById('prompt-form');
        let container = document.getElementById('prompt-form-container');
        let btnClose = document.getElementById('btnClose');
        let valueUrl = document.getElementById('valueUrl');
        let valueName = document.getElementById('valueName');
        let valueCom = document.getElementById('valueCom');
        let buttonAdd = document.getElementById('button-add');
        let floatingCirclesG = document.getElementById('floatingCirclesG');
        let valueNum = document.getElementById('valueNum');
        
        let divContainer = document.getElementById('prompt-form-container'); 
        
        let fromCaption =  document.getElementById('form-caption');
        
        divRedComment = document.getElementById('red-comment');
        divRedComment.hidden = true;
        
        if(id == 0){
            fromCaption.innerHTML = 'Новая веб-ссылка';
            
            valueUrl.innerHTML = '';
            valueCom.innerHTML = '';
            valueName.innerHTML = '';
            maxNum = Number(maxNum) + 1;
            valueNum.value = maxNum;
            
            
            floatingCirclesG.hidden = true;
            
            buttonAdd.onclick = function(e) {
                initBtnConfirm();
            };
        }
        else
        {
            floatingCirclesG.hidden = false;
            fromCaption.innerHTML = 'Редактирование веб-ссылки';
            buttonAdd.onclick = null;
            
            $.ajax({
                        // Метод отправки данных (тип запроса)
                        type : 'post',
                        // URL для отправки запроса
                        url : '/url/url-get',
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

        function complete(value) {
            if(value !== null){
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
        
        valueUrl.onkeydown = function(e) {
            if (e.key == 'Enter') {
                e.preventDefault();
            }
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
        
        function fullData(data) {
        
            valueUrl.innerHTML = data.data.url;
            valueName.innerHTML = data.data.name;
            valueCom.innerHTML = data.data.comment;
            valueNum.value = data.data.num;
            
            buttonAdd.onclick = function(e) {
                initBtnConfirm();
            };
        };
        
        function initBtnConfirm() {
        
            if(valueUrl.innerHTML.trim() == '') {
                valueUrlWrap.classList.add('redBorder');  
                return 0;
            }      
            
            let newUrl = {
                'id' : id,
                'url' : valueUrl.innerHTML,
                'name' : valueName.innerHTML,
                'comment' : valueCom.innerHTML,
                'num' : valueNum.value,
                'id_category': id_category,
            };
        
            complete(newUrl);
        };

        container.style.display = 'block';
        //form.elements.text.focus();
    }
    
    function confirm(data) {
        maxNum = data.maxNumInCategory;
    
        rerenderTable(data);
    }
    
    function rerenderTable(dataSet) {
        let listUrls = document.getElementById('list-urls'); 
        listUrls.innerHTML = '';
        
        if(dataSet.data.length > 0){
            let hrLine = document.createElement('hr');
            hrLine.className = 'line';
                                    
            let divClear = document.createElement('div');
            divClear.className = 'clearfix';
                                    
            divClear.append(hrLine);
                                    
            listUrls.append(divClear); 
         
            dataSet.data.forEach(function(data, i, arrData){ 
                let divRow = document.createElement('div');
                divRow.className = 'fin-acc-row white-back interactive-only';
                divRow.setAttribute('id', data['id']);
                          
                let aUrl = document.createElement('a');       
                aUrl.setAttribute('href', data['url']);  
                aUrl.setAttribute('target', '_blank');  
                                    
                let divMainName = document.createElement('div');
                divMainName.className = 'url-col-name table-text';
                                    
                let divWrapName = document.createElement('div');
                divWrapName.className = 'message-wrapper-title';
                                    
                let divTextName = document.createElement('div');
                divTextName.className = 'message-text-line'; 
                divTextName.innerHTML = data['num'] + '. ' + data['name'];    
                                    
                divWrapName.append(divTextName);
                divMainName.append(divWrapName);
                aUrl.append(divMainName);
                divRow.append(aUrl);
                                    
                let divPanel = document.createElement('div');
                divPanel.className = 'url-col-panel';     
                divRow.addEventListener('dblclick', function() {
                    editUrl(data['id']);
                }, false);   
                                    
                let divWrapPanel = document.createElement('div');
                divWrapPanel.className = 'message-wrapper-title';
                                    
                let divTextPanel = document.createElement('div');
                divTextPanel.className = 'message-text-line unactive'; 
                                    
                let spanEdit = document.createElement('span');
                spanEdit.className = 'glyphicon glyphicon-pencil symbol_style interactive text-center';   
                spanEdit.addEventListener('click', function() {
                    editUrl(data['id']);
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
                                    
                listUrls.append(divRow);
            });
        };
    }
");
$this->registerJs($script, \yii\web\View::POS_BEGIN);
?>

<div class="window window-border window-caption">Веб-ссылки</div>

<div class="content">
    <div class="url-container-wrap">
        <div class="url-window-right window-border url-sidebar-left">
            <div class="url-categoryList-header window-subcaption">
                Категории
            </div>
            <div class="clearfix"><hr class="line"></div>
            <div class="fin-acc-row white-back interactive-only" onclick="fullUrl(0)" ondblclick="editCategory(0)" id="0">
                <div class="url-col-category table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line">Без категории</div>
                    </div>
                </div>
                <div class="clearfix"><hr class="line"></div>
            </div>
            <div id="list-categories">

                <?php foreach ($categories as $category): ?>
                    <div class="fin-acc-row white-back interactive-only" onclick="fullUrl(<?= $category['id'] ?>)" ondblclick="editCategory(<?= $category['id'] ?>)" id="<?= $category['id'] ?>">
                        <div class="url-col-category table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $category['num'] ?>. <?= $category['name'] ?></div>
                            </div>
                        </div>
                        <div class="clearfix"><hr class="line"></div>
                    </div>

                <?php endforeach; ?>

            </div>

            <div class="window-button window-border" id="new-category" onclick="addCategory()">Добавить</div>
        </div>
        <div class="window window-border url-main" id="main-window">

            <div id="list-urls">
                <?php if (count($urls) == 0) { ?>
                    <div id="info" class="text-font text-center margin-v20">
                        У вас пока нет ни одной веб-ссылки.
                        <br>
                        Добавляйте здесь важные для вас веб-ссылки, чтобы не держать открытыми вкладки в браузере
                        <br>
                        и иметь возможность быстро их открывать
                    </div>
                <?php } else { ?>
                    <div class="clearfix"><hr class="line"></div>
                    <?php foreach ($urls as $url): ?>
                        <div class="fin-acc-row white-back interactive-only" id="<?= $url['id'] ?>">
                            <a href="<?= $url['url'] ?>" target="_blank">
                                <div class="url-col-name table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line"><?= $url['num'] ?>. <?= $url['name'] ?></div>
                                    </div>
                                </div>
                            </a>
                            <div class="url-col-panel" ondblclick="editUrl(<?= $url['id'] ?>)">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line unactive">
                                        <span class="glyphicon glyphicon-pencil symbol_style interactive text-center" onclick="editUrl(<?= $url['id'] ?>)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"><hr class="line"></div>
                        </div>

                    <?php endforeach; } ?>
            </div>

            <div class="clearfix"></div>
            <div class="window-button window-border" id="new-url" onclick="addUrl()">Добавить</div>
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
                <div class="caption-text" id="form-caption">Новый счет</div>
                <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
            </div>
            <div class="clearfix"></div>

            <div>
                <div class="caption-line">Ссылка HTTP:</div><div class="message-wrapper-line window-border" id="valueUrlWrap">
                    <div class="message-text-line" contentEditable id="valueUrl" placeholder="http://yavdele.net" ></div>
                </div>
            </div>
            <div>
                <div class="caption-line">Название:</div><div class="message-wrapper-line window-border" id="valueNameWrap">
                    <div class="message-text-line" contentEditable id="valueName" placeholder="Я в деле" ></div>
                </div>
            </div>
            <div class="half_width">
                <div class="caption-line-half">Порядок:</div><div class="message-wrapper-line-half window-border">
                    <input type="number" class="message-text-line" contentEditable id="valueNum" value="1" min="1">
                </div>
            </div>
            <div class="clearfix"></div>
            <div>
                <div class="caption-line">Комментарий:</div><div class="message-wrapper-line window-border">
                    <div class="message-text-line" contentEditable id="valueCom" ></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="red-comment" id="red-comment"></div>
            <div class="window-button window-border" id="button-add">Подтвердить</div>
        </div>

    </div>
</div>

