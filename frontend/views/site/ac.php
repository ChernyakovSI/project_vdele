<?php

use \common\models\Ac;
use yii\helpers\Html;
use yii\web\UrlManager;

/* @var $this yii\web\View class="site-index"*/

$this->title = 'Мой профиль';
//$this->registerCssFile('css/ac.css');

?>
<div class="content">
    <div class="container-wrap">
        <div class="window window-border avatar">avatar</div>
        <div class="window window-border window-caption-full FIO">
            <div class="FIO-main">
                <?php
                $current_ac = new Ac;
                echo $current_ac->getFIO($user_id);
                ?>
            </div>
            <a href="/site/ac-edit"><div class="subwindow unactive FIO-edit"><span class="glyphicon glyphicon-pencil symbol_style interactive"></div></a>
            <div class="subwindow unactive">online</div>
        </div>
        <div class="window window-border main-info">
            <div class="container-wrap-2colomns">
                <div class="wrap-column1">
                    <div class="container-wrap-2colomns">
                        <div class="wrap-column1">
                            <label>Дата рождения:</label>
                        </div>
                        <div class="wrap-column2">
                            <?= date('d', $cur_user->identity->date_of_birth).' '.$months[date('n', $cur_user->identity->date_of_birth) - 1].' '.date('Y', $cur_user->identity->date_of_birth).' г.' ?>
                        </div>
                    </div>
                </div>
                <div class="wrap-column2">

                </div>
            </div>
        </div>
        <div class="window window-border goals">goals</div>
    </div>
</div>