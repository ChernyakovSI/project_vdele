<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\models\User;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Team */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Команды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить команду?',
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

    <h2><?= Html::encode("Участники") ?></h2>

    <p>
        <?= Html::a('Добавить участника', ['add', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'userFIO',
                'value' => function ($data) { return User::getUserFIO($data->id); },
            ],

            ['class' => 'yii\grid\ActionColumn',

                'buttons'=>[
                    'delete'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['team/deleteuser','id_user'=>$model['id'], 'id_team'=>$model['id_team']]); //$model->id для AR
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-remove-circle"></span>', $customurl,
                            ['title' => Yii::t('yii', 'Delete'), 'data-pjax' => '0']);
                    }
                ],
                'template'=>'{delete}',
            ],
        ],
    ]); ?>



</div>
