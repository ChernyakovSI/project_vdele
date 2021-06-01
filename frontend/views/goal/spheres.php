<?php

use common\models\goal\Sphere;

$this->title = 'Сферы жизни';

?>

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
    <div class="caption-text" id="form-caption">Сферы жизни</div>

</div>

<div class="content">
    <div class="url-container-wrap">
        <div class="window url-window-right window-border url-sidebar-left">
            <div class="url-categoryList-header window-subcaption">
                Список
            </div>

            <div class="window-m-t-9"><hr class="line"></div>
            <div id="list-spheres">

                <?php foreach ($AllSpheres as $sphere): ?>
                    <div class="fin-acc-row <?= Sphere::getColorForId((integer)$sphere['id'], 1, 1) ?> interactive-only" id="<?= $sphere['id'] ?>">
                        <div class="url-col-category table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $sphere['name'] ?></div>
                            </div>
                        </div>
                        <div class="clearfix"><hr class="line"></div>
                    </div>

                <?php endforeach; ?>

            </div>


        </div>
        <div class="window window-border url-main" id="main-window">

            <div class="clearfix"></div>
            <div class="url-categoryList-header window-subcaption">
                Настройки
            </div>

            <div id="settings" class="window-m-t-9">
                <div id="info" class="text-font text-center margin-v20">
                    Выберите сферу жизни слева
                </div>

                <div id="set-panel"  class="visible-not">
                    <div>
                        <div class="caption-line-gen caption-line-left-15" id="fieldName">Название:</div>
                        <div class="message-wrapper-line window-border" id="valueNameWrap">
                            <input type="text" class="message-text-line" id="valueName" contentEditable />
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="window-button-panel window-m-t-9">
                        <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
                    </div>
                </div>

            </div>

        </div>
    </div>


</div>