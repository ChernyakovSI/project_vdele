<?php
use yii\helpers\Html;

$this->title = 'Финансы: Движения';
//$this->params['breadcrumbs'][] = $this->title;

?>

<div class="submenu">
    <span class="btn-submenu"><a href="/fin/accounts">Счета</a></span>
    <span class="btn-submenu"><a href="/fin/categories">Категории</a></span>
    <span class="btn-submenu btn-active">Движения</span>
    <span class="btn-submenu"><a href="#">Отчеты</a></span>
</div>

<?php
$script = new \yii\web\JsExpression("
    let isExpense = " .$isExpense.";
    let isProfit = " .$isProfit.";
    let isReplacement = " .$isReplacement.";
    
    function expense(){
        let divExpense = document.getElementById('btn-expense');
    
        if(isExpense == 0){
            isExpense = 1;
            divExpense.className = 'btn-submenu btn-active-expense btn-submenu-interactive';
        }
        else
        {
            isExpense = 0;
            divExpense.className = 'btn-submenu btn-submenu-interactive';
        }
    };
    
    function profit(){
        let divProfit = document.getElementById('btn-profit');
    
        if(isProfit == 0){
            isProfit = 1;
            divProfit.className = 'btn-submenu btn-active-profit btn-submenu-interactive';
        }
        else
        {
            isProfit = 0;
            divProfit.className = 'btn-submenu btn-submenu-interactive';
        }
    };
    
    function trans(){
        let divReplacement = document.getElementById('btn-replacement');
    
        if(isReplacement == 0){
            isReplacement = 1;
            divReplacement.className = 'btn-submenu btn-active-replacement btn-submenu-interactive';
        }
        else
        {
            isReplacement = 0;
            divReplacement.className = 'btn-submenu btn-submenu-interactive';
        }
    };
    
    ");
$this->registerJs($script, \yii\web\View::POS_BEGIN);
?>

<div class="window window-border window-caption" id="caption">Движения</div>

<div class="submenu">
    <?php if ($isExpense == 0){ ?>
        <span class="btn-submenu btn-submenu-interactive" id="btn-expense" onclick="expense()">Расходы</span>
    <?php } else { ?>
        <span class="btn-submenu btn-active-expense btn-submenu-interactive" id="btn-expense" onclick="expense()">Расходы</span>
    <?php } ?>
    <?php if ($isProfit == 0){ ?>
        <span class="btn-submenu btn-submenu-interactive" id="btn-profit" onclick="profit()">Доходы</span>
    <?php } else { ?>
        <span class="btn-submenu btn-active-profit btn-submenu-interactive" id="btn-profit" onclick="profit()">Доходы</span>
    <?php } ?>
    <?php if ($isReplacement == 0){ ?>
        <span class="btn-submenu btn-submenu-interactive" id="btn-replacement" onclick="trans()">Перемещения</span>
    <?php } else { ?>
        <span class="btn-submenu btn-active-replacement btn-submenu-interactive" id="btn-replacement" onclick="trans()">Перемещения</span>
    <?php } ?>
</div>

<div class="content">
    <div class="url-container-wrap">
        <div class="url-window-right window-border url-sidebar-left">
            <div class="url-categoryList-header window-subcaption">
                Настройки
            </div>
            <div class="clearfix"><hr class="line"></div>
            <div id="list-categories">



            </div>
        </div>
        <div class="window window-border fin-main-submenu" id="main-window">
            <div class="window-button window-border" id="new-sub" onclick="addSub()">Добавить</div>
            <div class="clearfix"></div>
            <div id="list-subcategories">
                <?php if (count($transactions) == 0){ ?>
                    <div id="info" class="text-font text-center margin-v20">
                        Нет движений
                    </div>
                <?php } else { ?>

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
                <div class="caption-line">Название:</div><div class="message-wrapper-line window-border" id="valueNameWrap">
                    <div class="message-text-line" contentEditable id="valueName" placeholder="Образование" ></div>
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

