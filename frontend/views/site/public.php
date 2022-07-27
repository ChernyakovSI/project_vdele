<?php

$this->title = 'Публикация';

?>

<div id="paramText" hidden="hidden"><?= $data->text ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>

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
    <div class="caption-text" id="form-caption">Публикация</div>

</div>

<div class="content">
    <div class="">

        <div class="window window-border main-info-foto">

            <div class="clearfix"></div>
            <div class="new-message-wrapper width-full multistring-min-25 window-border m-t-10px back-cells" id="valueTextWrap">
                <div contentEditable="false" class="message-text-multistring resize_vertical_only multistring-min-25 ahref" id="valueText"><?= $data->text ?></div>
            </div>
        </div>

    </div>
</div>

