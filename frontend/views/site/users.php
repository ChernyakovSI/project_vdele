<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

use common\models\Image;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="window window-border window-caption">Пользователи (<?= count($usersAll) ?>)</div>


<div class="users-center">
    <?= LinkPager::widget(['pagination' => $pagination,
        'firstPageLabel' => '<<',
        'lastPageLabel' => '>>',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'maxButtonCount' => 8,
        'disabledPageCssClass' => 'disabled',]) ?></div>

    <div class="content">
        <?php foreach ($users as $user): ?>

            <div class="users-container-wrap window window-border">
                <a href="/?id=<?= $user->getId() ?>">
                <div class="users-avatar">
                    <?php
                    $image = new Image();
                    $path_avatar = $image->getPathAvatarForUser($user->id);
                    if((isset($path_avatar)) && ($path_avatar != '')) { ?>
                        <img src=<?= '/data/img/avatar/'.$path_avatar; ?> class="users-avatar_font">
                    <?php }
                    else {
                        if((isset($user->gender)) && ($user->gender == 2)) { ?>
                            <img src=<?= '/data/img/avatar/avatar_default_w.jpg'; ?> class="users-avatar_font">
                        <?php }
                        else { ?>
                            <img src=<?= '/data/img/avatar/avatar_default.jpg'; ?> class="users-avatar_font">
                        <?php }
                    } ?>
                </div>
                </a>
                <div>
                    <div>
                        <a href="/?id=<?= $user->getId() ?>">
                            <?= Html::encode("{$user->getFIO($user->id, false)}") ?>
                        </a>
                        <div class="subwindow unactive">
                            <?= Html::encode("{$user->getTimeLastActivity()}") ?>
                        </div>
                        <?php if($user->getId() != Yii::$app->user->identity->getId()) { ?>
                        <div></br></div>
                        <div class="subwindow">
                            <a href="/dialog?id=<?= $user->getId() ?>">Написать</a>
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
