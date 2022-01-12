<?php

use common\models\goal\Sphere;
use common\models\goal\Ambition;

$this->title = 'Зачетка';

?>

<div id="paramCurDate" hidden="hidden"><?= $curDate ?></div>
<div id="paramDate" hidden="hidden"><?php
    if (count($AllPriority) > 0) {
        if(count($AllPriority['sem']) > 0) {
            echo $AllPriority['sem']['date'];
        } else {
            echo '';
        }
    } else {
        echo '';
    } ?>
</div>
<div id="paramDateFinish" hidden="hidden"><?php
    if (count($AllPriority) > 0) {
        if(count($AllPriority['sem']) > 0) {
            echo strtotime("tomorrow", $AllPriority['sem']['dateFinish']) - 1;
        } else {
            echo '';
        }
    } else {
        echo '';
    } ?>
</div>
<div id="paramName" hidden="hidden"><?php
    if (count($AllPriority) > 0) {
        if(count($AllPriority['sem']) > 0) {
            echo $AllPriority['sem']['name'];
        } else {
            echo '';
        }
    } else {
        echo '';
    } ?>
</div>
<div id="paramID" hidden="hidden"><?php
    if (count($AllPriority) > 0) {
        if(count($AllPriority['sem']) > 0) {
            echo $AllPriority['sem']['id'];
        } else {
            echo 0;
        }
    } else {
        echo 0;
    } ?>
</div>
<div id="paramStart" hidden="hidden"><?php
    if (count($AllPriority) > 0) {
        if(count($AllPriority['option']) > 0) {
            echo $AllPriority['option']['start'];
        } else {
            echo 0;
        }
    } else {
        echo 0;
    } ?>
