<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

use common\models\Image;
use common\models\Tag;
use common\models\User;

$this->registerJsFile('@web/js/profile/users_pos_ready.js', ['position' => \yii\web\View::POS_READY]);

$this->title = 'Пользователи';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="window window-border window-caption">Пользователи (<?= count($usersAll) ?>)</div>

<div class="window window-border gap-v" id="main-window">
    <div class="Rollup">
        <input class="hide" id="hd-1" type="checkbox" <?= $gFind === true ? 'checked' : '' ?> >
        <label for="hd-1">Поиск</label>
        <div>
            <div class="">
                <div class="clearfix"></div>
                <div class="column-50">
                    <div id="fieldTag">
                        <div class="caption-line w-16">Тег:</div>
                        <div class="message-wrapper-line-gen col-message-wrapper w-66 window-border" id="valueTagWrap">
                            <input type="text" class="message-text-line" list="list_tags" id="valueTag" contentEditable value="<?= $findTag ?>" />
                            <datalist id="list_tags">
                                <?php foreach ($tags as $tag): ?>
                                    <option data-id=<?= $tag['id'] ?> data-name=<?= $tag['name'] ?>><?= $tag['name'].' ('.$tag['count_users'].')' ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="window-button-in-panel window-border gap-v-13" id="ClearTag">&#10008;</div>
                    </div>

                    <div class="clearfix"></div>
                    <div id="fieldAge" class="column-14">
                        <div class="caption-line w-16">Возраст:</div>
                    </div>
                    <div class="column-35">
                        <div id="fieldAgeFrom">
                            <div class="caption-line w-16">от</div>
                            <div class="message-wrapper-line-gen col-message-wrapper w-66 window-border" id="valueAgeFromWrap">
                                <input type="number" class="message-text-line" id="valueAgeFrom" contentEditable value="<?= $findAgeFrom ?>" />
                            </div>

                        </div>
                    </div>
                    <div class="column-35">
                        <div id="fieldAgeTo">
                            <div class="caption-line w-16">до</div>
                            <div class="message-wrapper-line-gen col-message-wrapper w-66 window-border" id="valueAgeToWrap">
                                <input type="number" class="message-text-line" id="valueAgeTo" contentEditable value="<?= $findAgeTo ?>" />
                            </div>

                        </div>
                    </div>
                    <div id="fieldAge column-10">
                        <div class="window-button-in-panel window-border gap-v-13" id="ClearAge">&#10008;</div>
                    </div>



                </div>
                <div class="column-50">
                    <div class="clearfix"></div>
                    <div id="fieldFIO">
                        <div class="caption-line w-16">ФИО:</div>
                        <div class="message-wrapper-line-gen col-message-wrapper w-66 window-border" id="valueFIOWrap">
                            <input type="text" class="message-text-line" id="valueFIO" contentEditable value="<?= $findFIO ?>" />
                        </div>
                        <div class="window-button-in-panel window-border gap-v-13" id="ClearFIO">&#10008;</div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="window-button-panel window-m-t-9">
                    <div class="window-button-in-panel window-border gap-v-13" id="btnFind">&#10004; Найти</div>
                    <div class="window-button-in-panel window-border gap-v-13" id="btnReset">Сбросить</div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="clearfix gap-v"></div>

<div class="users-center">
    <?= LinkPager::widget(['pagination' => $pagination,
        'firstPageLabel' => '<<',
        'lastPageLabel' => '>>',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'maxButtonCount' => 8,
        'disabledPageCssClass' => 'disabled',]) ?></div>

    <div class="content flex-between">
        <?php $imageClass = new Image();
        foreach ($users as $user): ?>

            <div class="users-container-wrap window window-border flex-item-b16">
                <a href="/?id=<?= $user['id'] ?>">
                <div class="users-avatar">
                    <div class="img-wrap-user">
                        <?php
                        $image = new Image();
                        $path_avatar = $image->getPathAvatarForUser($user['id']);
                        if((isset($path_avatar)) && ($path_avatar != '')) { ?>
                            <img class="img-user" src=<?= '/data/img/avatar/'.$path_avatar; ?> class="users-avatar_font">
                        <?php }
                        else {
                            if((isset($user->gender)) && ($user->gender == 2)) { ?>
                                <img class="img-user" src=<?= '/data/img/avatar/avatar_default_w.jpg'; ?> class="users-avatar_font">
                            <?php }
                            else { ?>
                                <img class="img-user" src=<?= '/data/img/avatar/avatar_default.jpg'; ?> class="users-avatar_font">
                            <?php }
                        } ?>
                    </div>
                </div>
                </a>
                <div>
                    <div>
                        <a href="/?id=<?= $user['id'] ?>">
                            <div class="h-20px content-hide">
                                <?= User::getFIO_s($user['id'], false) ?>


                            </div>
                        </a>
                        <div class="subwindow unactive">
                            <?= User::getTimeLastActivity_s($user['id']) ?>
                        </div>
                        <div></br></div>
                        <?php if($user['id'] != Yii::$app->user->identity->getId()) { ?>
                        <div class="subwindow">
                            <a href="/dialog?id=<?= $user['id'] ?>">Написать</a>
                        </div>
                        <?php } ?>
                        <?php if(($imageClass->hasFotos($user['id'], 1) > 0) || ($user['id'] === Yii::$app->user->identity->getId()) ) { ?>
                        <div class="subwindow">
                            <a href="/foto?id=<?= $user['id'] ?>">Фотоальбом</a>
                        </div>
                        <?php } ?>
                        <?php $userTags = Tag::getTagsByUser($user['id']); if(count($userTags) > 0) { ?>
                            <div class="subwindow flex-start">
                                <?php foreach ($userTags as $tag): ?>
                                    <div class="flex-item m-r-10px wrapper-tag window-border interactive
                                    <?php if($tag['color'] === '0') {
                                        echo 'col-back-inn-light';
                                    }
                                    elseif ($tag['color'] === '1') {
                                        echo 'col-back-fin-light';
                                    }
                                    elseif ($tag['color'] === '2') {
                                        echo 'col-back-hea-light';
                                    }
                                    elseif ($tag['color'] === '3') {
                                        echo 'col-back-rel-light';
                                    }
                                    elseif ($tag['color'] === '4') {
                                        echo 'col-back-edu-light';
                                    }
                                    elseif ($tag['color'] === '5') {
                                        echo 'col-back-rea-light';
                                    }
                                    elseif ($tag['color'] === '6') {
                                        echo 'col-back-psy-light';
                                    }
                                    else {
                                        echo 'col-back-tra-light';
                                    }
                                    ?>

                                    ">
                                        <div class="tagname"><?= $tag['name'] ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php } ?>

                    </div>

                </div>


            </div>
        <?php endforeach; ?>
    </div>

<div class="users-center">
<?= LinkPager::widget(['pagination' => $pagination,
    'firstPageLabel' => '<<',
    'lastPageLabel' => '>>',
    'prevPageLabel' => '<',
    'nextPageLabel' => '>',
    'maxButtonCount' => 8,
    'disabledPageCssClass' => 'disabled',]) ?></div>
