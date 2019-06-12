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
            <div class="full-width">
                <?php if($cur_user->date_of_birth > 0) {
                    $fullYears = $cur_user->getFullYears($cur_user->date_of_birth); ?>
                <div class="container-wrap-2colomns">
                    <div class="wrap2-column1">
                        <label>Дата рождения:</label>
                    </div>
                    <div class="wrap2-column2">
                        <?= date('d', $cur_user->date_of_birth).' '.$months[date('n', $cur_user->date_of_birth) - 1].' '.date('Y', $cur_user->date_of_birth).' г. ('.$fullYears.$cur_user->getInclinationByNumber($fullYears, Array(' год', ' года', ' лет')).')' ?>
                    </div>
                </div>
                <?php }
                if($cur_user->id_city > 0) { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Город:</label>
                        </div>
                        <div class="wrap2-column2">
                            <?= $city->name ?>
                        </div>
                    </div>
                <?php }
                if($cur_user->phone <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Телефон:</label>
                        </div>
                        <div class="wrap2-column2">
                            <?= $cur_user->phone ?>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_vk <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>ВКонтакте:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_vk ?>" target="_blank"> <?= $cur_user->url_vk ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_fb <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Facebook:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_fb ?>" target="_blank"> <?= $cur_user->url_fb ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_ok <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Одноклассники:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_ok ?>" target="_blank"> <?= $cur_user->url_ok ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_in <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Instagram:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_in ?>" target="_blank"> <?= $cur_user->url_in ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_www <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>WWW:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_www ?>" target="_blank"> <?= $cur_user->url_www ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->skype <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Skype:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="skype:<?= $cur_user->skype ?>" target="_blank"> <?= $cur_user->skype ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->icq <> '') { ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>ICQ:</label>
                        </div>
                        <div class="wrap2-column2">
                            <?= $cur_user->icq ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
        <div class="window window-border goals">goals</div>
    </div>
</div>

<a data-config="commands=*;size=14;status=off;theme=logo;language=ru;bgcolor=#2a92f3" id="skaip-buttons" href="http://www.skaip.su/">Skype</a><script src="//apps.skaip.su/buttons/widget/core.min.js" defer="defer"></script>