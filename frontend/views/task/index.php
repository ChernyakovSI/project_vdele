<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\User;
use common\models\TaskStatus;
use common\models\Project;
use common\models\Task;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'created_at',
                'value' => function ($data) { return date('j.m.Y H:i:s', $data->created_at); },
            ],
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
                'value' => function ($data) { return TaskStatus::getStatusName($data->id_status);},
            ],


            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
            ],
        ],
        'rowOptions' => function ($model, $key, $index, $grid) {
            return Task::getStyleRow($model->id_status, $model->deadline);
        }
    ]); ?>
    <?php Pjax::end(); ?>
</div>
