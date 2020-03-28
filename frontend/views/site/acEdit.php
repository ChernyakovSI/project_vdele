
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = $this->title;

$this->registerLinkTag([
    'rel' => 'shortcut icon',
    'type' => 'image/x-icon',
    'href' => 'favicon.png',
]);
?>

<div class="content">
    <div class="window window-border window-caption">Редактирование профиля</div>

    <?php $form = ActiveForm::begin(
        ['options' => ['class' => 'form-horizontal',
                        'id' => 'ac-edit',
                        'enctype' => 'multipart/form-data'],
            'fieldConfig' => [
                'template' => "<div class=\"col-lg-3\">{label}</div>\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-12 col-lg-offset-3\">{error}</div>",
                'labelOptions' => ['class' => ''],
            ],
        ]);
    ?>

    <noscript>
        <div class="info">У Вас в браузере заблокирован JavaScript. Разрешите JavaScript для работы сайта!</div>
    </noscript>

    <div class="tabs">
        <input id="tab2" type="radio" name="tabs" checked>
        <label for="tab2" title="Основная информация" class="window window-border-top">Основное</label>

        <input id="tab3" type="radio" name="tabs">
        <label for="tab3" title="Аватар" class="window window-border-top">Аватар</label>

        <input id="tab4" type="radio" name="tabs">
        <label for="tab4" title="Контактная информация" class="window window-border-top">Контакты</label>

        <input id="tab5" type="radio" name="tabs">
        <label for="tab5" title="Дополнительная информация" class="window window-border-top">Дополнительно</label>

        <section id="content-tab2" class="window window-border-bottom">
            <div class="container-wrap-acEdit">
                <div class="column1">
                    <?php
                    echo $form->field($cur_user, 'surname')->textInput(['autofocus' => true])->label('Фамилия:');
                    echo $form->field($cur_user, 'name')->textInput()->label('Имя:');
                    echo $form->field($cur_user, 'middlename')->textInput()->label('Отчество:');
                    ?>
                </div>
                <div class="column2">
                    <?php
                    echo $form->field($cur_user, 'gender')->dropDownList([
                        '0' => 'Не выбрано',
                        '1' => 'Мужской',
                        '2'=> 'Женский'
                    ])->label('Пол:');
                    echo $form->field($cur_user,'date_of_birth')->widget(DatePicker::class, [
                        'language' => 'ru',
                        'dateFormat' => 'dd.MM.yyyy',
                        'options' => [
                            'placeholder' => Yii::$app->formatter->asDate($cur_user->date_of_birth),
                            'class'=> 'form-control',
                            'autocomplete'=>'off'
                        ],
                        'clientOptions' => [
                            'changeMonth' => true,
                            'changeYear' => true,
                            'yearRange' => '1960:'.date('Y'),
                            //'showOn' => 'button',
                            //'buttonText' => 'Выбрать дату',
                            //'buttonImageOnly' => true,
                            //'buttonImage' => 'images/calendar.gif'
                        ]])->label('Дата рождения:')
                    ?>
                </div>
            </div>
        </section>
        <section id="content-tab3" class="window window-border-bottom">
            <?php
            echo $form->field($cur_user, 'imageFile')->fileInput(['onchange' => 'loadFile(event)', 'id' => 'imageFileInput'])->label('Фото профиля:');
            echo Html::tag('div', 'Рекомедуется загружать картинку с соотношением ширины к высоте, как 3:4'); ?>
            <div class="content">
                <div class="container-wrap3">
                    <div class="window window-border avatar">
                        <?php if((isset($path_avatar)) && ($path_avatar != '')) { ?>
                            <img src=<?= '/data/img/avatar/'.$path_avatar; ?> class="avatar_font" id="output">
                        <?php }
                        else {
                            if((isset($cur_user->gender)) && ($cur_user->gender === 2)) { ?>
                                <img src=<?= '/data/img/avatar/avatar_default_w.jpg'; ?> class="avatar_font" id="output">
                            <?php }
                            else { ?>
                                <img src=<?= '/data/img/avatar/avatar_default.jpg'; ?> class="avatar_font" id="output">
                            <?php }
                        } ?>
                    </div>
                    <div class="window window-border main-info columnUnvis">
                        <input type="text" value="<?= ((isset($cur_user->gender)) && ($cur_user->gender === 2)) ? '2' : '1';  ?>" id = "jsGender" hidden>
                    </div>
                </div>
            </div>
            </br>
            <?= Html::Button('Удалить', ['class' => 'btn btn-primary', 'onclick' => 'DeleteAvatar()']) ?>

        </section>
        <section id="content-tab4" class="window window-border-bottom">
            <div class="container-wrap-acEdit">
                <div class="column1">

                    <script type="text/javascript" src="/js/geo/api.js" async></script>

                    <div></div>
                    <?php
                    echo Html::tag('div', 'Основные', ['class' => 'caption']);
                    ?>
                    <div class="wrap_text">
                        <?php
                        echo $form->field($cur_user, 'city')->
                            textInput(['id' => "city", 'placeholder' => "Начните вводить название", 'value' => isset($city) ? $city->name : '', 'onfocus' => "_geo.f_Choice=CityChoice;_geo.init(this);"])->
                            label('Город:');
                        echo $form->field($cur_user, 'email')->Input('email')->label('Email:');
                        echo $form->field($cur_user, 'username')->textInput(['readonly' => true])->label('Логин:');
                        echo $form->field($cur_user, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '+7 (999) 999-99-99',
                        ])->textInput(['placeholder' => $cur_user->getAttributeLabel('phone')])->label('Телефон:');
                        ?>
                    </div>

                    <div class="columnUnvis">
                        <?php
                            echo $form->field($cur_user, 'id_city')->textInput(['id' => "id_city"]);
                        ?>
                    </div>

                    <?php
                    echo Html::tag('div', 'Мессенджеры', ['class' => 'caption']);
                    ?>
                    <div class="wrap_text">
                        <?php
                        echo $form->field($cur_user, 'skype')->textInput()->label('Skype:');
                        echo $form->field($cur_user, 'icq')->textInput()->label('ICQ:');
                        ?>
                    </div>


                    <div id="info"></div>
                </div>
                <div class="column2">
                    <?php
                    echo Html::tag('div', 'WWW адреса', ['class' => 'caption']);
                    ?>
                    <div class="wrap_text">
                        <?php
                            echo $form->field($cur_user, 'url_vk')->textInput()->label('ВКонтакте:');
                            echo $form->field($cur_user, 'url_fb')->textInput()->label('Facebook:');
                            echo $form->field($cur_user, 'url_ok')->textInput()->label('Одноклассники:');
                            echo $form->field($cur_user, 'url_in')->textInput()->label('Instagram:');
                            echo $form->field($cur_user, 'url_www')->textInput()->label('Другой сайт:');
                        ?>
                    </div>
                </div>
            </div>
        </section>
        <section id="content-tab5" class="window window-border-bottom">
            <?php
            echo $form->field($cur_user, 'about', [
                'template' => '{label}{input}{hint}{error}'
            ])->textarea(['rows' => 15, 'cols' => 25, 'class' => 'resize_vertical_only'])->label('Дополнительная информация:');
            ?>
        </section>
    </div>

    <div class="window window-border window-panel-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

    var DeleteAvatar = function() {
        var imageFileInput = document.getElementById('imageFileInput');
        imageFileInput.value = '';

        var Gender = document.getElementById('jsGender').value;

        var output = document.getElementById('output');

        //console.log('Gender = ' + Gender);
        if (Gender == '1') {
            output.src = '/data/img/avatar/avatar_default.jpg';
        }
        else {
            output.src = '/data/img/avatar/avatar_default_w.jpg';
        }

        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>