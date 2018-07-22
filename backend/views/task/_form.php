<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Task;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_project')->dropDownList(Task::getArrayOfProjects()) ?>

    <?= $form->field($model, 'id_doer')->dropDownList(Task::getArrayOfUsersForProject($model->id_project)) ?>

    <?= $form->field($model, 'id_manager')->dropDownList(Task::getArrayOfAdmins()) ?>

    <?= $form->field($model, 'strDeadline')->widget(DatePicker::className(), ['pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose' => true,
    ],]) ?>

    <?= $form->field($model, 'strFinishDate')->widget(DatePicker::className(), ['pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose' => true,
    ],]) ?>

    <?= $form->field($model, 'id_status')->dropDownList(Task::getArrayOfStatuses()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
