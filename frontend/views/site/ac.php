<?php

use \common\models\Ac;
use common\models\User;
use yii\helpers\Html;
use \common\models\GeneralView;
use yii\web\UrlManager;

/* @var $this yii\web\View class="site-index"*/

$this->title = 'Мой профиль';

$isFilled = false;
//$this->registerCssFile('css/ac.css');

?>
<div class="content">
    <div class="container-wrap">
        <div class="window window-border avatar">
            <?php if((isset($path_avatar)) && ($path_avatar != '')) { ?>
                <img src=<?= '/data/img/avatar/'.$path_avatar; ?> class="avatar_font">
            <?php }
            else {
                if((isset($user->gender)) && ($user->gender === 2)) { ?>
                <img src=<?= '/data/img/avatar/avatar_default_w.jpg'; ?> class="avatar_font">
                <?php }
                else { ?>
                    <img src=<?= '/data/img/avatar/avatar_default.jpg'; ?> class="avatar_font">
                <?php }
             } ?>
        </div>
        <div class="window window-border window-caption-full FIO">
            <div class="FIO-main">
                <?php
                $current_ac = new Ac;
                echo $current_ac->getFIO($user_id);
                ?>
            </div>
            <?php if($cur_user_id == $user->getId()) { ?>
                <a href="/site/ac-edit"><div class="subwindow unactive FIO-edit"><span class="glyphicon glyphicon-pencil symbol_style interactive"></div></a>
            <?php } ?>
            <div class="subwindow unactive ac-last-activity"><?= $user->getTimeLastActivity() ?></div>
        </div>
        <div class="window window-border main-info">
            <div class="full-height full-width">

                    <?php if($user->date_of_birth > 0) {
                        $fullYears = $user->getFullYears($user->date_of_birth);
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>Дата рождения:</label>
                            </div>
                            <div class="wrap2-column2">
                                <?= date('d', $user->date_of_birth).' '.$months[date('n', $user->date_of_birth) - 1].' '.date('Y', $user->date_of_birth).' г. ('.$fullYears.$user->getInclinationByNumber($fullYears, Array(' год', ' года', ' лет')).')' ?>
                            </div>
                        </div>
                    <?php }
                    if($user->id_city > 0) {
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
                    if($user->phone <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>Телефон:</label>
                            </div>
                            <div class="wrap2-column2">
                                <?= $user->phone ?>
                            </div>
                        </div>
                    <?php }
                    if($user->url_vk <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>ВКонтакте:</label>
                            </div>
                            <div class="wrap2-column2">
                                <u><a href="<?= User::wrapURL($user->url_vk, 'https://vk.com/') ?>" target="_blank"> <?= $user->url_vk ?> </a></u>
                            </div>
                        </div>
                    <?php }
                    if($user->url_fb <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>Facebook:</label>
                            </div>
                            <div class="wrap2-column2">
                                <u><a href="<?= User::wrapURL($user->url_fb, 'https://www.facebook.com/') ?>" target="_blank"> <?= $user->url_fb ?> </a></u>
                            </div>
                        </div>
                    <?php }
                    if($user->url_ok <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>Одноклассники:</label>
                            </div>
                            <div class="wrap2-column2">
                                <u><a href="<?= User::wrapURL($user->url_ok, 'https://ok.ru/') ?>" target="_blank"> <?= $user->url_ok ?> </a></u>
                            </div>
                        </div>
                    <?php }
                    if($user->url_in <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>Instagram:</label>
                            </div>
                            <div class="wrap2-column2">
                                <u><a href="<?= User::wrapURL($user->url_in, 'http://www.instagram.com/') ?>" target="_blank"> <?= $user->url_in ?> </a></u>
                            </div>
                        </div>
                    <?php }
                    if($user->url_www <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>WWW:</label>
                            </div>
                            <div class="wrap2-column2">
                                <u><a href="<?= User::wrapURL($user->url_www) ?>" target="_blank"> <?= $user->url_www ?> </a></u>
                            </div>
                        </div>
                    <?php }
                    if($user->skype <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>Skype:</label>
                            </div>
                            <div class="wrap2-column2">
                                <u><a href="skype:<?= $user->skype ?>" target="_blank"> <?= $user->skype ?> </a></u>
                            </div>
                        </div>
                    <?php }
                    if($user->icq <> '') {
                        $isFilled = true; ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">
                                <label>ICQ:</label>
                            </div>
                            <div class="wrap2-column2">
                                <?= $user->icq ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if(($isFilled === false) && ($user->about === '')) {
                        ?>
                        <div class="container-wrap-2colomns">
                            <div class="wrap2-column1">

                            </div>
                            <div class="wrap2-column2">
                                <?php if($cur_user_id == $user->getId()) { ?>
                                    <label><u><a href="/site/ac-edit">Данные не заполнены.</a></u></label>
                                <?php } else { ?>
                                    <label>Данные не заполнены.</label>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
            </div>


        </div>

        <div class="window window-border button-panel">
            <div class="full-width full-height window-subcaption">
                <?php if($cur_user_id != $user->getId()) { ?>
                    <a href="/dialog?id=<?= $user->getId() ?>">
                        <div class="subwindow button-item">

                        <hr/>Написать<hr/>

                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="window window-border goals">
            <div class="full-width">
                <?php
                if($user->about <> '') {
                    if($isFilled === true) {?>
                        <br>
                    <?php }
                    $isFilled = true; ?>
                    <?= Html::label('Дополнительная информация:', 'about')?>
                    <?= Html::textarea('about', $user->about, ['class' => 'label-about', 'readonly' => 'readonly', 'rows' => '10'])?>


                    <?php //echo Html::textarea('about', $user->about, ['class' => 'label-about']) ?>

                <?php } ?>
            </div>


        </div>
    </div>
</div>

<a data-config="commands=*;size=14;status=off;theme=logo;language=ru;bgcolor=#2a92f3" id="skaip-buttons" href="http://www.skaip.su/">Skype</a><script src="//apps.skaip.su/buttons/widget/core.min.js" defer="defer"></script>