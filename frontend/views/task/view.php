<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\TaskStatus;
use common\models\Project;
use \yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name.' от '.date('j.m.Y H:i:s', $model->created_at);
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Закрыть', ['index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id_manager',
                'value' => function ($data) { return User::getUserFIO($data->id_manager); },
            ],
            [
                'attribute' => 'id_project',
                'value' => function ($data) { return Project::findOne($data->id_project)->name;},
            ],
            [
                'attribute' => 'deadline',
                'value' => function ($data) { return date('j.m.Y H:i:s', $data->deadline); },
            ],
            [
                'attribute' => 'id_status',
                'value' => function ($data) { return TaskStatus::getStatusName($data->id_status); },
            ],
            [
                'attribute' => 'finish_date',
                'value' => function ($data) { if ($data->finish_date > 0) {return date('j.m.Y H:i:s', $data->finish_date);} else {return 'не выполнена';} },
            ],
            'description:ntext',
        ],
    ]) ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'result')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Выполнить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
