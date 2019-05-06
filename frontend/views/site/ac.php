<?php

use \common\models\Ac;

/* @var $this yii\web\View class="site-index"*/

$this->title = 'Мой аккаунт';
$this->registerCssFile('css/ac.css');

?>
<div class="content">
    <div class="container-wrap">
        <div class="window avatar">avatar</div>
        <div class="window FIO">
            <?php
                $current_ac = new Ac;
                echo $current_ac->getFIO($user_id);
            ?></div>
        <div class="window main-info">main info</div>
        <div class="window goals">goals</div>
    </div>
</div>