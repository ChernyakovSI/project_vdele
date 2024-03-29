<?php

    use common\models\fin\Account;
    use common\models\fin\Reports;

    //$this->registerJsFile('@web/js/fin/reports_pos_ready.js', ['position' => \yii\web\View::POS_READY]);

    $this->title = 'Финансы: Отчеты';
    //$this->params['breadcrumbs'][] = $this->title;

?>

<div id="paramPeriodFrom" hidden="hidden"><?= $periodFrom ?></div>
<div id="paramPeriodTo" hidden="hidden"><?= $periodTo ?></div>

<div class="submenu">
    <span class="btn-submenu"><a href="/fin/accounts">Счета</a></span>
    <span class="btn-submenu"><a href="/fin/categories">Категории</a></span>
    <span class="btn-submenu"><a href="/fin/register">Движения</a></span>
    <span class="btn-submenu btn-active">Отчеты</span>
</div>

<div class="window window-border window-caption-2em caption-wrap">
    <div class="caption-begin">
        <div id="floatingCirclesGMain" class="window-m-t--9" hidden>
            <div class="f_circleG" id="frotateG_01"></div>
            <div class="f_circleG" id="frotateG_02"></div>
            <div class="f_circleG" id="frotateG_03"></div>
            <div class="f_circleG" id="frotateG_04"></div>
            <div class="f_circleG" id="frotateG_05"></div>
            <div class="f_circleG" id="frotateG_06"></div>
            <div class="f_circleG" id="frotateG_07"></div>
            <div class="f_circleG" id="frotateG_08"></div>
        </div>
        <?='&nbsp;'?>
    </div>
    <div class="caption-text-new">Отчеты<div><?='&nbsp;'?></div></div>
    <div class="caption-close-new">
        <div><?='&nbsp;'?></div>
    </div>
</div>
<div class="clearfix"></div>


