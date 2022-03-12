<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните следующие поля для регистрации:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?=$form->field($model, 'password')->passwordInput() ?>
                <?=$form->field($model, 'passwordAgain')->passwordInput() ?>


                <!-- //++ 002 12/03/2022 -->
                <div class="w-100">
                    <input type="checkbox" id="setPasVisible" class="custom-checkbox">
                    <label for="setPasVisible" class="interactive-only" id="lblSetPasVisible">Отобразить пароль</label>
                </div>
                <!-- //-- 002 12/03/2022 -->

                <div class="form-group m-t-20px">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'window-button-in-panel window-border', 'name' => 'signup-button']) ?>
                </div>


                <?php //echo $form->errorSummary($model);?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
