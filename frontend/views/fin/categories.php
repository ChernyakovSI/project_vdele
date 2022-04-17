<?php
use yii\helpers\Html;

$this->title = 'Финансы: Категории расходов и доходов';
//$this->params['breadcrumbs'][] = $this->title;

?>

<!-- //++ 1-2-2-009 15/04/2022 -->
<div id="paramIdCategory" hidden="hidden"><?= $id_category ?></div>
<div id="paramIsProfit" hidden="hidden"><?= $isProfit ?></div>
<!-- //-- 1-2-2-009 15/04/2022 -->

<div class="submenu">
    <span class="btn-submenu"><a href="/fin/accounts">Счета</a></span>
    <span class="btn-submenu btn-active">Категории</span>
    <span class="btn-submenu"><a href="/fin/register">Движения</a></span>
    <span class="btn-submenu"><a href="/fin/reports">Отчеты</a></span>
</div>

<!-- //++ 1-2-2-009 15/04/2022 -->
<!--
    $script = new \yii\web\JsExpression("
    
    ");
    $this->registerJs($script, \yii\web\View::POS_BEGIN); -->

<!-- //-- 1-2-2-009 15/04/2022 -->

<div class="window window-border window-caption" id="caption">Категории<?= ($isProfit == 0 ? ' расходов' : ' доходов') ?></div>

<div class="submenu">
    <?php if ($isProfit == 0){ ?>
        <span class="btn-submenu btn-active btn-submenu-interactive" id="btn-expense" onclick="expense()">Расходы</span>
        <span class="btn-submenu btn-submenu-interactive" id="btn-profit" onclick="profit()">Доходы</span>
    <?php } else { ?>
        <span class="btn-submenu btn-submenu-interactive" id="btn-expense" onclick="expense()">Расходы</span>
        <span class="btn-submenu btn-active btn-submenu-interactive" id="btn-profit" onclick="profit()">Доходы</span>
    <?php } ?>
</div>

<div class="content">
    <div class="url-container-wrap">
        <div class="window url-window-right window-border url-sidebar-left">
            <div class="url-categoryList-header window-subcaption">
                Категории
            </div>

            <div class="window-button window-border" id="new-category" onclick="addCategory()">Добавить</div>

            <div class="clearfix window-m-t-9"><hr class="line"></div>
            <div id="list-categories">

                <?php foreach ($categories as $category): ?>
                    <div class="fin-acc-row white-back interactive-only" onclick="fullSubCategories(<?= $category['id'] ?>)" id="<?= $category['id'] ?>">
                        <div class="url-col-category table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $category['name'] ?></div>
                            </div>
                        </div>
                        <div class="clearfix"><hr class="line"></div>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
        <div class="window window-border fin-main-submenu" id="main-window">

            <div class="window-button window-border" hidden="hidden" id="new-sub" onclick="addSub()">Добавить</div>
            <div class="clearfix"></div>

            <div id="list-subcategories" class="window-m-t-9">
                <?php if (count($categories) == 0){ ?>
                    <div id="info" class="text-font text-center margin-v20">
                        У вас пока нет ни одной категории.
                        <br>
                        Добавляйте слева категории и для каждой категории добавляйте подкатегории здесь.
                    </div>
                <?php } else { ?>
                <div id="info" class="text-font text-center margin-v20">
                    Выберите категорию слева для отображения всех ее подкатегорий
                </div>
                <?php } ?>
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
                <div class="caption-text" id="form-caption">Новая категория</div>
                <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
            </div>
            <div class="clearfix"></div>

            <div>
                <div class="caption-line">Название:</div>
                <div class="message-wrapper-line window-border" id="valueNameWrap">
                    <div class="message-text-line" contentEditable id="valueName" placeholder="Образование" ></div>
                </div>
            </div>
            <div class="clearfix"></div>

            <!-- //++ 1-2-2-009 15/04/2022 -->
            <div>
                <div class="caption-line">Цвет:</div>
                <div class="float-left m-t-15px" id="valuColorWrap">
                    <input type="color" id="valueColor" name="color"
                           value="#e66465">
                </div>

                <div class="float-left m-t-17px m-l-10px" id="valuColorOnWrap">
                    <input type="checkbox" id="setColorOn" class="custom-checkbox">
                    <label for="setColorOn" class="interactive-only">Использовать</label>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- //-- 1-2-2-009 15/04/2022 -->

            <div class="red-comment window-m-t-9" id="red-comment"></div>
            <div class="window-button-panel window-m-t-9">
                <div class="window-button-in-panel window-border" id="button-add">Подтвердить</div>
                <div class="window-button-in-panel window-border" id="button-del">Удалить</div>
            </div>
        </div>

    </div>
</div>