<div class="content">
    <div class="fin-container-wrap">
        <div class="submenu">
            <?php if ($typeReport === 0){ ?>
                <span class="btn-submenu btn-submenu-interactive" id="btn-expense">Расходы/Доходы</span>
            <?php } else { ?>
                <span class="btn-submenu btn-active-expense btn-submenu-interactive" id="btn-expense">Расходы/Доходы</span>
            <?php } ?>
        </div>
        <div class="window window-border gap-v" id="main-window">


            <div class="Rollup w-100">
                <input class="hide" id="hd-1" type="checkbox">
                <label for="hd-1">Настройки отчета: период с <span id="settingsPeriodFrom"><?= Reports::timestampToDateString($periodFrom) ?></span> по <span id="settingsPeriodTo"><?= Reports::timestampToDateString($periodTo) ?></span></label>
                <div>
                    <div class="column-33">
                        <div class="message-caption-line-date">c:</div>
                        <div class="message-wrapper-line-date-2 window-border">
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodFrom">
                        </div>
                        <div class="message-caption-line-date">по:</div>
                        <div class="message-wrapper-line-date-2 window-border">
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodTo">
                        </div>
                    </div>
                    <div class="column-33">
                        <div class="gap-v-13 m-t-20px">
                            <input type="checkbox" id="setVisibleSub" class="custom-checkbox" onclick="setVisibleSub()">
                            <label for="setVisibleSub" class="interactive-only">Показать подкатегории</label>
                        </div>
                    </div>
                    <div class="half_third">

                    </div>
                </div>

            </div>

            <div class="clearfix"></div>
            <div class="m-l-14px m-t-3px">
                Прибыль за период: <span id="delta" class="text-color-darkBlue text-bold"><?= Account::formatNumberToMoney($totalDelta) ?></span>
            </div>
            <div class="m-l-14px m-t-3px">
                За текущий день: <span id="deltaD" class="text-color-darkBlue text-bold"><?= Account::formatNumberToMoney($totalDeltaDay) ?></span>
                , за текущую неделю: <span id="deltaW" class="text-color-darkBlue text-bold"><?= Account::formatNumberToMoney($totalDeltaWeek) ?></span>
                , за текущий месяц: <span id="deltaM" class="text-color-darkBlue text-bold"><?= Account::formatNumberToMoney($totalDeltaMonth) ?></span>
                , за текущий год: <span id="deltaY" class="text-color-darkBlue text-bold"><?= Account::formatNumberToMoney($totalDeltaYear) ?></span>
            </div>

            <div class="clearfix"></div>
            <div class="halfwidth-wrapper">
                <div class="halfwidth">

                    <div  class="text-font-5 text-center">
                        Расходы
                    </div>
                    <div class="clearfix"><hr class="line"></div>
                    <div id="header-expenses">
                        <div class="interactive-only">
                            <div id="expenses-colname-cat" class="fin-reg-cat-60 table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line table-caption"><?= 'Категория' ?></div>
                                </div>
                            </div>
                            <div id="expenses-colname-sub" class="table-text">

                            </div>
                            <div id="expenses-colname-amo" class="fin-reg-amount-end table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="">
                            <div id="expenses-coltotal-cat" class="fin-reg-cat-60 table-text brown-back">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= '' ?></div>
                                </div>
                            </div>
                            <div id="expenses-coltotal-sub" class="table-text brown-back">

                            </div>
                            <div id="expenses-coltotal-amo" class="fin-reg-amount-end table-text brown-back">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line right-text" id="totalExp"><?= Account::formatNumberToMoney($totalExp) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="clearfix"><hr class="line"></div>
                    <div id="list-expenses">
                        <?php if (count($resultsExp) == 0){ ?>
                            <div id="infoExp" class="text-font-5 text-center margin-v20">
                                Нет данных
                            </div>
                        <?php } else {  foreach ($resultsExp as $res): ?>
                            <div class="fin-acc-row expense-back interactive-only">

                                <div class="fin-reg-cat-60 table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line"><?= $res['CatName'] ?></div>
                                    </div>
                                </div>
                                <div class="fin-reg-amount-end table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line right-text"><?= Account::formatNumberToMoney($res['sum']) ?></div>
                                    </div>
                                </div>

                                <div class="clearfix"><hr class="line"></div>
                            </div>
                        <?php endforeach; } ?>

                    </div>

                </div>
                <div class="halfwidth-gap">&nbsp;</div>
                <div class="halfwidth">

                    <div  class="text-font-5 text-center">
                        Доходы
                    </div>
                    <div class="clearfix"><hr class="line"></div>

                    <div class="interactive-only">
                        <div id="profits-colname-cat" class="fin-reg-cat-60 table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Категория' ?></div>
                            </div>
                        </div>
                        <div id="profits-colname-sub" class="table-text">

                        </div>
                        <div id="profits-colname-amo" class="fin-reg-amount-end table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="">
                        <div id="profits-coltotal-cat" class="fin-reg-cat-60 table-text brown-back">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= '' ?></div>
                            </div>
                        </div>
                        <div id="profits-coltotal-sub" class="table-text brown-back">

                        </div>
                        <div id="profits-coltotal-amo" class="fin-reg-amount-end table-text brown-back">
                            <div class="message-wrapper-title">
                                <div class="message-text-line right-text" id="totalProf"><?= Account::formatNumberToMoney($totalProf) ?></div>
                            </div>
                        </div>
                    </div>



                    <div class="clearfix"><hr class="line"></div>
                    <div id="list-profits">
                        <?php if (count($resultsProf) == 0){ ?>
                            <div id="infoProf" class="text-font-5 text-center margin-v20">
                                Нет данных
                            </div>
                        <?php } else {  foreach ($resultsProf as $res): ?>

                            <div class="fin-acc-row profit-back interactive-only">


                                <div class="fin-reg-cat-60 table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line"><?= $res['CatName'] ?></div>
                                    </div>
                                </div>
                                <!--<div class="fin-reg-sub table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line"><?= ''//$res['SubName'] ?></div>
                                    </div>
                                </div>-->
                                <div class="fin-reg-amount-end table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line right-text"><?= Account::formatNumberToMoney($res['sum']) ?></div>
                                    </div>
                                </div>

                                <div class="clearfix"><hr class="line"></div>
                            </div>
                        <?php endforeach; } ?>

                    </div>

                </div>

            </div>

            <div class="clearfix"></div>


        </div>


    </div>
</div>
