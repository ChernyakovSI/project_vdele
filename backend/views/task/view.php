<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\TaskStatus;
use common\models\Project;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить задачу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'id_doer',
                'value' => function ($data) { return User::getUserFIO($data->id_doer); },
            ],
            [
                'attribute' => 'id_manager',
                'value' => function ($data) { return User::getUserFIO($data->id_manager); },
            ],
            [
                'attribute' => 'deadline',
                'value' => function ($data) { return date('j.m.Y H:i:s', $data->deadline); },
            ],
            [
                'attribute' => 'finish_date',
                'value' => function ($data) { if ($data->finish_date > 0) {return date('j.m.Y H:i:s', $data->finish_date);} else {return 'не выполнена';} },
            ],
            [
                'attribute' => 'id_status',
                'value' => function ($data) { return TaskStatus::getStatusName($data->id_status); },
            ],
            [
                'attribute' => 'id_project',
                'value' => function ($data) { return Project::findOne($data->id_project)->name;},
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($data) { return date('j.m.Y H:i:s', $data->created_at); },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($data) { return date('j.m.Y H:i:s', $data->updated_at); },
            ],
        ],
    ]) ?>

</div>
