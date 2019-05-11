<?php

use \common\models\Ac;
use yii\helpers\Html;
use yii\web\UrlManager;

/* @var $this yii\web\View class="site-index"*/

$this->title = 'Мой аккаунт';
//$this->registerCssFile('css/ac.css');

?>
<div class="content">
    <div class="container-wrap">
        <div class="window window-border avatar">avatar</div>
        <div class="window window-border window-caption FIO">
            <div class="FIO-main">
                <?php
                $current_ac = new Ac;
                echo $current_ac->getFIO($user_id);
                ?>
            </div>
            <a href="/site/ac-edit"><div class="subwindow unactive FIO-edit"><span class="glyphicon glyphicon-pencil symbol_style interactive"></div></a>
            <div class="subwindow unactive">online</div>
        </div>
        <div class="window window-border main-info">main info</div>
        <div class="window window-border goals">goals</div>
    </div>
</div>