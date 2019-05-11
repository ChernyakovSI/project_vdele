
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content">
    <div class="window window-border window-caption">Редактирование профиля</div>

    <?php $form = ActiveForm::begin(
        ['options' => ['class' => 'form-horizontal', 'id' => 'ac-edit'],
            'fieldConfig' => [
                'template' => "<div class=\"col-lg-2\">{label}</div>\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-12 col-lg-offset-3\">{error}</div>",
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
            <p>
                <?php
                echo $form->field($cur_user, 'surname')->textInput(['autofocus' => true]);
                echo $form->field($cur_user, 'name')->textInput();
                echo $form->field($cur_user, 'middlename')->textInput();
                ?>
            </p>
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