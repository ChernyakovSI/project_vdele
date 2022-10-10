<?php

$this->title = 'Карточка запоминания';

?>

<div id="paramDate" hidden="hidden"><?= $date ?></div>
<div id="paramNum" hidden="hidden"><?= $data->num ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>
<div id="paramIDSphere" hidden="hidden"><?= $data->id_sphere ?></div>
<div id="paramActive" hidden="hidden"><?= $data->is_active ?></div>
<div id="paramParent" hidden="hidden"><?= $data->parent ?></div>

<?php
    $number = 0;
?>
<div id="paramNumString" hidden="hidden"><?= $number ?></div>


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
    <div class="caption-text" id="form-caption">Карточка запоминания</div>

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

            <div class="clearfix"></div>
            <div class="half_width">
                <div class="w-100 m-t-20px">
                    <div class="caption-line-half-20"></div>
                    <div class="">
                        <input type="checkbox" id="setActive" class="custom-checkbox">
                        <label for="setActive" id="setActiveLink" class="interactive-only">Активно</label>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="caption-line-10">Заголовок:</div><div class="message-wrapper-line window-border" id="valueTitleWrap">
                <input type="text" class="message-text-line" id="valueTitle" value="<?= $data->title ?>">
            </div>

            <div class="clearfix"></div>
            <div class="window-button-panel m-t-20px">
                <div class="window-button-in-panel window-border" id="add-card">Добавить</div>
                <!--                <div class="window-button-in-panel window-border" id="btn-copy">Скопировать</div>-->
            </div>

            <div class="clearfix"></div>
            <div class="halfwidth-wrapper m-t-10px">

                <div id="header1">
                    <div class="interactive-only">
                        <div class="column-5 border-1px-bottom colNameNum">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '№' ?></div>
                            </div>
                        </div>
                        <div class="column-30 border-1px-bottom colNameValue1">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Значение 1' ?></div>
                            </div>
                        </div>
                        <div class="column-15 border-1px-bottom colNameImage1">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Картинка 1' ?></div>
                            </div>
                        </div>

                        <div class="column-5 border-1px-bottom colNameSep">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= '' ?></div>
                            </div>
                        </div>
                        <div class="column-30 border-1px-bottom colNameValue2">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Значение 2' ?></div>
                            </div>
                        </div>
                        <div class="column-15 border-1px-all colNameImage2">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Картинка 2' ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div id="list-cards" class="tableResize">
                    <?php
                    if (count($cards) == 0){ ?>

                        <div id="info1" class="text-font-5 text-center margin-v20">
                            Нет данных
                        </div>

                    <?php } else {
                        $pathImg = '/data/img/cards/';
                        foreach ($cards as $card): ?>

                        <div class="fin-acc-row interactive-only reg_<?= $number ?>" id="<?= $card['id'] ?>">

                                <div class="column-5 border-1px-bottom col-back-nul colNameNum colResize">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center"><div>
                                                <?= ++$number ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column-30 border-1px-bottom col-back-nul colNameValue1 colResize">
                                    <div class="wrapper-line">
                                        <input type="text" class="message-text-line" contentEditable id="value1_<?= $number ?>" value="<?= $card['value1'] ?>" onchange="afterEditValue(this)">
                                    </div>
                                </div>
                                <div class="column-15 border-1px-all col-back-nul colNameImage1 colResize">
                                    <?php if($card['image1_id'] == 0) { ?>
                                        <div class="add-img">
                                            <div class="foto-input-string" id="foto-wrap1-<?= $number ?>">
                                                <div class="form-group">
                                                    <label class="label p-10px m-0">
                                                        <div id="labelImg1_<?= $number ?>">
                                                            <span class="title">Добавить фото</span>
                                                        </div>
                                                        <form enctype='multipart/form-data' method='POST' action='' id="form1_<?= $number ?>" hidden="hidden">
                                                            <input type="file" id="fotos1_<?= $number ?>" onchange="handleFiles(this.files, this)">
                                                        </form>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="add-img">
                                            <div class="foto-input-string" id="foto-wrap1-<?= $number ?>">
                                                <div class="form-group w-50 float-left">
                                                    <label class="label p-0 m-0 h-37px">
                                                        <div id="labelImg1_<?= $number ?>">
                                                            <img src="<?= $pathImg.$card['image1_src'] ?>" data-id="<?= $card['image1_id'] ?>" class="img-wrap-33" id="image1_<?= $number ?>">
                                                        </div>
                                                        <form enctype='multipart/form-data' method='POST' action='' id="form1_<?= $number ?>" hidden="hidden">
                                                            <input type="file" id="fotos1_<?= $number ?>" onchange="handleFiles(this.files, this)">
                                                        </form>
                                                    </label>
                                                </div>
                                                <div class="message-text-line-half unactive w-50 p-8px">
                                                    <span class="glyphicon glyphicon-remove symbol_style interactive text-center" onclick="deleteImg1(<?= $number ?>)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="column-5 border-1px-bottom col-back-nul colNameSep colResize">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line text-center">
                                            <div class="message-text-line-half unactive w-100">
                                                <span class="glyphicon glyphicon-remove symbol_style interactive text-center" onclick="deleteCard(<?= $number ?>)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column-30 border-1px-bottom col-back-nul colNameValue2 colResize">
                                    <div class="wrapper-line">
                                        <input type="text" class="message-text-line" contentEditable id="value2_<?= $number ?>" value="<?= $card['value2'] ?>" onchange="afterEditValue(this)">
                                    </div>
                                </div>
                                <div class="column-15 border-1px-all col-back-nul colNameImage2 colResize">
                                    <?php if($card['image2_id'] == 0) { ?>
                                        <div class="add-img">
                                            <div class="foto-input-string" id="foto-wrap2-<?= $number ?>">
                                                <div class="form-group">
                                                    <label class="label p-10px m-0">
                                                        <div id="labelImg2_<?= $number ?>">
                                                            <span class="title">Добавить фото</span>
                                                        </div>
                                                        <form enctype='multipart/form-data' method='POST' action='' id="form2_<?= $number ?>" hidden="hidden">
                                                            <input type="file" id="fotos2_<?= $number ?>" onchange="handleFiles(this.files, this)">
                                                        </form>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="add-img">
                                            <div class="foto-input-string" id="foto-wrap2-<?= $number ?>">
                                                <div class="form-group w-50 float-left">
                                                    <label class="label p-0 m-0 h-37px">
                                                        <div id="labelImg2_<?= $number ?>">
                                                            <img src="<?= $pathImg.$card['image2_src'] ?>" data-id="<?= $card['image2_id'] ?>" class="img-wrap-33" id="image2_<?= $number ?>">
                                                        </div>
                                                        <form enctype='multipart/form-data' method='POST' action='' id="form2_<?= $number ?>" hidden="hidden">
                                                            <input type="file" id="fotos2_<?= $number ?>" onchange="handleFiles(this.files, this)">
                                                        </form>
                                                    </label>
                                                </div>
                                                <div class="message-text-line-half unactive w-50 p-8px">
                                                    <span class="glyphicon glyphicon-remove symbol_style interactive text-center" onclick="deleteImg2(<?= $number ?>)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                        </div>

                    <?php endforeach; } ?>
                </div>
            </div>
            <!-- -->
        </div>

        <div id="prompt-form-container">
            <div id="prompt-form" class="window window-border form-off">
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
                    <div class="caption-text" id="form-caption">Предпросмотр загружаемых фотографий</div>
                    <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
                </div>
                <div class="clearfix"></div>

                <div id="preview" class="flex">

                </div>

                <div class="window-button-panel">
                    <div class="window-button-in-panel window-border" id="button-add">Подтвердить</div>
                    <div class="window-button-in-panel window-border" id="button-del">Отменить</div>
                </div>
            </div>

        </div>

    </div>
</div>

