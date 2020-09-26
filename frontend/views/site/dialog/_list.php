<?php
/**
 * @var \yii\web\View $this
 * @var \yii\db\ActiveQuery $messagesQuery
 */

    use yii\data\SqlDataProvider;
    use yii\data\ArrayDataProvider;

    $script = new \yii\web\JsExpression("

    function loadPager() {
        $('.ias-trigger a').trigger('click');
    }
    ");

    $this->registerJs($script, \yii\web\View::POS_READY);

    $count = Yii::$app->db->createCommand('
        SELECT COUNT(*) FROM message WHERE id_dialog=:id_dialog
          ', [':id_dialog' => $dialog_id])->queryScalar();

    $provider = new SqlDataProvider([
        'sql' => 'SELECT id, created_at, id_user, text, is_new FROM message WHERE id_dialog=:id_dialog ORDER BY created_at DESC',
        //'params' => [':id_dialog' => $dialog_id],
        'params' => [':id_dialog' => -1],
        //'totalCount' => (int)$count,
        'totalCount' => 0,
        'pagination' => [
            'pageSize' => $option['limit'],
        ],
        'sort' => [
            'attributes' => [
                'created_at',
            ],
        ],

    ]);

    $data = $provider->getModels();

    $provider = new ArrayDataProvider([
        'allModels' => array_reverse($data),
    ]);

?>

<?=

\yii\widgets\ListView::widget([
    'itemView' => '_row',
    'layout' => "{pager}{items}",
    'dataProvider' => $provider, //new \yii\data\ActiveDataProvider([
        //'query' => array_reverse($messagesQuery),
        //'pagination' => ['pageSize' => 5,]
    //]),
    'options' => [
        'tag' => 'div',
        'class' => 'dialog-list'
    ],
    'itemOptions' => ['class' => 'dialog-item',
                        'tag' => 'div'],
    //'emptyText' => 'В данном диалоге еще пока нет сообщений',
    'emptyText' => '',

    /*'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
        'container' => '.dialog-main',
        'item' => '.dialog-item',
        'triggerTemplate' => '<div class="reload">
                                                             <a href="" role="button" title="Button" class="btn-reload">{text}</a>
                                                         </div>',
        'triggerText' => 'Показать еще',
        'noneLeftText' => '',
        'historyPrevText' => 'Показать еще',
        'historyPrevTemplate' => '<div class="ias-trigger ias-trigger-prev" style="text-align: center; cursor: pointer;"><a>{text}</a></div>',
        //'negativeMargin' => 5,
        ],*/

])

?>
