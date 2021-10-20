<?php

use common\models\goal\Sphere;

$this->title = 'Мечты';

?>

<div id="paramPeriodFrom" hidden="hidden"><?= $periodFrom ?></div>
<div id="paramPeriodTo" hidden="hidden"><?= $periodTo ?></div>
<div id="paramLevel" hidden="hidden"><?= $level ?></div>

<div class="submenu">
    <?php
        $name = '';
        $names = '';
        $colNameDate = 'Создано';
        if($level == 1) {
            $name = 'Мечта';
            $names = 'Мечты';
            $colNameDate = 'Создано';
            ?>
        <span class="btn-submenu btn-active">Мечты</span>
        <span class="btn-submenu"><a href="/goal/wishes">Желания</a></span>
        <span class="btn-submenu"><a href="/goal/intents">Намерения</a></span>
        <span class="btn-submenu"><a href="/goal/goals">Цели</a></span>
    <?php } elseif($level == 2) {
            $name = 'Желание';
            $names = 'Желания';
            $colNameDate = 'Создано';
            ?>
        <span class="btn-submenu"><a href="/goal/dreams">Мечты</a></span>
        <span class="btn-submenu btn-active">Желания</a></span>
        <span class="btn-submenu"><a href="/goal/intents">Намерения</a></span>
        <span class="btn-submenu"><a href="/goal/goals">Цели</a></span>
    <?php } elseif($level == 3) {
            $name = 'Намерение';
            $names = 'Намерения';
            $colNameDate = 'Создано';
            ?>
        <span class="btn-submenu"><a href="/goal/dreams">Мечты</a></span>
        <span class="btn-submenu"><a href="/goal/wishes">Желания</a></span>
        <span class="btn-submenu btn-active">Намерения</span>
        <span class="btn-submenu"><a href="/goal/goals">Цели</a></span>
    <?php } elseif($level == 4) {
            $name = 'Цель';
            $names = 'Цели';
            $colNameDate = 'Срок';
            ?>
        <span class="btn-submenu"><a href="/goal/dreams">Мечты</a></span>
        <span class="btn-submenu"><a href="/goal/wishes">Желания</a></span>
        <span class="btn-submenu"><a href="/goal/intents">Намерения</a></span>
        <span class="btn-submenu btn-active">Цели</span>
    <?php } ?>
</div>

<div class="window window-border window-caption-2em caption-wrap">
    <div class="caption-begin"><?='&nbsp;'?></div>
    <div class="caption-text-new"><?= $names ?><div><?='&nbsp;'?></div></div>
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
    <div class="submenu">
        <span class="btn-submenu btn-active">Список</span>
        <!--<span class="btn-submenu"><a href="/goal/dreams-foto">Доска</a></span>-->
    </div>

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
            <div class="window-button-in-panel window-border" id="new-reg">Добавить</div>
            <!--                <div class="window-button-in-panel window-border" id="btn-copy">Скопировать</div>-->
        </div>

        <div class="clearfix"></div>
        <div class="halfwidth-wrapper m-t-10px">
            <div class="halfwidth m-r-10px">
                <div id="header1">
                    <div class="interactive-only">
                        <div class="column-10 border-1px-bottom">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '№' ?></div>
                            </div>
                        </div>
                        <div class="column-25 border-1px-bottom">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $colNameDate ?></div>
                            </div>
                        </div>
                        <div class="column-65 border-1px-all">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $name ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div id="list-dreams1">
                    <?php
                    $number = 0;
                    if(count($AllDreams) > 1) {
                        $halved = array_chunk($AllDreams, ceil(count($AllDreams)/2));
                    }
                    elseif (count($AllDreams) === 1) {
                        $halved = array_chunk($AllDreams, ceil(count($AllDreams)/2));
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

                        <?php $curPath = 'dream/'.$reg['num']; ?>
                        <div class="fin-acc-row interactive-only <?= Sphere::getColorForId((integer)$reg['id_sphere'], 1, 1) ?>" id="<?= $reg['id'] ?>">
                            <a href="<?= $curPath ?>">
                                <div class="column-10 border-1px-bottom col-back-nul">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= ++$number ?></div>
                                    </div>
                                </div>
                                <div class="column-25 border-1px-bottom col-back-nul">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= date("d.m.Y", ($level == 4)?$reg['date']:$reg['created_at']) ?></div>
                                    </div>
                                </div>
                                <div class="column-65 border-1px-all col-back-nul">
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
                        <div class="column-10 border-1px-bottom">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '№' ?></div>
                            </div>
                        </div>
                        <div class="column-25 border-1px-bottom">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $colNameDate ?></div>
                            </div>
                        </div>
                        <div class="column-65 border-1px-all">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $name ?></div>
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

                        <?php $curPath = 'dream/'.$reg['num']; ?>
                        <div class="fin-acc-row interactive-only <?= Sphere::getColorForId((integer)$reg['id_sphere'], 1, 1) ?>" id="<?= $reg['id'] ?>">
                            <a href="<?= $curPath ?>">
                                <div class="column-10 border-1px-bottom col-back-nul">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= ++$number ?></div>
                                    </div>
                                </div>
                                <div class="column-25 border-1px-bottom col-back-nul">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?= date("d.m.Y", ($level == 4)?$reg['date']:$reg['created_at']) ?></div>
                                    </div>
                                </div>
                                <div class="column-65 border-1px-all col-back-nul">
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