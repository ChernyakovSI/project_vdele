<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \common\models\Role;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            //'status',
            'name',
            'surname',
            'middlename',
            [
                'attribute' => 'id_role',
                'value' => function ($data) { return Role::getRoleById($data->id_role); },
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
