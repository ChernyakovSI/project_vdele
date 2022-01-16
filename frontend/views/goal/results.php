<?php

use common\models\goal\Sphere;
use common\models\goal\Ambition;

$this->title = 'Итоги';

?>

<div id="paramPeriodType" hidden="hidden"><?= $PeriodType ?></div>
<div id="paramID" hidden="hidden"><?php
    if (count($CurPriority) > 0) {
        if(count($CurPriority['sem']) > 0) {
            echo $CurPriority['sem']['id'];
        } else {
            echo 0;
        }
    } else {
        echo 0;
    } ?>
</div>
<div id="paramDate" hidden="hidden"><?php
    if (count($CurPriority) > 0) {
        if(count($CurPriority['sem']) > 0) {
            echo $CurPriority['sem']['date'];
        } else {
            echo '';
        }
    } else {
        echo '';
    } ?>
</div>
<div id="paramDateFinish" hidden="hidden"><?php
    if (count($CurPriority) > 0) {
        if(count($CurPriority['sem']) > 0) {
            echo strtotime("tomorrow", $CurPriority['sem']['dateFinish']) - 1;
        } else {
            echo '';
        }
    } else {
        echo '';
    } ?>
</div>
<div id="paramName" hidden="hidden"><?php
    if (count($CurPriority) > 0) {
        if(count($CurPriority['sem']) > 0) {
            echo $CurPriority['sem']['name'];
        } else {
            echo '';
        }
    } else {
        echo '';
    } ?>
