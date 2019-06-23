<?php

use \common\models\Ac;
use yii\helpers\Html;
use yii\web\UrlManager;

/* @var $this yii\web\View class="site-index"*/

$this->title = 'Мой профиль';

$isFilled = false;
//$this->registerCssFile('css/ac.css');

?>
<div class="content">
    <div class="container-wrap">
        <div class="window window-border avatar">
            <?php if($path_avatar !== '') { ?>
                <img src=<?= '/data/img/avatar/'.$path_avatar; ?> class="avatar_font">
            <?php }
            else { ?>
                <img src=<?= '/data/img/avatar/avatar_default.jpg'; ?> class="avatar_font">
            <?php } ?>
        </div>
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
                    $fullYears = $cur_user->getFullYears($cur_user->date_of_birth);
                    $isFilled = true; ?>
                <div class="container-wrap-2colomns">
                    <div class="wrap2-column1">
                        <label>Дата рождения:</label>
                    </div>
                    <div class="wrap2-column2">
                        <?= date('d', $cur_user->date_of_birth).' '.$months[date('n', $cur_user->date_of_birth) - 1].' '.date('Y', $cur_user->date_of_birth).' г. ('.$fullYears.$cur_user->getInclinationByNumber($fullYears, Array(' год', ' года', ' лет')).')' ?>
                    </div>
                </div>
                <?php }
                if($cur_user->id_city > 0) {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Город:</label>
                        </div>
                        <div class="wrap2-column2">
                            <?= $city->name ?>
                        </div>
                    </div>
                <?php }
                if($cur_user->phone <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Телефон:</label>
                        </div>
                        <div class="wrap2-column2">
                            <?= $cur_user->phone ?>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_vk <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>ВКонтакте:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_vk ?>" target="_blank"> <?= $cur_user->url_vk ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_fb <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Facebook:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_fb ?>" target="_blank"> <?= $cur_user->url_fb ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_ok <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Одноклассники:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_ok ?>" target="_blank"> <?= $cur_user->url_ok ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_in <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Instagram:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_in ?>" target="_blank"> <?= $cur_user->url_in ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->url_www <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>WWW:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="<?= $cur_user->url_www ?>" target="_blank"> <?= $cur_user->url_www ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->skype <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Skype:</label>
                        </div>
                        <div class="wrap2-column2">
                            <u><a href="skype:<?= $cur_user->skype ?>" target="_blank"> <?= $cur_user->skype ?> </a></u>
                        </div>
                    </div>
                <?php }
                if($cur_user->icq <> '') {
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>ICQ:</label>
                        </div>
                        <div class="wrap2-column2">
                            <?= $cur_user->icq ?>
                        </div>
                    </div>
                <?php } ?>

                <?php
                if($cur_user->about <> '') {
                    if($isFilled === true) {?>
                        <br>
                    <?php }
                    $isFilled = true; ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">
                            <label>Доп. информация:</label>
                        </div>
                        <div class="wrap2-column2">
                            <pre>
                                <?= $cur_user->about ?>
                            </pre>
                        </div>
                    </div>
                <?php } ?>


                <?php if($isFilled === false) {
                ?>
                    <div class="container-wrap-2colomns">
                        <div class="wrap2-column1">

                        </div>
                        <div class="wrap2-column2">
                            <label><u><a href="/site/ac-edit">Данные не заполнены.</a></u></label>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
        <!--<div class="window window-border goals">goals</div>-->
    </div>
</div>

<a data-config="commands=*;size=14;status=off;theme=logo;language=ru;bgcolor=#2a92f3" id="skaip-buttons" href="http://www.skaip.su/">Skype</a><script src="//apps.skaip.su/buttons/widget/core.min.js" defer="defer"></script>