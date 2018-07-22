<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Team;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить проект?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
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

    <h2><?= Html::encode("Команды проекта") ?></h2>

    <p>
        <?= Html::a('Добавить команду', ['add', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_team',
                'value' => function ($data) { return Team::findOne($data->id_team)->name; },
            ],

            ['class' => 'yii\grid\ActionColumn',

                'buttons'=>[
                    'delete'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['project/deleteteam','id_team'=>$model['id_team'], 'id_project'=>$model['id_project']]); //$model->id для AR
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-remove-circle"></span>', $customurl,
                            ['title' => Yii::t('yii', 'Delete'), 'data-pjax' => '0']);
                    }
                ],
                'template'=>'{delete}',
            ],
        ],
    ]); ?>

</div>
