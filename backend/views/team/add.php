<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Team */

$this->title = 'Добавить участника команды: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Команды', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Добавление участника';
?>
<div class="team-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formUser', [
        'model' => $model,
    ]) ?>

</div>
