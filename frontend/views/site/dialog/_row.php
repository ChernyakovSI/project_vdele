<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\Message $model
 */

use common\models\User;

?>
<div class="row">
    <div class="dialog-field">
        <div class=<?= ($model['id_user'] == Yii::$app->user->identity->getId())?"dialog-caption-my":"dialog-caption-caller" ?>>
            <div class="dialog-capture-start"><?= ($model['id_user'] == Yii::$app->user->identity->getId())?"Я:":User::getI($model['id_user']).":" ?></div>
            <div class="dialog-capture-time"><?= date('H:m:s', $model['created_at']) ?></div>
        </div>
        <div <?= ($model['id_user'] == Yii::$app->user->identity->getId())?('class="window-border-0 dialog-my"'):('class="window-border-0 dialog-caller"') ?>><?= $model['text'] ?></div>
    </div>

</div>