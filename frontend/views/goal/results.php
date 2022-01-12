<?php

use common\models\goal\Sphere;
use common\models\goal\Ambition;

$this->title = 'Итоги';

?>

<div id="paramPeriodType" hidden="hidden"><?= $PeriodType ?></div>

<div class="submenu">
    <?php
        $names = 'Итоги';
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
        <span class="btn-submenu"><a href="/goal/priority">Зачетка</a></span>
        <span class="btn-submenu btn-active">Итоги</span>
        <!--<span class="btn-submenu"><a href="/goal/dreams-foto">Доска</a></span>-->
    </div>

    <div class="window window-border gap-v" id="main-window">
        <div class="clearfix"></div>

        <div class="m-t-10px">

            <div id="header1">
                <div class="interactive-only">
                    <div class="column-7 border-1px-bottom colNameNum">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"></div>
                        </div>
                    </div>
                    <div class="column-39 border-1px-bottom colNameSphere">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"></div>
                        </div>
                    </div>
                    <div class="column-27 border-1px-bottom">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Все цели' ?></div>
                        </div>
                    </div>
                    <div class="column-27 border-1px-all">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Цели из зачетки' ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="header1">
                <div class="interactive-only">
                    <div class="column-7 border-1px-bottom colNameNum">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= '№' ?></div>
                        </div>
                    </div>
                    <div class="column-39 border-1px-bottom colNameSphere">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Сфера' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bottom colNameTotal">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Всего' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bottom colNameDone">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Выполнено' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-all colNameDoneProcent">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Процент' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bottom colNameTotal">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Всего' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bottom colNameDone">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Зачтено' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-all colNameDoneProcent">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Процент' ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div id="list-result">
                <?php if (count($AllPriority) == 0){ ?>

                <div id="info1" class="text-font-5 text-center margin-v20">
                    Нет данных
                </div>

                <?php } else {  $number = 0;
                    foreach ($AllPriority as $reg): ?>

                <div class="fin-acc-row interactive-only <?= Sphere::getColorForId((integer)$reg['id_sphere'], 1, 1) ?>">
                    <div class="column-7 border-1px-bl col-back-nul colNameNum">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><div><?= ++$number ?></div>

                            </div>
                        </div>
                    </div>
                    <div class="column-39 border-1px-bl col-back-nul colNameSphere">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><?= $reg['sphere'] ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl col-back-nul colNameTotal">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><?= $reg['total'] ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl col-back-nul colNameDone">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><?= $reg['done'] ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-rbl col-back-nul colNameDoneProcent">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><?= number_format((float)$reg['doneProcent'], 2, '.', '')  ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl col-back-nul colNameTotal">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><?= $reg['totalZ'] ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl col-back-nul colNameDone">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><?= $reg['doneZ'] ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-rbl col-back-nul colNameDoneProcent">
                        <div class="message-wrapper-title">
                            <div class="message-text-line text-center"><?= number_format((float)$reg['doneZProcent'], 2, '.', '')  ?></div>
                        </div>
                    </div>
                </div>

                <?php endforeach; } ?>
            </div>

        </div>

    </div>
</div>