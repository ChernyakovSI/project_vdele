<?php

use \common\models\User;
use \yii\widgets\Pjax;
use \yii\grid\GridView;
use \common\models\Project;
use \common\models\Task;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h3><?php echo User::getUserIO(Yii::$app->user->identity->getId()); ?>, добро пожаловать в менеджер задач!</h3>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-2">
            </div>
            <div class="col-lg-2">
                <p><a class="btn btn-primary btn-block" href="index.php?r=task">Задачи</a></p>
            </div>
            <div class="col-lg-2">
                <p><a class="btn btn-primary btn-block" href="index.php?r=team">Команды</a></p>
            </div>
        </div>

        <div class="team-index">

            <h1>Задачи к выполнению</h1>
            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                        'attribute' => 'id_manager',
                        'value' => function ($data) { return User::getUserFIO($data->id_manager); },
                    ],
                    [
                        'attribute' => 'deadline',
                        'value' => function ($data) { if ($data->deadline > 0) {return date('j.m.Y H:i:s', $data->deadline);} else {return 'не назначен';} },
                    ],
                    [
                        'attribute' => 'id_project',
                        'value' => function ($data) { return Project::findOne($data->id_project)->name;},
                    ],


                    ['class' => 'yii\grid\ActionColumn',
                        'buttons'=>[
                            'view'=>function ($url, $model) {
                                $customurl=Yii::$app->getUrlManager()->createUrl(['task/view','id'=>$model['id']]); //$model->id для AR
                                return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                                    ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                            },
                        ],
                        'template'=>'{view}',
                    ],
                ],
            'rowOptions' => function ($model, $key, $index, $grid) {
                return Task::getStyleRow($model->id_status, $model->deadline);
            }
            ]); ?>
            <?php Pjax::end(); ?>
        </div>

    </div>
</div>
