<?php

/* @var $this yii\web\View class="site-index"*/
use frontend\assets\AppAsset;
use common\models\fin\Account;

AppAsset::register($this);

$this->title = 'Финансы: Счета';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="submenu">
    <span class="btn-submenu btn-active">Счета</span>
    <span class="btn-submenu"><a href="/fin/categories">Категории</a></span>
    <span class="btn-submenu"><a href="/fin/register">Движения</a></span>
    <span class="btn-submenu"><a href="/fin/reports">Отчеты</a></span>
</div>

<?php

$this->registerJsFile('@web/js/fin/index_pos_begin.js', ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile('@web/js/fin/index_pos_ready.js', ['position' => \yii\web\View::POS_READY]);


?>
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
    <div class="caption-text-new">Счета<div><?='&nbsp;'?></div></div>
    <div class="caption-close-new">
        <div><?='&nbsp;'?></div>
    </div>

</div>

<div class="window window-border" id="content">
    <div id="paramMaxNum" hidden="hidden"><?= $maxNum ?></div>

<div id="general">

    <div class="clearfix"></div>
    <div class="window-button window-border" id="button-new" onclick="addAcc()">Добавить</div>

    <div class="clearfix window-m-t-9"><hr class="line"></div>

    <div class="fin-acc-name table-text">
        <div class="message-wrapper-title">
            <div class="message-text-line table-caption"><?= 'Счет' ?></div>
        </div>
    </div>
    <div class="fin-acc-amount table-text">
        <div class="message-wrapper-title">
            <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
        </div>
    </div>
    <div class="fin-acc-comment table-text">
        <div class="message-wrapper-title">
            <div class="message-text-line table-caption"><?= 'Комментарий' ?></div>
        </div>
    </div>
    <div class="fin-acc-panel table-caption">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= '' ?></div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="fin-acc-name table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= 'Всего:' ?></div>
        </div>
    </div>
    <div class="fin-acc-amount table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line right-text" id="total"><?= Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user)) ?></div>
        </div>
    </div>
    <div class="fin-acc-comment table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= '' ?></div>
        </div>
    </div>
    <div class="fin-acc-panel table-text brown-back">
        <div class="message-wrapper-title">
            <div class="message-text-line"><?= '' ?></div>
        </div>
    </div>

    <div class="clearfix"><hr class="line"></div>

    <div id="listAccounts">
        <?php if (count($accounts) == 0) { ?>
        <div id="info" class="text-font text-center margin-v20">
            У вас пока нет ни одного счета.
            <br>
            Добавьте несколько, например, "Кошелек", "Карта Тинькофф" и "Резервный Фонд"
        </div>
        <?php } else { ?>
        <?php foreach ($accounts as $account): ?>
            <div class="fin-acc-row white-back interactive-only" ondblclick="editAcc(<?= $account['id'] ?>)" id="<?= $account['id'] ?>">
                <div class="fin-acc-name table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line"><?= $account['num'] ?>. <?= $account['name'] ?></div>
                    </div>
                </div>
                <div class="fin-acc-amount table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line right-text"><?= Account::formatNumberToMoney($account['sum']) ?></div>
                    </div>
                </div>
                <div class="fin-acc-comment table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line"><?= $account['comment'] ?></div>
                    </div>
                </div>
                <div class="fin-acc-panel">
                    <div class="message-wrapper-title">
                        <div class="message-text-line unactive">
                            <span class="glyphicon glyphicon-pencil symbol_style interactive text-center" onclick="editAcc(<?= $account['id'] ?>)"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"><hr class="line"></div>
            </div>

        <?php endforeach; } ?>
    </div>


    <div class="height-5em">
        <input type="checkbox" id="setVisibleDeleted" class="custom-checkbox" onclick="setVisibleDeleted()">
        <label for="setVisibleDeleted" class="interactive-only">Показать скрытые</label>

        <div class="window-button window-button-left window-border" id="button-calc" onclick="recalculateAllAcc()">Пересчитать суммы</div>
    </div>

</div>



</div>


<div id="prompt-form-container">
    <div id="prompt-form" class="window window-border">
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
            <div class="caption-text" id="form-caption">Новый счет</div>
            <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
        </div>
        <div class="clearfix"></div>

        <div>
            <div class="caption-line">Счет:</div><div class="message-wrapper-line window-border" id="valueAccWrap">
                <div class="message-text-line" contentEditable id="valueAcc" >Новый счет</div>
            </div>
        </div>
        <div class="half_width">
            <div class="caption-line-half">Начальный остаток:</div><div class="message-wrapper-line-half window-border">
                <div class="message-text-line" contentEditable id="valueAmo" >0</div>
            </div>
        </div>
        <div class="half_width">
            <div class="caption-line-half">Порядок:</div><div class="message-wrapper-line-half window-border">
                <input type="number" class="message-text-line" contentEditable id="valueNum" value="1" min="1">
            </div>
        </div>
        <div>
            <div class="caption-line">Комментарий:</div><div class="message-wrapper-line window-border">
                <div class="message-text-line" contentEditable id="valueCom" ></div>
            </div>
        </div>
        <div>
            <div class="caption-line">
                <input type="checkbox" id="valueDel" class="custom-checkbox">
                <label for="valueDel" class="interactive-only">Скрыть счет</label>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="red-comment" id="red-comment"></div>
        <div class="window-button window-border" id="button-add">Подтвердить</div>
    </div>

</div>
