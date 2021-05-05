<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

use common\models\Image;
use common\models\Tag;

$this->title = 'Пользователи';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="window window-border window-caption">Пользователи (<?= count($usersAll) ?>)</div>

<!--<div class="window window-border gap-v" id="main-window">
    <div class="Rollup">
        <input class="hide" id="hd-1" type="checkbox">
        <label for="hd-1">Настройки списка</label>
        <div>
            <div class="">
                <div class="clearfix"></div>
                <div class="flex" id="container-tags">
                </div>
                <div class="clearfix"></div>
                <div id="fieldTag">
                    <div class="caption-line w-16">Тег:</div>
                    <div class="message-wrapper-line-gen col-message-wrapper w-66 window-border" id="valueTagWrap">
                        <input type="text" class="message-text-line" list="list_tags" id="valueTag" contentEditable />
                        <datalist id="list_tags">
                            <?php //foreach ($tags as $tag): ?>
                                <option data-id=<?= ''//$tag['id'] ?> data-name=<?= ''//$tag['name'] ?>><?= ''//$tag['name'].' ('.$tag['count_users'].')' ?></option>
                            <?php //endforeach; ?>
                        </datalist>
                    </div>
                    <div class="window-button-in-panel window-border gap-v-13" id="EnterTag">&#10004;</div>
                    <div class="window-button-in-panel window-border gap-v-13" id="ClearTag">&#10008;</div>
                </div>
            </div>
        </div>
    </div>
</div>
-->

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
                <a href="/?id=<?= $user->getId() ?>">
                <div class="users-avatar">
                    <div class="img-wrap-user">
                        <?php
                        $image = new Image();
                        $path_avatar = $image->getPathAvatarForUser($user->id);
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
                        <a href="/?id=<?= $user->getId() ?>">
                            <div class="h-20px content-hide">
                                <?= Html::encode("{$user->getFIO($user->id, false)}") ?>
                            </div>
                        </a>
                        <div class="subwindow unactive">
                            <?= Html::encode("{$user->getTimeLastActivity()}") ?>
                        </div>
                        <div></br></div>
                        <?php if($user->getId() != Yii::$app->user->identity->getId()) { ?>
                        <div class="subwindow">
                            <a href="/dialog?id=<?= $user->getId() ?>">Написать</a>
                        </div>
                        <?php } ?>
                        <?php if(($imageClass->hasFotos($user->getId(), 1) > 0) || ($user->getId() === Yii::$app->user->identity->getId()) ) { ?>
                        <div class="subwindow">
                            <a href="/foto?id=<?= $user->getId() ?>">Фотоальбом</a>
                        </div>
                        <?php } ?>
                        <?php $userTags = Tag::getTagsByUser($user->id); if(count($userTags) > 0) { ?>
                            <div class="subwindow flex-start">
                                <?php foreach ($userTags as $tag): ?>
                                    <div class="flex-item m-l-10px wrapper-tag window-border
                                    <?php if($tag['id'] % 8 === 0) {
                                        echo 'col-back-inn-light';
                                    }
                                    elseif ($tag['id'] % 8 === 1) {
                                        echo 'col-back-fin-light';
                                    }
                                    elseif ($tag['id'] % 8 === 2) {
                                        echo 'col-back-hea-light';
                                    }
                                    elseif ($tag['id'] % 8 === 3) {
                                        echo 'col-back-rel-light';
                                    }
                                    elseif ($tag['id'] % 8 === 4) {
                                        echo 'col-back-edu-light';
                                    }
                                    elseif ($tag['id'] % 8 === 5) {
                                        echo 'col-back-rea-light';
                                    }
                                    elseif ($tag['id'] % 8 === 6) {
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
