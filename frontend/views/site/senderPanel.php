<?php

$this->title = 'Панель рассылки';

?>

<div class="window window-border window-caption window-h-35">
    <div class="caption-begin window-m-t--9">
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
    <div class="caption-text" id="form-caption">Панель рассылок</div>

</div>

<div class="content">
    <div class="container-wrap-rw-62px">

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-68px">
            <div class="caption-begin window-m-t--9">
                <div class="float-left"><?='&nbsp;'?></div>
            </div>
            <div class="caption-text">
                Новое письмо
            </div>
            <div class="clearfix"></div>
            <div class="window-button-in-panel window-border float-left" id="button-save">Отправить</div>

        </div>

        <div class="window window-border main-info-foto">
            <div class="caption-line-10">Заголовок:</div><div class="message-wrapper-line window-border" id="valueTitleWrap">
                <input type="text" class="message-text-line" id="valueTitle" value="<?= $data->title ?>">
            </div>

            <div class="clearfix"></div>
            <div class="new-message-wrapper width-full multistring-min-25 window-border m-t-10px back-cells" id="valueTextWrap">
                <div contentEditable="true" class="message-text-multistring resize_vertical_only multistring-min-25 ahref" id="valueText"><?= $data->text ?></div>
            </div>
        </div>



    </div>
</div>