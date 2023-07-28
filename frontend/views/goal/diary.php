<?php

$this->title = 'Дневник';

?>

<div id="paramDescription" hidden="hidden"><?= $data->description ?></div>
<div id="paramTitle" hidden="hidden"><?= $data->title ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>
<div id="paramIDSphere" hidden="hidden"><?= $data->id_sphere ?></div>
<div id="paramPublic" hidden="hidden"><?= $data->is_public ?></div>
<div id="paramPriority" hidden="hidden"><?= $data->priority ?></div>
<div id="paramDomain" hidden="hidden"><?= Yii::$app->params['doman'] ?></div>
<div id="paramDateFrom" hidden="hidden"><?= $dateFrom ?></div>
<div id="paramDateTo" hidden="hidden"><?= $dateTo ?></div>

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
    <div class="caption-text" id="form-caption">Дневник "<?= $data->title ?>"</div>

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
                <div class="window-button-in-panel window-border" id="button-settings">Настройка</div>
            <?php } else { ?>
                <div class="window-button-in-panel window-border visible-not" id="button-settings">Настройка</div>
            <?php } ?>
        </div>

        <div class="window window-border main-info-foto">
            <div class="Rollup">
                <input class="hide" id="hd-1" type="checkbox">
                <label for="hd-1">Описание дневника</label>
                <div>
                    <div class="half_third">
                        <div class="message-wrap-line w-80 window-border" id="valueSphereWrap">
                            <?php if($isGuest == 0) { ?>
                            <input type="text" class="message-text-line" placeholder="Сфера" list="list_sphere" id="valueSphere" value="<?= isset($data) && $data->id_sphere>0 ? $spheres[$data->id_sphere-1]['name'] : '' ?>"/>
                            <?php } else { ?>
                                <input disabled type="text" class="message-text-line" placeholder="Сфера" list="list_sphere" id="valueSphere" value="<?= isset($data) && $data->id_sphere>0 ? $spheres[$data->id_sphere-1]['name'] : '' ?>"/>
                            <?php } ?>

                            <datalist id="list_sphere">
                                <?php foreach ($spheres as $sphere): ?>
                                    <option data-id=<?= $sphere['id'] ?>><?= $sphere['name'] ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <?php if($isGuest == 0) { ?>
                        <div class="w-20 float-left">
                            <div class="float-left">&nbsp</div>
                            <div class="window-button-in-panel-percent window-border gap-v-13" id="ClearSphere">&#10008;</div>
                        </div>
                        <?php } else { ?>
                            <div class="w-20 float-left visible-not">
                                <div class="float-left">&nbsp</div>
                                <div class="window-button-in-panel-percent window-border gap-v-13" id="ClearSphere">&#10008;</div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="half_third">
                        <div class="message-caption-line w-50 w-m-75px">Приоритет:</div>
                        <div class="float-left w-3">&nbsp</div>
                        <div class="message-wrapper-line w-47 window-border w-m-43px">
                            <?php if($isGuest == 0) { ?>
                            <input type="number" class="message-text-line" id="valuePriority" value="<?= $data->priority ?>">
                            <?php } else { ?>
                                <input disabled type="number" class="message-text-line" id="valuePriority" value="<?= $data->priority ?>">
                            <?php } ?>
                        </div>
                    </div>

                    <div class="half_third">
                        <?php if($isGuest == 0) { ?>
                        <div class="w-100 m-t-20px">
                            <div class="caption-line-half-20"></div>
                            <div class="">
                                <input type="checkbox" id="setPublic" class="custom-checkbox">
                                <label for="setPublic" id="setPublicLink" class="interactive-only">Опубликовать</label>
                            </div>
                            <div class="caption-line-half-20 visible-not" id="PublicURLgap"></div>
                            <div class="" id="PublicURL"></div>
                        </div>
                        <?php } else { ?>
                            <div class="w-100 m-t-20px visible-not">
                                <div class="caption-line-half-20"></div>
                                <div class="">
                                    <input type="checkbox" id="setPublic" class="custom-checkbox">
                                    <label for="setPublic" id="setPublicLink" class="interactive-only">Опубликовать</label>
                                </div>
                                <div class="caption-line-half-20 visible-not" id="PublicURLgap"></div>
                                <div class="" id="PublicURL"></div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="clearfix"></div>
                    <div class="message-wrapper-line w-100 window-border" id="valueTitleWrap">
                        <?php if($isGuest == 0) { ?>
                        <input type="text" class="message-text-line" placeholder="Заголовок" id="valueTitle" value="<?= $data->title ?>">
                        <?php } else { ?>
                            <input disabled type="text" class="message-text-line" placeholder="Заголовок" id="valueTitle" value="<?= $data->title ?>">
                        <?php } ?>
                    </div>

                    <div class="clearfix"></div>
                    <div class="new-message-wrapper width-full h-m-20em window-border m-t-10px back-cells" id="valueTextWrap">
                        <?php if($isGuest == 0) { ?>
                        <div contentEditable="true" class="message-text-multistring resize_vertical_only h-m-20em ahref" id="valueText"><?= $data->description ?></div>
                        <?php } else { ?>
                            <div contentEditable="false" class="message-text-multistring resize_vertical_only h-m-20em ahref" id="valueText"><?= $data->description ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="Rollup">
                <input class="hide" id="hd-2" type="checkbox">
                <!-- //++ 1-3-1-005 28/04/2023
                //*-
                <label for="hd-2">Настройки дневника</label>
                //*+ -->
                <label for="hd-2">Настройки и итоги дневника</label>
                <!-- //++ 1-3-1-005 28/04/2023 -->
                <div>
                    <div class="w-25 float-left w-m-255px">
                        <div class="w-20 float-left">
                            <div class="message-caption-line w-40">с:</div>
                        </div>

                        <div class="w-80 float-left">
                            <div class="message-wrapper-line-datetime-px window-border">
                                <input type="date" class="message-text-line" id="valueDateFrom" value="<?= $dateFrom ?>">
                            </div>
                        </div>
                    </div>
                    <div class="w-25 float-left w-m-255px">
                        <div class="w-20 float-left">
                            <div class="message-caption-line w-40">по:</div>
                        </div>

                        <div class="w-80 float-left">
                            <div class="message-wrapper-line-datetime-px window-border">
                                <input type="date" class="message-text-line" id="valueDateTo" value="<?= $dateTo ?>">
                            </div>
                        </div>
                    </div>

                    <!-- //++ 1-3-1-005 28/04/2023 -->
                    <div class="clearfix"></div>
                    <div id="finalValues" class="m-t-20px"></div>
                    <!-- //-- 1-3-1-005 28/04/2023 -->
                </div>
            </div>


            <div class="clearfix"></div>
            <div class="message-wrapper-title">
                <div class="message-text-line table-caption"><?= 'Записи' ?></div>
            </div>
            <?php if($isGuest == 0) { ?>
            <div class="window-button-panel">
                <div class="window-button-in-panel window-border m-b-10px" id="new-record">Добавить</div>
            </div>
            <?php } else { ?>
                <div class="window-button-panel visible-not">
                    <div class="window-button-in-panel window-border m-b-10px" id="new-record">Добавить</div>
                </div>
            <?php } ?>

            <div class="clearfix"></div>
            <div id="header1" class="tableResize">
                <div class="interactive-only">
                    <div class="<?= count($dataTable)==0?'column-100':'column-10' ?> colDate border-1px-all colResize">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Дата' ?></div>
                        </div>
                    </div>
                    <!-- //++ 1-3-1-005 28/04/2023
                    //*-
                    <php foreach ($dataTable as $record): ?>
                    //*+  -->
                    <?php
                    $num = 0;
                    //var_dump($dataTable);
                    foreach ($dataTable as $record):
                        $num = $num + 1;
                        if($num > 5) {
                            break;
                        }
                        ?>
                        <!-- //-- 1-3-1-005 28/04/2023 -->
                        <div class="<?= $record['widthClass'] ?> colDate border-1px-all colResize">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $record['param_title'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="clearfix"></div>
            <div id="list-cards" class="tableResize">
                <?php
                if (count($records) == 0){ ?>

                    <div id="info1" class="text-font-5 text-center margin-v20">
                        Нет записей
                    </div>

                <?php } else {
                    foreach ($records as $record): ?>
                        <?php $curPath = '/goal/diary-record/'.$record['id']; ?>
                        <a href="<?= $curPath ?>">
                        <div class="fin-acc-row movement-back interactive-only reg_<?= $record['id'] ?>" id="<?= $record['id'] ?>">
                            <div class="<?= count($dataTable)==0?'column-100':'column-10' ?> border-1px-all colDate colResize">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line text-center"><?= date("d.m.Y", $record['date']) ?></div>
                                </div>
                            </div>
                            <?php foreach ($dataTable as $column): ?>
                                <div class="<?= $column['widthClass'] ?> border-1px-all">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><?php
                                            if ((integer)$column['param_type'] == 5) {
                                                //echo date("d.m.Y H:m:s", $dataRecords[$record['id']][$column['param_id']]);
                                            } elseif ((integer)$column['param_type'] == 3) {
                                                echo ((integer)$dataRecords[$record['id']][$column['param_id']] == 1?
                                                    '<i class="fa fa-check-circle symbol_style text-center text-color-green" aria-hidden="true"></i>':
                                                    '<i class="fa fa-ban symbol_style text-center text-color-red" aria-hidden="true"></i>');
                                            } else {
                                                echo $dataRecords[$record['id']][$column['param_id']];
                                            }

                                            ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        </a>

                    <?php endforeach; } ?>
            </div>
        </div>



    </div>
</div>

