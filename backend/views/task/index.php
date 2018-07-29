<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\User;
use common\models\TaskStatus;
use common\models\Project;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать задачу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'created_at',
                'value' => function ($data) { return date('j.m.Y H:i:s', $data->created_at); },
            ],
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
                'value' => function ($data) { return TaskStatus::getStatusName($data->id_status);},
            ],
            [
                'attribute' => 'id_project',
                'value' => function ($data) { return Project::findOne($data->id_project)->name;},
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
