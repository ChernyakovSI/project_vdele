<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Заполните, пожалуйста, следующие поля, чтобы авторизоваться:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <!-- //++ 002 12/03/2022 -->
                <div class="w-100">
                    <input type="checkbox" id="setPasVisible" class="custom-checkbox">
                    <label for="setPasVisible" class="interactive-only" id="lblSetPasVisible">Отобразить пароль</label>
                </div>

                <div class="w-100">
                    <input type="checkbox" id="setRememberMe" class="custom-checkbox">
                    <label for="setRememberMe" class="interactive-only" id="lblSetRememberMe">Запомнить меня</label>
                </div>
                <!-- //-- 002 12/03/2022 -->

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    Если вы забыли ваш пароль, вы можете <?= Html::a('сбросить его', ['site/request-password-reset']) ?>.
                </div>
                <div style="color:#999;margin:1em 0">
                    <!-- //++ 002 12/03/2022
                    //:-
                    Если вы никогда не являтесь пользователем сайта, вы можете <= Html::a('зарегистрироваться', ['/signup']) ?>.
                    //:+
                    -->
                    Если вы еще не являетесь пользователем сайта, вы можете <?= Html::a('зарегистрироваться', ['/signup']) ?>.
                    <!-- //-- 002 12/03/2022 -->
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'window-button-in-panel window-border', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
