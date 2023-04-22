<?php

use common\models\goal\Sphere;
use common\models\goal\Note;

$this->title = 'Дневники';

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
            <div class="caption-text" id="form-caption">Дневники</div>

        </div>

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-remove">Удалить</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="Rollup">
                <input class="hide" id="hd-1" type="checkbox">
                <label for="hd-1">Настройки списка</label>
                <div class="w-100">&nbsp
                    <div class="column-33 float-left">
                        <div class="message-caption-line w-20 w-m-75px">Первые:</div>
                        <div class="float-left w-3">&nbsp</div>
                        <div class="message-wrapper-line w-47 window-border w-m-43px">
                            <input type="number" class="message-text-line" id="valuePriority" value="<?= $showFirst ?>">
                        </div>
                    </div>
                    <div class="column-33 float-left">
                        <div class="message-wrap-line w-80 window-border" id="valueSphereWrap">
                            <input type="text" class="message-text-line" placeholder="Сфера" list="list_sphere" id="valueSphere" value="<?= isset($data) && $data->id_sphere>0 ? $spheres[$data->id_sphere-1]['name'] : '' ?>"/>
                            <datalist id="list_sphere">
                                <?php foreach ($spheres as $sphere): ?>
                                    <option data-id=<?= $sphere['id'] ?>><?= $sphere['name'] ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="w-20 float-left">
                            <div class="float-left">&nbsp</div>
                            <div class="window-button-in-panel-percent window-border gap-v-13" id="ClearSphere">&#10008;</div>
                        </div>
                    </div>

                    <div class="column-33 float-left">
                        <div class="w-100 float-left m-t-20px">
                            <input type="checkbox" id="setIsPublic" class="custom-checkbox w-1-5em">
                            <label for="setIsPublic" class="interactive-only">Только опубликованные</label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="clearfix"></div>
            <div class="m-t-10px"></div>

            <div class="flex" id="list-fotos">
                <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
                <a href="diary">
                    <div class="flex-item add-img">
                        <div class="foto-input">
                            <div class="form-group">
                                <label class="label">
                                    <i class="material-icons">local_library</i>
                                    <span class="title">Добавить</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </a>


                <?php if (count($AllDiaries) == 0){ ?>
                    <div id="info" class="text-font text-center margin-v20 flex-item">
                        Нет дневников
                    </div>
                    <!--<div class="window window-border flex-item"><img src="data/img/main/7_1_1.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_2.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_3.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_4.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_5.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_6.jpg" class="img-wrap"></div>
                        <div class="window window-border flex-item"><img src="/data/img/main/7_1_7.jpg" class="img-wrap"></div>-->
                <?php } else { foreach ($AllDiaries as $diary): ?>
                    <?php $curPath = 'diary/'.$diary['id']; ?>
                    <div class="window window-border flex-item foto-item <?= Sphere::getColorForId((integer)$diary['id_sphere'], 1, 1) ?>"><a href="<?= $curPath ?>">
                            <div class="h-150px w-180px ">
                                <div class="subwindow hm-80 w-100 content-hide">
                                    <div class="like-table h-100 w-100">
                                        <p class="text-center like-cell text-s-16px"><?= $diary['title'] ?></p>
                                    </div>
                                </div>
                                <div class="subwindow h-20 like-table w-100 content-hide">
                                    <p class="text-right like-cell text-s-20px <?= Note::getColorForDate($diary['updated_at']) ?>"><?= date("d.m.Y H:i:s", $diary['updated_at']) ?></p>
                                </div>
                            </div>
                        </a></div>
                <?php endforeach; } ?>
            </div>
        </div>


    </div>
</div>

