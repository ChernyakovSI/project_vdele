<?php

use common\models\goal\Sphere;

$this->title = 'Задачи';

?>

<div id="paramPeriodFrom" hidden="hidden"><?= $periodFrom ?></div>
<div id="paramPeriodTo" hidden="hidden"><?= $periodTo ?></div>

<div class="submenu">
    <span class="btn-submenu btn-active">Общий список</span>
    <span class="btn-submenu"><a href="/goal/tasks">Задачи дня</a></span>
    <span class="btn-submenu"><a href="/goal/tasks-week">Задачи на неделю</a></span>
    <span class="btn-submenu"><a href="/goal/tasks-report">Отчет</a></span>
</div>

<div class="window window-border window-caption-2em caption-wrap">
    <div class="caption-begin"><?='&nbsp;'?></div>
    <div class="caption-text-new">Общий список задач<div><?='&nbsp;'?></div></div>
    <div class="caption-close-new">
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
</div>
<div class="clearfix"></div>

<div class="content">
    <div class="window window-border gap-v" id="main-window">
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
                    <div class="caption-line-simple-28">Сфера:</div><div class="message-wrapper-line-half window-border" id="valueSphereWrap">
                        <input type="text" class="message-text-line" list="list_sphere" id="valueSphere" value="<?= isset($sphere)?$sphere->name:'' ?>"/>
                        <datalist id="list_sphere">
                            <?php foreach ($spheres as $sphere): ?>
                                <option data-id=<?= $sphere['id'] ?>><?= $sphere['name'] ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="window-button-in-panel window-border gap-v-13" id="ClearSphere">х</div>
                </div>
                <div class="half_third">
                    <div class="w-100">
                        <div class="caption-line-half-20"></div>
                        <input type="checkbox" id="setDont" class="custom-checkbox">
                        <label for="setDont" class="interactive-only">Не будет выполнено</label>
                    </div>
                    <div class="w-100">
                        <div class="caption-line-half-20"></div>
                        <input type="checkbox" id="setProcess" class="custom-checkbox">
                        <label for="setProcess" class="interactive-only">В процессе</label>
                    </div>
                    <div class="w-100">
                        <div class="caption-line-half-20"></div>
                        <input type="checkbox" id="setDone" class="custom-checkbox">
                        <label for="setDone" class="interactive-only">Выполнено</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="window-button-panel">
            <div class="window-button-in-panel window-border" id="new-task">Добавить</div>
            <!--                <div class="window-button-in-panel window-border" id="btn-copy">Скопировать</div>-->
        </div>

        <div class="clearfix"></div>
        <div class="halfwidth-wrapper m-t-10px">
            <div class="halfwidth m-r-10px">
                <div id="header1">
                    <div class="interactive-only">
                        <div class="column-10 border-1px-bottom colNameNum">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '№' ?></div>
                            </div>
                        </div>
                        <div class="column-25 border-1px-bottom colNameDate">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Дата' ?></div>
                            </div>
                        </div>
                        <div class="column-65 border-1px-all colNameDream">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Задача' ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div id="list-dreams1">
                    <?php
                    $number = 0;
                    if(count($AllTasks) > 1) {
                        $halved = array_chunk($AllTasks, ceil(count($AllTasks)/2));
                    }
                    elseif (count($AllTasks) === 1) {
                        $halved = array_chunk($AllTasks, ceil(count($AllTasks)/2));
                        $halved[1] = [];
                    }
                    else {
                        $halved[0] = [];
                        $halved[1] = [];
                    }

                    if (count($halved[0]) == 0){ ?>

                        <div id="info1" class="text-font-5 text-center margin-v20">
                            Нет данных
                        </div>

                    <?php } else {  foreach ($halved[0] as $reg): ?>

                        <?php $curPath = 'task/'.$reg['num']; ?>
                        <div class="fin-acc-row interactive-only <?= Sphere::getColorForId((integer)$reg['id_sphere'], 1, 1) ?>" id="<?= $reg['id'] ?>">
                            <a href="<?= $curPath ?>">
                                <div class="column-10 border-1px-bottom col-back-nul colNameNum">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><div><?= ++$number ?></div>
                                            <?php
                                            if($reg['status'] == 1) {
                                                echo '<i class="fa fa-check-circle symbol_style text-center text-color-green" aria-hidden="true"></i>';
                                            } elseif ($reg['status'] == 2) {
                                                echo '<i class="fa fa-ban symbol_style text-center text-color-red" aria-hidden="true"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="column-25 border-1px-bottom col-back-nul colNameDate <?= Ambition::getColorForDateline($reg['date'], $reg['status'], 4) ?>">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= date("d.m.Y", $reg['date']).(($reg['status'] != 0)?' / '.date("d.m.Y", $reg['dateDone']):'') ?></div>
                                    </div>
                                </div>
                                <div class="column-65 border-1px-all col-back-nul colNameDream">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= $reg['title'] ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php endforeach; } ?>
                </div>
            </div>

            <div class="halfwidth">

                <div id="header2">
                    <div class="interactive-only">
                        <div class="column-10 border-1px-bottom colNameNum">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '№' ?></div>
                            </div>
                        </div>
                        <div class="column-25 border-1px-bottom colNameDate">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Дата' ?></div>
                            </div>
                        </div>
                        <div class="column-65 border-1px-all colNameDreams">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Задача' ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div id="list-dreams2">
                    <?php
                    if (count($halved[1]) == 0){ ?>

                        <div id="info2" class="text-font-5 text-center margin-v20">
                            Нет данных
                        </div>

                    <?php } else {  foreach ($halved[1] as $reg): ?>

                        <?php $curPath = 'task/'.$reg['num']; ?>
                        <div class="fin-acc-row interactive-only <?= Sphere::getColorForId((integer)$reg['id_sphere'], 1, 1) ?>" id="<?= $reg['id'] ?>">
                            <a href="<?= $curPath ?>">
                                <div class="column-10 border-1px-bottom col-back-nul colNameNum">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><div><?= ++$number ?></div>
                                            <?php
                                            if($reg['status'] == 1) {
                                                echo '<i class="fa fa-check-circle symbol_style text-center text-color-green" aria-hidden="true"></i>';
                                            } elseif ($reg['status'] == 2) {
                                                echo '<i class="fa fa-ban symbol_style text-center text-color-red" aria-hidden="true"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="column-25 border-1px-bottom col-back-nul colNameDate <?= Ambition::getColorForDateline($reg['date'], $reg['status'], 4) ?>">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= date("d.m.Y", $reg['date']).(($reg['status'] != 0)?' / '.date("d.m.Y", $reg['dateDone']):'') ?></div>
                                    </div>
                                </div>
                                <div class="column-65 border-1px-all col-back-nul colNameDream">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= $reg['title'] ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php endforeach; } ?>

                </div>
            </div>
        </div>


        <div class="clearfix"></div>


    </div>
</div>