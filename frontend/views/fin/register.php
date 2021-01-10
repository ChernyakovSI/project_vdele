<?php
    use yii\helpers\Html;
    use common\models\fin\Account;

    $this->registerJsFile('@web/js/fin/register_pos_begin.js', ['position' => \yii\web\View::POS_BEGIN]);
    $this->registerJsFile('@web/js/fin/register_pos_ready.js', ['position' => \yii\web\View::POS_READY]);

    $this->title = 'Финансы: Движения';
    //$this->params['breadcrumbs'][] = $this->title;

?>

<div id="paramPeriodFrom" hidden="hidden"><?= $periodFrom ?></div>
<div id="paramPeriodTo" hidden="hidden"><?= $periodTo ?></div>

<div class="submenu">
    <span class="btn-submenu"><a href="/fin/accounts">Счета</a></span>
    <span class="btn-submenu"><a href="/fin/categories">Категории</a></span>
    <span class="btn-submenu btn-active">Движения</span>
    <span class="btn-submenu"><a href="#">Отчеты</a></span>
</div>

<div class="window window-border window-caption-2em caption-wrap">
    <div class="caption-begin"><?='&nbsp;'?></div>
    <div class="caption-text-new">Движения<div><?='&nbsp;'?></div></div>
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
    <div class="fin-container-wrap">
        <div class="submenu">
            <?php if ($isExpense == 0){ ?>
                <span class="btn-submenu btn-submenu-interactive" id="btn-expense">Расходы</span>
            <?php } else { ?>
                <span class="btn-submenu btn-active-expense btn-submenu-interactive" id="btn-expense">Расходы</span>
            <?php } ?>
            <?php if ($isProfit == 0){ ?>
                <span class="btn-submenu btn-submenu-interactive" id="btn-profit">Доходы</span>
            <?php } else { ?>
                <span class="btn-submenu btn-active-profit btn-submenu-interactive" id="btn-profit">Доходы</span>
            <?php } ?>
            <?php if ($isReplacement == 0){ ?>
                <span class="btn-submenu btn-submenu-interactive" id="btn-replacement">Перемещения</span>
            <?php } else { ?>
                <span class="btn-submenu btn-active-replacement btn-submenu-interactive" id="btn-replacement">Перемещения</span>
            <?php } ?>
        </div>
        <div class="window window-border gap-v" id="main-window">


            <div class="Rollup">
                <input class="hide" id="hd-1" type="checkbox">
                <label for="hd-1">Настройки списка</label>
                <div>
                    <div class="half_third">
                        <div class="caption-line-half-20">c:</div><div class="message-wrapper-line-half window-border">
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodFrom">
                        </div>
                        <div class="caption-line-half-20">по:</div><div class="message-wrapper-line-half window-border">
                            <input type="date" class="message-text-line" contentEditable id="valuePeriodTo">
                        </div>
                    </div>
                    <div class="half_third">
                        <div class="message-wrapper-line-half-70 window-border">
                            <input type="text" class="message-text-line" list="list_accounts_sel" id="selValueAcc" contentEditable placeholder="Счет"/>
                            <datalist id="list_accounts_sel">
                                <?php foreach ($accs as $account): ?>
                                    <option data-id=<?= $account['id'] ?>><?= $account['name'] ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="window-button-in-panel window-border gap-v-13" id="selClearAcc">х</div>
                    </div>
                    <div class="half_third" id="wrapSelCats">
                        <div class="message-wrapper-line-half-70 window-border">
                            <input type="text" class="message-text-line" list="list_cats_sel" id="selValueCat" contentEditable placeholder="Категория"/>
                            <datalist id="list_cats_sel">
                                <?php foreach ($cats as $cat): ?>
                                    <option data-id=<?= $cat['id'] ?>><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="window-button-in-panel window-border gap-v-13" id="selClearCat">х</div>
                        <div class="message-wrapper-line-half-70 window-border">
                            <input type="text" class="message-text-line" list="list_subs_sel" id="selValueSub" contentEditable placeholder="Подкатегория"/>
                            <datalist id="list_subs_sel">
                                <?php foreach ($subs as $sub): ?>
                                    <option data-id=<?= $sub['id'] ?>><?= $sub['name'] ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="window-button-in-panel window-border gap-v-13" id="selClearSub">х</div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="window-button-panel">
                <div class="window-button-in-panel window-border" id="new-reg" onclick="addReg()">Добавить</div>
