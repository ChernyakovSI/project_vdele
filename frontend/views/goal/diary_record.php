<?php

$this->title = 'Запись дневника';

?>

<div id="paramText" hidden="hidden"><?= $data->text ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>
<div id="paramDate" hidden="hidden"><?= $data->date ?></div>
<div id="paramIdDiary" hidden="hidden"><?= $data->id_diary ?></div>
<div id="paramIsGuest" hidden="hidden"><?= $isGuest ?></div>

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
    <div class="caption-text" id="form-caption">Запись дневника</div>

</div>

<div class="content">
    <div class="container-foto-wrap">

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <?php if($isGuest == 0) { ?>
                <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
            <?php } else { ?>
                <div class="window-button-in-panel window-border visible-not" id="button-save">Сохранить</div>
            <?php } ?>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
            <?php if($isGuest == 0) { ?>
                <div class="window-button-in-panel window-border" id="button-delete">Удалить</div>
            <?php } else { ?>
                <div class="window-button-in-panel window-border visible-not" id="button-delete">Удалить</div>
            <?php } ?>
        </div>

        <div class="window window-border main-info-foto">
            <div class="w-25 float-left w-m-255px">
                <div class="caption-line-half-20">Дата:</div>
                <div class="message-wrapper-line-half window-border">
                    <?php if($isGuest == 0) { ?>
                        <input type="datetime-local" class="message-text-line" id="valueDate">
                    <?php } else { ?>
                        <input type="datetime-local" class="message-text-line" disabled id="valueDate">
                    <?php } ?>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="w-100" id="DataContent">

            </div>

            <div class="clearfix"></div>
            <div class="new-message-wrapper width-full h-m-20em window-border m-t-10px back-cells" id="valueTextWrap">
                <?php if($isGuest == 0) { ?>
                    <div contentEditable="true" class="message-text-multistring resize_vertical_only h-m-20em ahref" id="valueText"><?= $data->text ?></div>
                <?php } else { ?>
                    <div contentEditable="false" class="message-text-multistring resize_vertical_only h-m-20em ahref" id="valueText"><?= $data->text ?></div>
                <?php } ?>
            </div>



        </div>



    </div>
</div>

