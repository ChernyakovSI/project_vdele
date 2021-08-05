<?php

use common\models\goal\Sphere;
use common\models\goal\Note;

$this->title = 'Заметки';

?>



<div class="content">
    <div class="container-foto-wrap">

        <div class="window window-border window-caption window-h-35 FIO-foto">
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
            <div class="caption-text" id="form-caption">Заметки</div>

        </div>

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-remove">Удалить</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="Rollup">
                <input class="hide" id="hd-1" type="checkbox">
                <label for="hd-1">Настройки списка</label>
                <div>
                    <div class="half_third">
                        <div class="caption-line-half-20">c:</div>
                        <div class="message-wrapper-line-half window-border">
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodFrom">
                        </div>
                        <div class="caption-line-half-20">по:</div>
                        <div class="message-wrapper-line-half window-border">
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodTo">
                        </div>
                    </div>
                    <div class="half_third">

                    </div>
                    <div class="half_third">

                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="m-t-10px"></div>

            <div class="flex" id="list-fotos">
                <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
                <a href="note">
                    <div class="flex-item add-img">
                        <div class="foto-input">
                            <div class="form-group">
                                <label class="label">
                                    <i class="material-icons">content_paste</i>
                                    <span class="title">Добавить</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </a>


                <?php if (count($AllNotes) == 0){ ?>
                    <div id="info" class="text-font text-center margin-v20 flex-item">
                        Нет заметок
                    </div>
                    <!--<div class="window window-border flex-item"><img src="data/img/main/7_1_1.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_2.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_3.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_4.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_5.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_6.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_7.jpg" class="img-wrap"></div>-->
                <?php } else { foreach ($AllNotes as $note): ?>
                    <?php $curPath = 'note/'.$note['num']; ?>
                    <div class="window window-border flex-item foto-item <?= Sphere::getColorForId((integer)$note['id_sphere'], 1, 1) ?>"><a href="<?= $curPath ?>">
                            <div class="h-150px w-180px ">
                                <div class="subwindow hm-80 w-100 content-hide">
                                    <div class="like-table h-100 w-100">
                                        <p class="text-center like-cell text-s-16px"><?= $note['title'] ?></p>
                                    </div>
                                </div>
                                <div class="subwindow h-20 like-table w-100 content-hide">
                                    <p class="text-right like-cell text-s-20px <?= Note::getColorForDate($note['date']) ?>"><?= date("d.m.Y H:i:s", $note['date']) ?></p>
                                </div>
                            </div>
                        </a></div>
                <?php endforeach; } ?>
            </div>
        </div>


    </div>
</div>

