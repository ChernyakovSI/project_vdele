<?php

$this->title = 'Заметка';

?>

<div id="paramDate" hidden="hidden"><?= $date ?></div>

<div id="paramText" hidden="hidden"><?= $data->text ?></div>

<div id="paramNum" hidden="hidden"><?= $data->num ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>
<div id="paramIDSphere" hidden="hidden"><?= $data->id_sphere ?></div>
<!-- ++ 1-2-3-006 28/07/2022 -->
<div id="paramPublic" hidden="hidden"><?= $data->isPublic ?></div>
<div id="paramDomain" hidden="hidden"><?= Yii::$app->params['doman'] ?></div>
<!-- -- 1-2-3-006 28/07/2022 -->

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
    <div class="caption-text" id="form-caption">Заметка</div>

</div>

<div class="content">
    <div class="container-foto-wrap">

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="half_width">
                <div class="caption-line-half-20">Дата:</div><div class="message-wrapper-line-half window-border">
                    <input type="datetime-local" class="message-text-line" contentEditable id="valueDate">
                </div>
            </div>
            <div class="half_width">
                <div class="caption-line-10">Сфера:</div><div class="message-wrapper-line-half window-border" id="valueSphereWrap">
                    <input type="text" class="message-text-line" list="list_sphere" id="valueSphere" value="<?= isset($sphere)?$sphere->name:'' ?>"/>
                    <datalist id="list_sphere">
                        <?php foreach ($spheres as $sphere): ?>
                            <option data-id=<?= $sphere['id'] ?>><?= $sphere['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearSphere">х</div>
            </div>

            <!-- ++ 1-2-3-006 28/07/2022 -->
            <div class="clearfix"></div>
            <div class="half_width">
                <div class="w-100 m-t-20px">
                    <div class="caption-line-half-20"></div>
                    <div class="">
                        <input type="checkbox" id="setPublic" class="custom-checkbox">
                        <label for="setPublic" id="setPublicLink" class="interactive-only">Опубликовать</label>
                    </div>
                    <div class="caption-line-half-20 visible-not" id="PublicURLgap"></div>
                    <div class="" id="PublicURL"></div>
                </div>
            </div>
            <!-- -- 1-2-3-006 28/07/2022 -->

            <div class="clearfix"></div>
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