</div>
<div id="paramDateFrom" hidden="hidden"><?= $dateFrom ?></div>
<div id="paramDateTo" hidden="hidden"><?= $dateTo ?></div>

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

            <div class="Rollup">
                <input class="hide" id="hd-1" type="checkbox">
                <label for="hd-1">Настройки списка</label>
                <div>
                    <div class="half_third">
                        <div class="w-100">
                            <div class="caption-line-half-20"></div>
                            <?php if($PeriodType == 0) { ?>
                            <input type="radio" id="setPeriodAll" name="setPeriod" class="custom-checkbox custom-checkbox-radio" checked>
                            <?php } else { ?>
                                <input type="radio" id="setPeriodAll" name="setPeriod" class="custom-checkbox custom-checkbox-radio">
                            <?php } ?>
                            <label for="setPeriodAll" class="interactive-only">Весь период</label>
                        </div>
                        <div class="w-100">
                            <div class="caption-line-half-20"></div>
                            <?php if($PeriodType == 1) { ?>
                            <input type="radio" id="setPeriodPriority" name="setPeriod" class="custom-checkbox custom-checkbox-radio" checked>
                            <?php } else { ?>
                                <input type="radio" id="setPeriodPriority" name="setPeriod" class="custom-checkbox custom-checkbox-radio">
                            <?php } ?>
                            <label for="setPeriodPriority" class="interactive-only">По периоду зачетки</label>
                        </div>
                        <div class="w-100">
                            <div class="caption-line-half-20"></div>
                            <?php if($PeriodType == 2) { ?>
                            <input type="radio" id="setPeriodManual" name="setPeriod" class="custom-checkbox custom-checkbox-radio" checked>
                            <?php } else { ?>
                                <input type="radio" id="setPeriodManual" name="setPeriod" class="custom-checkbox custom-checkbox-radio">
                            <?php } ?>
                            <label for="setPeriodManual" class="interactive-only">Собственный период</label>
                        </div>
                    </div>
                    <div class="column-66">
                        <?php if($PeriodType == 1) { ?>
                            <div class="message-wrapper-line-half-70 window-border" id="wrapValuePriority">
                        <?php } else { ?>
                            <div class="message-wrapper-line-half-70 window-border" id="wrapValuePriority" hidden="hidden">
                        <?php } ?>
                            <input type="text" class="message-text-line" list="list_priority_sel" id="selValuePriority" contentEditable placeholder="Период зачетки"/>
                            <datalist id="list_priority_sel">
                                <?php foreach ($AllPriority as $priority): ?>
                                    <option data-id=<?= $priority['id'] ?>><?= $priority['name'].' ('.
                                        date("d.m.Y", $priority['date']).' - '.date("d.m.Y", $priority['dateFinish']).')' ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <?php if($PeriodType == 1) { ?>
                        <div class="window-button-in-panel window-border gap-v-13" id="btnClearPriority">х</div>
                        <?php } else { ?>
                            <div class="window-button-in-panel window-border gap-v-13" id="btnClearPriority" hidden="hidden">х</div>
                        <?php } ?>

                        <?php if($PeriodType == 2) { ?>
                            <div class="message-caption-line-date" id="capPeriodFrom">c:</div>
                            <div class="message-wrapper-line-date-2 window-border" id="wrapPeriodFrom">
                        <?php } else { ?>
                            <div class="message-caption-line-date" id="capPeriodFrom" hidden="hidden">c:</div>
                            <div class="message-wrapper-line-date-2 window-border" id="wrapPeriodFrom" hidden="hidden">
                        <?php } ?>
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodFrom">
                        </div>
                        <?php if($PeriodType == 2) { ?>
                            <div class="message-caption-line-date" id="capPeriodTo">по:</div>
                            <div class="message-wrapper-line-date-2 window-border" id="wrapPeriodTo">
                        <?php } else { ?>
                            <div class="message-caption-line-date" id="capPeriodTo" hidden="hidden">по:</div>
                            <div class="message-wrapper-line-date-2 window-border" id="wrapPeriodTo" hidden="hidden">
                        <?php } ?>
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodTo">
                        </div>

                        <div class="window-button-in-panel window-border gap-v-13" id="btnRefresh">Обновить</div>
                    </div>
                </div>
            </div>

            <div class="clearfix m-t-10px"><hr class="line"></div>
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
                    <div class="column-7 border-1px-bl colNameNum">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= '№' ?></div>
                        </div>
                    </div>
                    <div class="column-39 border-1px-bl colNameSphere">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Сфера' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl colNameTotal">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Всего' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl colNameDone">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Выполнено' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl colNameDoneProcent">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Процент' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl colNameTotal">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Всего' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-bl colNameDone">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Зачтено' ?></div>
                        </div>
                    </div>
                    <div class="column-9 border-1px-rbl colNameDoneProcent">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Процент' ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="interactive-only">
                <div class="column-7 border-1px-bl colNameNum brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= '' ?></div>
                    </div>
                </div>
                <div class="column-39 border-1px-bl colNameSphere brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= 'Итоги' ?></div>
                    </div>
                </div>
                <?php           $allHave = 0;
                                $allDone = 0;
                                $allProc = 0;
                                $zacHave = 0;
                                $zacDone = 0;
                                $zacProc = 0;
                    foreach ($AllResults as $reg):
                        $allHave = $allHave + $reg['total'];
                        $allDone = $allDone + $reg['done'];
                        $zacHave = $zacHave + $reg['totalZ'];
                        $zacDone = $zacDone + $reg['doneZ'];
                    endforeach;
                    if($allHave > 0){
                        $allProc = $allDone * 100 / $allHave;
                    }
                    if($zacHave > 0){
                        $zacProc = $zacDone * 100 / $zacHave;
                    }
                    ?>
                <div class="column-9 border-1px-bl colNameTotal brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= $allHave ?></div>
                    </div>
                </div>
                <div class="column-9 border-1px-bl colNameDone brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= $allDone ?></div>
                    </div>
                </div>
                <div class="column-9 border-1px-bl colNameDoneProcent brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= number_format((float)$allProc, 2, '.', '') ?></div>
                    </div>
                </div>
                <div class="column-9 border-1px-bl colNameTotal brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= $zacHave ?></div>
                    </div>
                </div>
                <div class="column-9 border-1px-bl colNameDone brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= $zacDone ?></div>
                    </div>
                </div>
                <div class="column-9 border-1px-rbl colNameDoneProcent brown-back">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= number_format((float)$zacProc, 2, '.', '') ?></div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div id="list-result">
                <?php if (count($AllResults) == 0){ ?>

                <div id="info1" class="text-font-5 text-center margin-v20">
                    Нет данных
                </div>

                <?php } else {  $number = 0;
                    foreach ($AllResults as $reg): ?>

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
                    <div class="column-9 border-1px-bl col-back-nul colNameDoneProcent">
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