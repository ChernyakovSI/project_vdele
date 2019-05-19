
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
        ['options' => ['class' => 'form-horizontal', 'id' => 'ac-edit'],
            'fieldConfig' => [
                'template' => "<div class=\"col-lg-3\">{label}</div>\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-12 col-lg-offset-3\">{error}</div>",
                'labelOptions' => ['class' => ''],
            ],
        ]);
    ?>

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
                    echo $form->field($cur_user, 'surname')->textInput(['autofocus' => true]);
                    echo $form->field($cur_user, 'name')->textInput();
                    echo $form->field($cur_user, 'middlename')->textInput();
                    ?>
                </div>
                <div class="column2">
                    <?php
                    echo $form->field($cur_user, 'gender')->dropDownList([
                        '0' => 'Не выбрано',
                        '1' => 'Мужской',
                        '2'=> 'Женский'
                    ]);
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
                        ]])
                    ?>
                </div>
            </div>
        </section>
        <section id="content-tab3" class="window window-border-bottom">
            <p>
                В разработке...
            </p>
        </section>
        <section id="content-tab4" class="window window-border-bottom">
            <p>
                В разработке...
            </p>
        </section>
        <section id="content-tab5" class="window window-border-bottom">
            <p>
                В разработке...
            </p>
        </section>
    </div>

    <div class="window window-border window-panel-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>