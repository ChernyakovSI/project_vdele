<?php

$this->title = 'День';

?>

<div id="paramDate" hidden="hidden"><?= $date ?></div>
<div id="paramIDSphere" hidden="hidden"><?= $dayData->id_sphere ?></div>

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
    <div class="caption-text" id="form-caption">
        День
    </div>

</div>

<div class="content">
    <div class="container-foto-wrap">

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
            <div class="half_width">
                <div class="caption-line-half-20 m-t-0">Дата:</div>
                <div class="message-wrapper-line-half window-border m-t--3px">
                    <input type="date" class="message-text-line" contentEditable id="valueDate">
                </div>
            </div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="">
                <div class="caption-line-10">Специализация:</div>
                <div class="message-wrapper-line-half window-border" id="valueSphereWrap">
                    <input type="text" class="message-text-line" list="list_sphere" id="valueSphere" value=""/>
                    <datalist id="list_sphere">
                        <?php foreach ($spheres as $sphere): ?>
                            <option data-id=<?= $sphere['id'] ?>><?= $sphere['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearSphere">х</div>
            </div>

            <div id="content-notes" hidden="hidden">
                <div class="clearfix gap-v-60"></div>

                <div class="interactive-only">
                    <div class="border-1px-right">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Заметки' ?></div>
                        </div>
                    </div>
                </div>
                <div id="list-notes">
                    <div class="fin-acc-row interactive-only">
                        <div class="border-1px-right">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= 'Название заметки 1' ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="fin-acc-row interactive-only">
                        <div class="border-1px-all">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= 'Название заметки 1' ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>