
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->registerJsFile('@web/js/profile/ac-edit_pos_ready.js', ['position' => \yii\web\View::POS_READY]);

$this->title = 'Редактирование профиля';
//$this->params['breadcrumbs'][] = $this->title;

$this->registerLinkTag([
    'rel' => 'shortcut icon',
    'type' => 'image/x-icon',
    'href' => '/frontend/web/favicon.png',
]);
?>

<div id="paramDateOfBirth" hidden="hidden"><?= $dateOfBirth ?></div>

<div class="content">
    <div class="window window-border window-caption window-h-35">
        <div class="caption-begin window-m-t--9">
            <div id="floatingCirclesGMain" hidden>
                <div class="f_circleG" id="frotateG_01"></div>
                <div class="f_circleG" id="frotateG_02"></div>
                <div class="f_circleG" id="frotateG_03"></div>
                <div class="f_circleG" id="frotateG_04"></div>
                <div class="f_circleG" id="frotateG_05"></div>
                <div class="f_circleG" id="frotateG_06"></div>
                <div class="f_circleG" id="frotateG_07"></div>
                <div class="f_circleG" id="frotateG_08"></div>
            </div>
            <div><?='&nbsp;'?></div>
        </div>
        <div class="caption-text" id="form-caption">Редактирование профиля</div>

    </div>

    <noscript>
        <div class="info">У Вас в браузере заблокирован JavaScript. Разрешите JavaScript для работы сайта!</div>
    </noscript>

    <div class="submenu">
        <?php if ($tab === 1){ ?>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-main">Основное</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-avatar">Аватар</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-contacts">Контакты</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-additional">Дополнительно</span>
        <?php } ?>
        <?php if ($tab === 2){ ?>
            <span class="btn-submenu btn-submenu-interactive" id="tab-main">Основное</span>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-avatar">Аватар</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-contacts">Контакты</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-additional">Дополнительно</span>
        <?php } ?>
        <?php if ($tab === 3){ ?>
            <span class="btn-submenu btn-submenu-interactive" id="tab-main">Основное</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-avatar">Аватар</span>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-contacts">Контакты</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-additional">Дополнительно</span>
        <?php } ?>
        <?php if ($tab === 4){ ?>
            <span class="btn-submenu btn-submenu-interactive" id="tab-main">Основное</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-avatar">Аватар</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-contacts">Контакты</span>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-additional">Дополнительно</span>
        <?php } ?>
    </div>


        <section id="content-main" class="window window-border" <?= $tab === 1 ? '' : 'hidden' ?>>
            <div class="container-wrap-acEdit">
                <div class="column1">
                    <div class="caption-line-gen caption-line-left-15" id="fieldSurname">Фамилия:</div><div class="message-wrapper-line window-border" id="valueSurnameWrap">
                        <input type="text" class="message-text-line" id="valueSurname" value="<?= $cur_user->surname ?>" contentEditable />
                    </div>
                    <div class="caption-line-gen caption-line-left-15" id="fieldName">Имя:</div><div class="message-wrapper-line window-border" id="valueNameWrap">
                        <input type="text" class="message-text-line" id="valueName" value="<?= $cur_user->name ?>" contentEditable />
                    </div>
                    <div class="caption-line-gen caption-line-left-15" id="fieldMiddleName">Отчество:</div><div class="message-wrapper-line window-border" id="valueMiddleNameWrap">
                        <input type="text" class="message-text-line" id="valueMiddleName" value="<?= $cur_user->middlename ?>" contentEditable />
                    </div>
                </div>
                <div class="column2">
                    <div class="caption-line-gen caption-line-left-15" id="fieldGender">Пол:</div>
                    <div class="radio-container">
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isMas" <?= $cur_user->gender === 1 ? 'checked' : '' ?>>
                            <label for="isMas">Мужской</label>
                        </div>
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isFem" <?= $cur_user->gender === 2 ? 'checked' : '' ?>>
                            <label for="isFem">Женский</label>
                        </div>
                    </div>


                    <div class="caption-line-gen caption-line-left-15">Дата рождения:</div><div class="message-wrapper-line window-border">
                        <input type="date" id="valueDateOfBirth" class="message-text-line" contentEditable id="valueDateOfBirth">
                    </div>
                </div>
            </div>
        </section>
        <section id="content-avatar" class="window window-border" <?= $tab === 2 ? '' : 'hidden' ?>>
            <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto">
                <div class="window-button-in-panel window-border" id="button-remove">Удалить</div>
                <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
            </div>

            <div class="users-container-wrap window window-border flex-item-b16">
                <div id="paramDomain" hidden="hidden"><?=  Yii::$app->params['domain'] ?></div>
                <div class="users-avatar">
                    <div class="img-wrap-user">
                        <img id="curAvatar" class="img-user" src=<?= Yii::$app->params['doman'].'data/img/avatar/'.$path_avatar; ?> >
                    </div>
                </div>
                <div>
                    <div>
                        <div></br></div>
                        <div class="subwindow">
                            Слева отображается текущая аватарка
                        </div>
                        <div></br></div>
                        <div class="subwindow">
                            Ниже можно загрузить фотографии и выбрать одну в качестве аватарки
                        </div>
                        <div class="subwindow">
                            Все загруженные и невыбранные фотографии никому не будут отображаться, но всегда будут доступны для смены аватарки.
                        </div>
                    </div>

                </div>
            </div>

                <div class="flex" id="list-fotos">
                    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
                    <div class="flex-item add-img">
                        <div class="foto-input">
                            <div class="form-group">
                                <label class="label">
                                    <i class="material-icons">attach_file</i>
                                    <span class="title">Добавить фото</span>
                                    <form enctype='multipart/form-data' method='POST' action='' id="form">
                                        <input type="file" id="fotos" onchange="handleFiles(this.files)" multiple>
                                    </form>
                                </label>
                            </div>
                        </div>
                    </div>

                    <?php if (count($allPaths) == 0){ ?>
                        <div id="info" class="text-font text-center margin-v20 flex-item">
                            Нет фотографий
                        </div>
                        <!--<div class="window window-border flex-item"><img src="data/img/main/7_1_1.jpg" class="img-wrap"></div>
                            <div class="window window-border flex-item"><img src="/data/img/main/7_1_2.jpg" class="img-wrap"></div>
                            <div class="window window-border flex-item"><img src="/data/img/main/7_1_3.jpg" class="img-wrap"></div>
                            <div class="window window-border flex-item"><img src="/data/img/main/7_1_4.jpg" class="img-wrap"></div>
                            <div class="window window-border flex-item"><img src="/data/img/main/7_1_5.jpg" class="img-wrap"></div>
                            <div class="window window-border flex-item"><img src="/data/img/main/7_1_6.jpg" class="img-wrap"></div>
                            <div class="window window-border flex-item"><img src="/data/img/main/7_1_7.jpg" class="img-wrap"></div>-->
                    <?php } else { foreach ($allPaths as $foto): ?>
                        <?php $curPath = Yii::$app->params['doman'].'data/img/avatar/'.$foto['src']; ?>
                            <?php if($foto['src'] === $path_avatar) { ?>
                                <div class="window window-border flex-item foto-item window-colored-green"><img src="<?= $curPath ?>" class="img-wrap"></div>
                            <?php } else { ?>
                                <div class="window window-border flex-item foto-item"><img src="<?= $curPath ?>" class="img-wrap"></div>
                            <?php } ?>
                        <?php endforeach; } ?>
                </div>

        </section>
        <section id="content-contacts" class="window window-border" <?= $tab === 3 ? '' : 'hidden' ?>>
            <div class="container-wrap-acEdit">
                <div class="column1">

                    <script type="text/javascript" src="/js/geo/api.js" async></script>

                    <div class="caption">Основные</div>
                    <div class="wrap_text">
                        <div class="caption-line-gen caption-line-left-15" id="fieldCity">Город:</div><div class="message-wrapper-line window-border" id="valueCityWrap">
                            <input type="text" autocomplete="off" name="test" class="message-text-line" id="city" value="<?= $city->name ?>" placeholder="Начните вводить название" onfocus="_geo.f_Choice=CityChoice;_geo.init(this);" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldEmail">Email:</div><div class="message-wrapper-line window-border" id="valueEmailWrap">
                            <input type="text" class="message-text-line" id="valueEmail" value="<?= $cur_user->email ?>" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldLogin">Логин:</div>
                            <div class="message-wrapper-line window-border underlined-back" id="valueLoginWrap">
                            <input type="text" class="message-text-line" id="valueLogin" value="<?= $cur_user->username ?>" readonly />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldPhone">Телефон:</div><div class="message-wrapper-line window-border" id="valuePhoneWrap">
                            <input type="tel" class="message-text-line" id="valuePhone" value="<?= $cur_user->phone ?>" contentEditable />
                        </div>
                    </div>

                    <div class="columnUnvis">
                        <input type="number" id="id_city" value="<?= $cur_user->id_city ?>"/>
                    </div>

                    <div class="caption">Мессенджеры</div>
                    <div class="wrap_text">
                        <div class="caption-line-gen caption-line-left-15" id="fieldTelegram">Telegram:</div><div class="message-wrapper-line window-border" id="valueTelegramWrap">
                            <input type="text" class="message-text-line" id="valueTelegram" value="<?= $cur_user->telegram ?>" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldSkype">Skype:</div><div class="message-wrapper-line window-border" id="valueSkypeWrap">
                            <input type="text" class="message-text-line" id="valueSkype" value="<?= $cur_user->skype ?>" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldIcq">ICQ:</div><div class="message-wrapper-line window-border" id="valueIcqWrap">
                            <input type="text" class="message-text-line" id="valueIcq" value="<?= $cur_user->icq ?>" contentEditable />
                        </div>
                    </div>
                </div>
                <div class="column2">
                    <div class="caption">WWW адреса</div>
                    <div class="wrap_text">
                        <div class="caption-line-gen caption-line-left-15" id="fieldUrlVK">ВК:</div><div class="message-wrapper-line window-border" id="valueUrlVKWrap">
                            <input type="text" class="message-text-line" id="valueUrlVK" value="<?= $cur_user->url_vk ?>" placeholder="vk.com/ или @" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldUrlFB">Facebook:</div><div class="message-wrapper-line window-border" id="valueUrlFBWrap">
                            <input type="text" class="message-text-line" id="valueUrlFB" value="<?= $cur_user->url_fb ?>" placeholder="www.facebook.com/ или @" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldUrlOK">ОК:</div><div class="message-wrapper-line window-border" id="valueUrlOKWrap">
                            <input type="text" class="message-text-line" id="valueUrlOK" value="<?= $cur_user->url_ok ?>" placeholder="ok.ru/ или @" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldUrlIN">Instagram:</div><div class="message-wrapper-line window-border" id="valueUrlINWrap">
                            <input type="text" class="message-text-line" id="valueUrlIN" value="<?= $cur_user->url_in ?>" placeholder="www.instagram.com/ или @" contentEditable />
                        </div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldUrlWWW">Другой сайт:</div><div class="message-wrapper-line window-border" id="valueUrlWWWWrap">
                            <input type="text" class="message-text-line" id="valueUrlWWW" value="<?= $cur_user->url_www ?>" placeholder="" contentEditable />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="content-additional" class="window window-border" <?= $tab === 4 ? '' : 'hidden' ?>>
                <div class="caption-line-gen caption-line-left-full" id="fieldAbout">Дополнительная информация:</div>
                <div class="clearfix"></div>
                <div class="new-message-wrapper width-full multistring-min-25 window-border" id="valueUrlAboutWrap">
                    <textarea class="message-text-multistring resize_vertical_only multistring-min-25" id="valueAbout"><?= $cur_user->about ?></textarea>
                </div>
        </section>


    <div class="window window-border window-h-50 window-m-t-9">
        <div class="clearfix"></div>
        <div class="red-comment" id="red-comment"></div>
        <div class="window-button-panel">
            <div class="window-button-in-panel window-border" id="button-save">Подтвердить</div>
            <div class="window-button-in-panel window-border" id="button-close">Закрыть</div>
        </div>
    </div>


</div>

<div id="prompt-form-container">
    <div id="prompt-form" class="window window-border form-off">
        <div class="caption-wrap">
            <div class="caption-begin">
                <div id="floatingCirclesG">
                    <div class="f_circleG" id="frotateG_01"></div>
                    <div class="f_circleG" id="frotateG_02"></div>
                    <div class="f_circleG" id="frotateG_03"></div>
                    <div class="f_circleG" id="frotateG_04"></div>
                    <div class="f_circleG" id="frotateG_05"></div>
                    <div class="f_circleG" id="frotateG_06"></div>
                    <div class="f_circleG" id="frotateG_07"></div>
                    <div class="f_circleG" id="frotateG_08"></div>
                </div>
                <div><?='&nbsp;'?></div>
            </div>
            <div class="caption-text" id="form-caption">Предпросмотр загружаемых фотографий</div>
            <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
        </div>
        <div class="clearfix"></div>

        <div id="preview" class="flex">

        </div>

        <div class="window-button-panel">
            <div class="window-button-in-panel window-border" id="button-add">Подтвердить</div>
            <div class="window-button-in-panel window-border" id="button-del">Отменить</div>
        </div>
    </div>

</div>