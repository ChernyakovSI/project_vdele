<?php

$this->title = 'Мечта';

?>

<div id="paramDate" hidden="hidden"><?= $date ?></div>
<div id="paramDateDone" hidden="hidden"><?= $dateDone ?></div>
<div id="paramDateGoal" hidden="hidden"><?= $dateGoal ?></div>

<div id="paramText" hidden="hidden"><?= $data->text ?></div>

<div id="paramNum" hidden="hidden"><?= $data->num ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>
<div id="paramIDSphere" hidden="hidden"><?= $data->id_sphere ?></div>
<div id="paramLevel" hidden="hidden"><?= ($data->id_level > 0) ? $data->id_level : $level ?></div>
<div id="paramStatus" hidden="hidden"><?= $data->status ?></div>

<div id="paramResultType" hidden="hidden"><?= $data->result_type ?></div>
<div id="paramResultMark" hidden="hidden"><?= $data->result_mark ?></div>
<div id="paramResultText" hidden="hidden"><?= $data->result_text ?></div>



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
    <div class="caption-text" id="form-caption">Мечта</div>

</div>

<div class="content">
    <div class="container-foto-wrap">

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="half_width">
                <div class="caption-line-half-20">Создан:</div>
                <div class="wrapper-line-half window-border col-message-wrapper-gray">
                    <input type="datetime-local" class="message-text-line" id="valueDate" disabled="disabled">
                </div>
            </div>
            <div class="half_width">
                <div class="caption-line-10">Сфера:</div>
                <div class="message-wrapper-line-half window-border" id="valueSphereWrap">
                    <input type="text" class="message-text-line" list="list_sphere" id="valueSphere" value="<?= isset($data) && $data->id_sphere>0 ? $spheres[$data->id_sphere-1]['name'] : '' ?>"/>
                    <datalist id="list_sphere">
                        <?php foreach ($spheres as $sphere): ?>
                            <option data-id=<?= $sphere['id'] ?>><?= $sphere['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearSphere">х</div>
            </div>

            <div class="clearfix"></div>
            <div class="half_width">
                <div class="caption-line-half-20">Тип:</div>
                <div class="message-wrapper-line-half window-border" id="valueLevelWrap">
                    <input type="text" class="message-text-line" list="list_level" id="valueLevel" value="<?= $levels[(isset($data) && $data->id_level>0?$data->id_level:2) - 1]['name'] ?>"/>
                    <datalist id="list_level">
                        <?php foreach ($levels as $clevel): ?>
                            <option data-id=<?= $clevel['id'] ?>><?= $clevel['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearLevel">х</div>
            </div>
            <div class="half_width">
                <div class="radio-container">
                    <div class="form-item radio-btn nth-3">
                        <input type="radio" name="valueType" id="isArchive">
                        <label for="isArchive">Не будет выполнено</label>
                    </div>
                    <div class="form-item radio-btn nth-3">
                        <input type="radio" name="valueType" id="isInProcess" checked>
                        <label for="isInProcess">В процессе</label>
                    </div>
                    <div class="form-item radio-btn nth-3">
                        <input type="radio" name="valueType" id="isDone">
                        <label for="isDone">Выполнено</label>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <?php if ($data->id_level === 4 || $level == 4){ ?>
            <div id="goalResult">
            <?php } else { ?>
            <div id="goalResult" hidden="hidden">
            <?php } ?>
                <div class="half_width">
                    <div id="wrap-DateGoal">
                        <div class="caption-line-half-20">Срок:</div>
                        <div class="message-wrapper-line-half window-border">
                            <input type="datetime-local" class="message-text-line" id="valueDateGoal">
                        </div>
                    </div>
                </div>
                <div class="half_width">
                    <div class="radio-container">
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueZachet" id="isUsual" checked>
                            <label for="isUsual">Без отметок</label>
                        </div>
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueZachet" id="isZachet">
                            <label for="isZachet">На зачет</label>
                        </div>
                        <div class="form-item radio-btn nth-3">
                            <input type="radio" name="valueZachet" id="isExam">
                            <label for="isExam">На оценку</label>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <?php if ($data->result_type > 0){ ?>
                <div id="goalMark">
                <?php } else { ?>
                <div id="goalMark" hidden="hidden">
                <?php } ?>
                    <div class="half_width">
                        &nbsp;
                    </div>
                    <div class="half_width">
                        <div class="float-right w-116px m-r-78px">
                            <?php if ($data->result_type == 1){ ?>
                            <div id="markZachet" class="m-t-10px">
                            <?php } else { ?>
                            <div id="markZachet" class="m-t-10px" hidden="hidden">
                            <?php } ?>
                                <input type="checkbox" id="setZachet" class="custom-checkbox">
                                <label for="setZachet" class="interactive-only">Зачтено</label>
                            </div>
                            <?php if ($data->result_type == 2){ ?>
                            <div id="markExam">
                            <?php } else { ?>
                            <div id="markExam" hidden="hidden">
                            <?php } ?>
                                <div class="caption-line-half-70px">Оценка:</div>
                                <div class="message-wrapper-line-46px window-border" id="valueExamWrap">
                                    <input type="number" min="0" max="5" class="message-text-line" id="valueMark" value="<?= $data->result_mark ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


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

