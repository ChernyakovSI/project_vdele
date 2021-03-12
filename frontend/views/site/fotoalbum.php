<?php

use \common\models\Ac;
use common\models\Image;

$this->title = 'Фотоальбом';

?>
<div class="content">
    <div class="content">
        <div class="container-foto-wrap">

            <div class="window window-border window-caption-full FIO-foto">
                <a href="/?id=<?= $user_id ?>">
                    <div class="FIO-main">
                        <?php
                        $current_ac = new Ac;
                        echo 'Фотоальбом ('.$current_ac->getFIO($user_id).')';
                        ?>
                    </div>
                </a>
            </div>

            <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto">
                <div class="window-button-in-panel window-border" id="button-remove">Удалить</div>
                <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
            </div>

            <div class="window window-border main-info-foto">
                <div class="flex" id="list-fotos">
                    <?php if($user_id == Yii::$app->user->identity->getId()) { ?>
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

                    <?php } ?>

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
                        <?php $curPath = Yii::$app->params['dataUrl'].'img/main/'.$foto['src']; ?>
                        <div class="window window-border flex-item foto-item"><a href="<?= $curPath ?>" data-lightbox="roadtrip"><img src="<?= $curPath ?>" class="img-wrap"></a></div>
                    <?php endforeach; } ?>
                </div>
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
</div>