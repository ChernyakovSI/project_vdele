<?php

$this->title = 'Задача';

?>

<div id="paramDate" hidden="hidden"><?= $date ?></div>
<div id="paramDateDone" hidden="hidden"><?= $dateDone ?></div>

<div id="paramText" hidden="hidden"><?= $data->text ?></div>

<div id="paramNum" hidden="hidden"><?= $data->num ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>
<div id="paramIDSphere" hidden="hidden"><?= $data->id_sphere ?></div>
<div id="paramStatus" hidden="hidden"><?= $data->status ?></div>

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
    <div class="caption-text" id="form-caption">Задача</div>

</div>

<div class="content">
    <div class="container-foto-wrap">

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="w-275px">

                <div class="message-caption-line m-l-35px w-55px">Дата:</div>
                <div class="message-wrapper-line-datetime-px window-border">
                    <input type="datetime-local" class="message-text-line" id="valueDate">
                </div>
            </div>
            <div class="w-30 float-left m-t-20px">
                <div class="caption-line-half-20"></div>
                <input type="checkbox" id="isActive" class="custom-checkbox w-1-5em">
                <label for="isActive" class="interactive-only">Актив</label>
            </div>
            <div class="w-30 float-left">
                <div class="w-3 float-left">&nbsp</div>
                <div class="message-wrap-line w-70 window-border" id="valueLevelWrap">
                    <input type="text" class="message-text-line" placeholder="Тип" list="list_level" id="valueLevel" value="<?= $types[$data->type]['name'] ?>"/>
                    <datalist id="list_level">
                        <?php foreach ($types as $type): ?>
                            <option data-id=<?= $type['id'] ?>><?= $type['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearLevel">&#10008;</div>
            </div>

            <div class="clearfix"></div>

            <div class="half_width">
                <div class="w-87px float-left">&nbsp</div>
                <div class="message-wrap-line w-70 window-border">
                    <input type="text" class="message-text-line" placeholder="Цель" list="list_goals" id="valueGoal" value="<?= isset($data) && $data->id_goal>0 ? $goals[$data->id_goal]['name'] : '' ?>">
                    <datalist id="list_goals">
                        <?php foreach ($goals as $goal): ?>
                            <option data-id=<?= $goal['id'] ?>><?= $goal['name'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearGoal">&#10008;</div>
            </div>
            <div class="half_width">
                <div class="w-50 float-left">

                    <div class="message-wrap-line w-80 window-border" id="valueSphereWrap">
                        <input type="text" class="message-text-line" placeholder="Главная задача" list="list_tasks" id="valueTask" value="<?= isset($data) && $data->id_task>0 ? $tasks[$data->id_task-1]['name'] : '' ?>"/>
                        <datalist id="list_sphere">
                            <?php foreach ($tasks as $task): ?>
                                <option data-id=<?= $task['id'] ?>><?= $task['name'] ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="window-button-in-panel window-border gap-v-13" id="ClearTask">&#10008;</div>
                </div>

                <div class="w-50 float-left">
                    <div class="w-6 float-left">&nbsp</div>
                    <div class="message-wrap-line w-46 window-border" id="valueSphereWrap">
                        <input type="text" class="message-text-line" placeholder="Сфера" list="list_sphere" id="valueSphere" value="<?= isset($data) && $data->id_sphere>0 ? $spheres[$data->id_sphere-1]['name'] : '' ?>"/>
                        <datalist id="list_sphere">
                            <?php foreach ($spheres as $sphere): ?>
                                <option data-id=<?= $sphere['id'] ?>><?= $sphere['name'] ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="window-button-in-panel window-border gap-v-13" id="ClearSphere">&#10008;</div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="half_width">
                <div class="w-176px float-left">
                    <div class="message-caption-line m-l-3px w-87px">Приоритет:</div>
                    <div class="message-wrapper-line-number999-px window-border">
                        <input type="number" class="message-text-line" id="valuePriority">
                    </div>
                </div>
                <div class="w-166px float-left">
                    <div class="message-caption-line m-l-3px w-50px">План:</div>
                    <div class="message-wrapper-line-number999-px window-border">
                        <input type="number" class="message-text-line" id="valuePriority">
                    </div>
                </div>
                <div class="w-166px float-left">
                    <div class="message-caption-line m-l-3px w-50px">Факт:</div>
                    <div class="message-wrapper-line-number999-px window-border">
                        <input type="number" class="message-text-line" id="valuePriority">
                    </div>
                </div>
            </div>
            <div class="half_width">
                <div class="radio-container">
                    <div class="form-item radio-btn nth-3">
                        <input type="radio" name="valueType" id="isArchive">
                        <label for="isArchive">Отменено</label>
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
            <div id="goalResult">
                <div class="half_width">

                </div>
                <div class="half_width">

                </div>

                <div class="clearfix"></div>
            </div>


            <div class="clearfix"></div>
            <div class="message-caption-line-8">Заголовок:</div>
            <div class="message-wrap-line w-92 window-border" id="valueTitleWrap">
                <input type="text" class="message-text-line" id="valueTitle" value="<?= $data->title ?>">
            </div>

            <div class="clearfix"></div>
            <div class="submenu m-t-10px">
                    <span class="btn-submenu cur-pointer btn-active" id="btnText">Описание</span>
                    <span class="btn-submenu cur-pointer" id="btnResult">Результаты</span>
                    <!--<span class="btn-submenu"><a href="/goal/dreams-foto">Доска</a></span>-->
                </div>

                <div class="clearfix"></div>
                <div class="new-message-wrapper width-full multistring-min-25 window-border m-t-10px back-cells" id="valueTextWrap">
                    <div contentEditable="true" class="message-text-multistring resize_vertical_only multistring-min-25 ahref" id="valueText"><?= $data->text ?></div>
                </div>
            </div>



        </div>
    </div>

