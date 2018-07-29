<?php

use \yii\widgets\Pjax;
use \yii\grid\GridView;
use \common\models\User;
use \common\models\TaskStatus;
use \common\models\Project;
/* @var $this yii\web\View */

$this->title = 'Менеджер задач';
?>
<div class="site-index">

    <div class="jumbotron">
        <h3><?php echo User::getUserIO(Yii::$app->user->identity->getId()); ?>, добро пожаловать в административную часть менеджера задач!</h3>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-2">
            </div>
            <div class="col-lg-2">
                <p><a class="btn btn-primary btn-block" href="index.php?r=user">Пользователи</a></p>
            </div>
            <div class="col-lg-2">
                <p><a class="btn btn-primary btn-block" href="index.php?r=team">Команды</a></p>
            </div>
            <div class="col-lg-2">
                <p><a class="btn btn-primary btn-block" href="index.php?r=project">Проекты</a></p>
            </div>
            <div class="col-lg-2">
                <p><a class="btn btn-primary btn-block" href="index.php?r=task">Задачи</a></p>
            </div>
        </div>

        <div class="team-index">

            <h1>Новые закрытые задачи</h1>
            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProviderDone,
                'filterModel' => $searchModelDone,
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


                    ['class' => 'yii\grid\ActionColumn',
                        'buttons'=>[
                            'view'=>function ($url, $model) {
                                $customurl=Yii::$app->getUrlManager()->createUrl(['task/view','id'=>$model['id']]); //$model->id для AR
                                return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                                    ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                            },
                            'update'=>function ($url, $model) {
                                $customurl=Yii::$app->getUrlManager()->createUrl(['task/update','id'=>$model['id']]); //$model->id для AR
                                return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                                    ['title' => Yii::t('yii', 'Update'), 'data-pjax' => '0']);
                            },
                            /*'delete'=>function ($url, $model) {
                                $customurl=Yii::$app->getUrlManager()->createUrl(['task/delete','id'=>$model['id']]); //$model->id для AR
                                return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                                    ['title' => Yii::t('yii', 'Delete'), 'data-pjax' => '0']);
                            }*/
                        ],
                        'template'=>'{view} {update}',
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>

        <div class="team-index">

            <h1>Просроченные задачи</h1>
            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProviderExpired,
                'filterModel' => $searchModelExpired,
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
                        'value' => function ($data) { if ($data->deadline > 0) {return date('j.m.Y H:i:s', $data->deadline);} else {return 'не установлен';} },
                    ],
                    [
                        'attribute' => 'id_status',
                        'value' => function ($data) { return TaskStatus::getStatusName($data->id_status);},
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
                            'update'=>function ($url, $model) {
                                $customurl=Yii::$app->getUrlManager()->createUrl(['task/update','id'=>$model['id']]); //$model->id для AR
                                return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                                    ['title' => Yii::t('yii', 'Update'), 'data-pjax' => '0']);
                            },
                            /*'delete'=>function ($url, $model) {
                                $customurl=Yii::$app->getUrlManager()->createUrl(['task/delete','id'=>$model['id']]); //$model->id для AR
                                return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                                    ['title' => Yii::t('yii', 'Delete'), 'data-pjax' => '0']);
                            }*/
                        ],
                        'template'=>'{view} {update}',
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>

    </div>
</div>