</div>
<div id="paramFinish" hidden="hidden"><?php
    if (count($AllPriority) > 0) {
        if(count($AllPriority['option']) > 0) {
            echo strtotime("tomorrow", $AllPriority['option']['finish']) - 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    } ?>
</div>

<div class="submenu">
    <?php
        $name = 'Цель';
        $names = 'Зачетка';
        $colNameDate = 'Срок';
    ?>
        <span class="btn-submenu"><a href="/goal/dreams">Мечты</a></span>
        <span class="btn-submenu"><a href="/goal/wishes">Желания</a></span>
        <span class="btn-submenu"><a href="/goal/intents">Намерения</a></span>
        <span class="btn-submenu btn-active">Цели</span>
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
        <span class="btn-submenu"><a href="/goal/goals">Список</a></span>
        <span class="btn-submenu btn-active">Зачетка</span>
        <span class="btn-submenu"><a href="/goal/results">Итоги</a></span>
        <!--<span class="btn-submenu"><a href="/goal/dreams-foto">Доска</a></span>-->
    </div>

    <div class="window window-border gap-v" id="main-window">
        <div class="clearfix"></div>

        <div>
            <div class="column-5" id="arrow-back">
                <div class="like-table h-70px w-100 ia-background" id="arrow-back-high">
                    <div class="like-cell text-center">
                        <i class="arrow arrow-left" id="symBack"></i>
                    </div>
                </div>
            </div>
            <div class="div-w-60">
                <div class="message-caption-line-12">Название:</div>
                <div class="message-wrapper-line-88 window-border">
                    <input type="text" class="message-text-line" contentEditable id="valueName">
                </div>
            </div>
            <div class="div-w-30">
                <div class="message-caption-line-2symbols">c:</div>
                <div class="message-wrapper-line-date window-border" id="valuePeriodFromWrap">
                    <input type="date" class="message-text-line" contentEditable id="valuePeriodFrom">
                </div>
                <div class="message-caption-line-2symbols">по:</div>
                <div class="message-wrapper-line-date window-border" id="valuePeriodToWrap">
                    <input type="date" class="message-text-line" contentEditable id="valuePeriodTo">
                </div>
            </div>
            <div class="column-5" id="arrow-forward">
                <div class="like-table h-70px w-100 ia-background" id="arrow-forward-high">
                    <div class="like-cell text-center">
                        <i class="arrow arrow-right" id="symForward"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="window-button-panel" id="btnSave" hidden="hidden">
            <div class="window-button-in-panel window-border" id="new-reg">Сохранить</div>
            <!--                <div class="window-button-in-panel window-border" id="btn-copy">Скопировать</div>-->
        </div>
        <div class="clearfix"></div>
        <div class="red-comment" id="red-comment"></div>

        <div class="halfwidth-wrapper m-t-10px">
            <div class="halfwidth m-r-10px">
                <div id="header1">
                    <div class="text-center text-bold">Экзамены</div>
                    <div class="clearfix"></div>
                    <div class="interactive-only">
                        <div class="column-7 border-1px-bottom colNameNum">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '№' ?></div>
                            </div>
                        </div>
                        <div class="column-16 border-1px-bottom colNameDeadline">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $colNameDate ?></div>
                            </div>
                        </div>
                        <div class="column-46 border-1px-bottom colNameDreams">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $name ?></div>
                            </div>
                        </div>
                        <div class="column-15 border-1px-bottom colNameMark">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption">Отметка</div>
                            </div>
                        </div>
                        <div class="column-16 border-1px-all colNameDateDone">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption">Дата</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div id="list-dreams1">
                    <?php if (count($AllPriority['exam']) == 0){ ?>

                    <div id="info1" class="text-font-5 text-center margin-v20">
                        Нет данных
                    </div>

                    <?php } else {  $number = 0;
                        foreach ($AllPriority['exam'] as $reg): ?>

                        <?php $curPath = 'dream/'.$reg['num']; ?>

                    <div class="fin-acc-row interactive-only <?= Sphere::getColorForId((integer)$reg['id_sphere'], 1, 1) ?>" id="<?= $reg['id'] ?>">
                        <a href="<?= $curPath ?>">
                            <div class="column-7 border-1px-bl col-back-nul colNameNum">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line text-center"><div><?= ++$number ?></div>

                                    </div>
                                </div>
                            </div>
                            <div class="column-16 border-1px-bl col-back-nul colNameDeadline">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line text-center"><?= date("d.m.Y", $reg['date']) ?></div>
                                </div>
                            </div>
                            <div class="column-46 border-1px-bl col-back-nul colNameDream">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line text-center"><?= $reg['title'] ?></div>
                                </div>
                            </div>
                            <div class="column-15 border-1px-bl col-back-nul colNameMark">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line text-center <?= ($reg['status'] == 1)?Ambition::getClassMark($reg['result_mark'], true):'' ?>"><?= ($reg['status'] == 1)?Ambition::getNameMark($reg['result_mark']):'' ?></div>
                                </div>
                            </div>
                            <div class="column-16 border-1px-rbl col-back-nul colNameDateDone">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line text-center"><?= ($reg['status'] == 1)?date("d.m.Y", $reg['dateDone']):'' ?></div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <?php endforeach; } ?>

                </div>
            </div>

            <div class="halfwidth">

                <div id="header2">
                    <div class="text-center text-bold">Зачеты</div>
                    <div class="clearfix"></div>
                    <div class="interactive-only">
                        <div class="column-7 border-1px-bottom colNameNum">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '№' ?></div>
                            </div>
                        </div>
                        <div class="column-16 border-1px-bottom colNameDeadline">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $colNameDate ?></div>
                            </div>
                        </div>
                        <div class="column-46 border-1px-bottom colNameDreams">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $name ?></div>
                            </div>
                        </div>
                        <div class="column-15 border-1px-bottom colNameMark">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption">Отметка</div>
                            </div>
                        </div>
                        <div class="column-16 border-1px-all colNameDateDone">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption">Дата</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div id="list-dreams2">


                    <?php if (count($AllPriority['zachet']) == 0){ ?>

                        <div id="info1" class="text-font-5 text-center margin-v20">
                            Нет данных
                        </div>

                    <?php } else {  $number = 0;
                        foreach ($AllPriority['zachet'] as $reg): ?>

                            <?php $curPath = 'dream/'.$reg['num']; ?>

                            <div class="fin-acc-row interactive-only <?= Sphere::getColorForId((integer)$reg['id_sphere'], 1, 1) ?>" id="<?= $reg['id'] ?>">
                                <a href="<?= $curPath ?>">
                                    <div class="column-7 border-1px-bl col-back-nul colNameNum">
                                        <div class="message-wrapper-title">
                                            <div class="message-text-line text-center"><div><?= ++$number ?></div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="column-16 border-1px-bl col-back-nul colNameDeadline">
                                        <div class="message-wrapper-title">
                                            <div class="message-text-line text-center"><?= date("d.m.Y", $reg['date']) ?></div>
                                        </div>
                                    </div>
                                    <div class="column-46 border-1px-bl col-back-nul colNameDream">
                                        <div class="message-wrapper-title">
                                            <div class="message-text-line text-center"><?= $reg['title'] ?></div>
                                        </div>
                                    </div>
                                    <div class="column-15 border-1px-bl col-back-nul colNameMark">
                                        <div class="message-wrapper-title">
                                            <div class="message-text-line text-center <?= ($reg['status'] == 1)?Ambition::getClassMark($reg['result_mark'], false):'' ?>"><?= ($reg['status'] == 1)?Ambition::getNameMark($reg['result_mark'], false):'' ?></div>
                                        </div>
                                    </div>
                                    <div class="column-16 border-1px-rbl col-back-nul colNameDateDone">
                                        <div class="message-wrapper-title">
                                            <div class="message-text-line text-center"><?= ($reg['status'] == 1)?date("d.m.Y", $reg['dateDone']):'' ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        <?php endforeach; } ?>


                </div>
            </div>
        </div>


        <div class="clearfix"></div>
        <div class="window-button-panel m-t-10px" id="btnDelete" hidden="hidden">
            <div class="window-button-in-panel window-border" id="del-reg">Удалить</div>
            <!--                <div class="window-button-in-panel window-border" id="btn-copy">Скопировать</div>-->
        </div>

    </div>
</div>