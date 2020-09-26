<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\Message $message
 * @var \yii\db\ActiveQuery $messagesQuery
 */

?>
<?php \yii\widgets\Pjax::begin([
    'id' => 'list-messages',
    'enablePushState' => false,
    'formSelector' => false,
    'linkSelector' => false
]) ?>
<?= $this->render('_list', compact('messagesQuery', 'dialog_id', 'option')) ?>
<?php \yii\widgets\Pjax::end() ?>