<!--                <div class="window-button-in-panel window-border" id="btn-copy">Скопировать</div>-->
            </div>

            <div class="clearfix gap-v-60"><hr class="line"></div>
            <div class="interactive-only">
                <div class="fin-reg-date table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= 'Дата' ?></div>
                    </div>
                </div>
                <div class="fin-reg-acc table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= 'Счет' ?></div>
                    </div>
                </div>
                <div class="fin-reg-cat table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= 'Категория' ?></div>
                    </div>
                </div>
                <div class="fin-reg-sub table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= 'Подкатегория' ?></div>
                    </div>
                </div>
                <div class="fin-reg-amount table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
                    </div>
                </div>
                <div class="fin-reg-com table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line table-caption"><?= 'Примечание' ?></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="fin-reg-date table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-acc table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-cat table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-sub table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>
            <div class="fin-reg-amount table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line right-text" id="total"><?= Account::formatNumberToMoney($total) ?></div>
                </div>
            </div>
            <div class="fin-reg-com table-text brown-back">
                <div class="message-wrapper-title">
                    <div class="message-text-line"><?= '' ?></div>
                </div>
            </div>

            <div class="clearfix"><hr class="line"></div>
            <div id="list-register">
                <?php if (count($transactions) == 0){ ?>
                    <div id="info" class="text-font text-center margin-v20">
                        Нет движений
                    </div>
                <?php } else {  foreach ($transactions as $reg): ?>
                    <?php if ($reg['id_type'] == 0){ ?>
                    <div class="fin-acc-row expense-back interactive-only" ondblclick="editReg(<?= $reg['id'] ?>)" id="<?= $reg['id'] ?>">
                    <?php }?>

                     <?php if ($reg['id_type'] == 1){ ?>
                     <div class="fin-acc-row profit-back interactive-only" ondblclick="editReg(<?= $reg['id'] ?>)" id="<?= $reg['id'] ?>">
                     <?php }?>

                     <?php if ($reg['id_type'] == 2){ ?>
                     <div class="fin-acc-row movement-back interactive-only" ondblclick="editReg(<?= $reg['id'] ?>)" id="<?= $reg['id'] ?>">
                     <?php }?>

                        <div class="fin-reg-date table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= date("d.m.Y", $reg['date']) ?></div>
                            </div>
                        </div>
                        <div class="fin-reg-acc table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $reg['AccName'] ?></div>
                            </div>
                        </div>
                        <?php if ($reg['id_type'] == 2){ ?>
                            <div class="fin-reg-cat table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= $reg['AccToName'] ?></div>
                                </div>
                            </div>
                            <div class="fin-reg-sub table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= '' ?></div>
                                </div>
                            </div>
                        <?php } else {?>
                            <div class="fin-reg-cat table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= $reg['CatName'] ?></div>
                                </div>
                            </div>
                            <div class="fin-reg-sub table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= $reg['SubName'] ?></div>
                                </div>
                            </div>
                        <?php }?>
                        <div class="fin-reg-amount table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line right-text"><?= Account::formatNumberToMoney($reg['sum']) ?></div>
                            </div>
                        </div>
                        <div class="fin-reg-com table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $reg['comment'] ?></div>
                            </div>
                        </div>
                        <div class="clearfix"><hr class="line"></div>
                    </div>
                <?php endforeach; } ?>
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
                <div class="caption-text" id="form-caption">Новое движение</div>
                <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
            </div>
            <div class="clearfix"></div>

            <div class="half_width">
                <div class="caption-line-half-20">Дата:</div><div class="message-wrapper-line-half window-border">
                    <input type="datetime-local" class="message-text-line" contentEditable id="valueDate">
                </div>
            </div>
            <div class="half_width">

                    <div class="radio-container">
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isExpense" checked>
                            <label for="isExpense">Расход</label>
                        </div>
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isProfit">
                            <label for="isProfit">Доход</label>
                        </div>
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueType" id="isReplacement">
                            <label for="isReplacement">Перемещение</label>
                        </div>
                    </div>

                    <!--<select size="1" class="message-text-line" contentEditable id="valueType">
                        <option selected value="0">Расход</option>
                        <option  value="1">Доход</option>
                        <option value="2">Перемещение</option>
                    </select>-->

            </div>
            <div class="clearfix"></div>
            <div>
                <div class="caption-line-10" id="fieldAcc">Счет:</div><div class="message-wrapper-line-half window-border" id="valueAccWrap">
                    <input type="text" class="message-text-line" list="list_accounts" id="valueAcc" contentEditable />
                    <datalist id="list_accounts">
                        <?php foreach ($accs as $account): ?>
                            <option data-id=<?= $account['id'] ?>><?= $account['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearAcc">х</div>
                <div class="caption-line-left" id="textAcc"></div>
            </div>
            <div class="clearfix"></div>
            <div hidden="hidden" id="fieldAccTo">
                <div class="caption-line-10">На счет:</div><div class="message-wrapper-line-half window-border" id="valueAccToWrap">
                    <input type="text" class="message-text-line" list="list_accountsTo" id="valueAccTo" contentEditable />
                    <datalist id="list_accountsTo">
                        <?php foreach ($accs as $account): ?>
                            <option data-id=<?= $account['id'] ?>><?= $account['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearAccTo">х</div>
                <div class="caption-line-left" id="textAccTo"></div>
            </div>
            <div class="clearfix"></div>
            <div id="fieldCat">
                <div class="caption-line-10">Категория:</div><div class="message-wrapper-line-half window-border" id="valueCatWrap">
                    <input type="text" class="message-text-line" list="list_categories" id="valueCat" contentEditable />
                    <datalist id="list_categories">
                        <?php foreach ($cats as $category): ?>
                            <option data-id=<?= $category['id'] ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearCat">х</div>
                <div class="caption-line-left" id="textCat"></div>
            </div>
            <div class="clearfix"></div>
            <div id="fieldSub">
                <div class="caption-line-10">Подкатегория:</div><div class="message-wrapper-line-half window-border" id="valueSubWrap">
                    <input type="text" class="message-text-line" list="list_subcategories" id="valueSub" contentEditable />
                    <datalist id="list_subcategories">
                        <?php foreach ($subs as $category): ?>
                            <option data-id=<?= $category['id'] ?>><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearSub">х</div>
                <div class="caption-line-left" id="textSub"></div>
            </div>
            <div class="clearfix"></div>
            <div class="half_width">
                <div class="caption-line-half-20">Сумма:</div><div class="message-wrapper-line-half window-border" id="valueAmoWrap">
                    <!--<div class="message-text-line" contentEditable id="valueAmo" >0</div>-->
                    <input type="number" class="message-text-line" id="valueAmo" step="0.01" contentEditable />
                </div>
            </div>
            <div class="clearfix"></div>
            <div>
                <div class="caption-line-10">Примечание:</div><div class="message-wrapper-line window-border">
                    <div class="message-text-line" contentEditable id="valueCom" ></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="red-comment" id="red-comment"></div>
            <div class="window-button-panel">
                <div class="window-button-in-panel window-border" id="button-add">Подтвердить</div>
                <div class="window-button-in-panel window-border" id="button-del">Удалить</div>
            </div>
        </div>

    </div>
</div>


        <nav class="context-menu" id="context-menu">
            <div class="context-menu__items">
                <div class="context-menu__item">
                    <div class="context-menu__link" data-action="Copy">
                        Скопировать
                    </div>
                </div>
            </div>
        </nav>