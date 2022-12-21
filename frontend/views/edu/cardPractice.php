<?php

$this->title = 'Практика по карточкам';

?>

<div id="paramNum" hidden="hidden"><?= $data->num ?></div>
<div id="paramID" hidden="hidden"><?= $data->id ?></div>
<div id="paramTitle" hidden="hidden"><?= $data->title ?></div>
<div id="paramSphereID" hidden="hidden"><?= $sphere->id ?></div>
<div id="paramSphereName" hidden="hidden"><?= $sphere->name ?></div>
<div id="paramParentID" hidden="hidden"><?= $data->parent ?></div>
<div id="paramPath" hidden="hidden"><?= $path ?></div>

<?php
$group = $data;
$number = 0;
$tab = 1;
?>

<div id="paramNumTab" hidden="hidden"><?= $tab ?></div>

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
    <div class="caption-text" id="form-caption">Практика по карточкам</div>

</div>

<div class="content">
    <section id="content-main" class="container-foto-wrap" <?= $tab === 1 ? '' : 'hidden' ?>>

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-practise">Практиковать</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="half_width">
                <div class="caption-line-10">Раздел:</div><div class="message-wrapper-line-half window-border" id="valueGroupWrap">
                    <input type="text" class="message-text-line" list="list_groups" id="valueGroup" value="<?= isset($group)?$group->title:'' ?>"/>
                    <datalist id="list_groups">
                        <?php foreach ($groups as $thisGroup): ?>
                            <option data-id=<?= $thisGroup['id'] ?>><?= $thisGroup['title'].' ('.$thisGroup['num'].')' ?></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearCard">х</div>
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
                <div class="caption-line-10">Вариант:</div>
                <div class="message-wrapper-line-half window-border" id="valueVariantWrap">
                    <input type="text" class="message-text-line" list="list_variants" id="valueVariant" value=""/>
                    <datalist id="list_variants">

                    </datalist>
                </div>
                <div class="window-button-in-panel window-border gap-v-13" id="ClearVariant">х</div>
            </div>
            <div class="half_width">
                <div class="caption-line-simple-28">Количество ответов:</div>
                <div class="message-wrapper-line-number999-px window-border" id="valueAnswerWrap">
                    <input type="number" class="message-text-line" id="valueAnswer" value="2" min="2" max="2"/>
                </div>

                <div class="caption-line-simple-28">Задать вопросов:</div>
                <div class="message-wrapper-line-number999-px window-border" id="valueQuestionWrap">
                    <input type="number" class="message-text-line" id="valueQuestion" value="2" min="2" max="2"/>
                </div>
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
                                        <div class="message-text-line" id="value1_<?= $number ?>">
                                            <?= $card['value1'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="column-15 border-1px-all col-back-nul colNameImage1 colResize">
                                    <?php if($card['image1_id'] == 0) { ?>
                                            <div class="foto-input-string">
                                                <div class="title m-l-30 m-t-3" id="foto-wrap1-<?= $number ?>">Без фото</div>
                                            </div>
                                    <?php } else { ?>
                                            <div class="foto-input-string" id="foto-wrap1-<?= $number ?>">
                                                <img src="<?= $pathImg.$card['image1_src'] ?>" data-id="<?= $card['image1_id'] ?>" class="div-center" id="image1_<?= $number ?>">
                                            </div>
                                    <?php } ?>
                                </div>
                                <div class="column-5 border-1px-bottom col-back-nul colNameSep colResize">

                                </div>
                                <div class="column-30 border-1px-bottom col-back-nul colNameValue2 colResize">
                                    <div class="wrapper-line">
                                        <div class="message-text-line" id="value2_<?= $number ?>">
                                            <?= $card['value2'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="column-15 border-1px-all col-back-nul colNameImage2 colResize">
                                    <?php if($card['image2_id'] == 0) { ?>
                                            <div class="foto-input-string" id="foto-wrap2-<?= $number ?>">
                                                <div class="title m-l-30 m-t-3">Без фото</div>
                                            </div>
                                    <?php } else { ?>
                                            <div class="foto-input-string" id="foto-wrap2-<?= $number ?>">
                                                <img src="<?= $pathImg.$card['image2_src'] ?>" data-id="<?= $card['image2_id'] ?>" class="div-center" id="image2_<?= $number ?>">
                                            </div>
                                    <?php } ?>
                                </div>

                            </div>

                        <?php endforeach; } ?>
                </div>
            </div>
            <!-- -->
        </div>

    </section>



    <section id="content-trainer" class="container-foto-wrap" <?= $tab === 2 ? '' : 'hidden' ?>>

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-stop">Завершить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="trainer-content" id="fisrtString">

                <!-- //++ 1-2-4-003 20/12/2022
                //*-
                <div class="text-full-center text-bold">Укажите корректное соответствие для <span id="questionText"></span>:</div>
                //*+ -->
                <div class="text-full-center text-bold">Укажите корректное соответствие для <span id="questionText" class="text-color-blue-underlined"></span>:</div>
                <!-- //-- 1-2-4-003 20/12/2022 -->
                <div class="clearfix"></div>

                <!-- //++ 1-2-4-003 20/12/2022
                //*-
                <div class="text-full-center m-t-20px">Вопрос <span id="questionNum"></span> из 6</div>
                //*+ -->
                <div class="text-full-center m-t-20px">Вопрос <span id="questionNum"></span> из <span id="questionTotal"></span></div>
                <!-- //-- 1-2-4-003 20/12/2022 -->

                <div id="answer_list" class="column-33 m-l-33 m-t-20px">
                    <div>
                        <input type="checkbox" id="setVar1" class="custom-checkbox">
                        <label for="setVar1" class="interactive-only" id="AnsVar1">Вариант 1</label>
                    </div>
                    <div>
                        <input type="checkbox" id="setVar2" class="custom-checkbox">
                        <label for="setVar2" class="interactive-only">Вариант 2</label>
                    </div>
                    <div>
                        <input type="checkbox" id="setVar3" class="custom-checkbox">
                        <label for="setVar3" class="interactive-only">Вариант 3</label>
                    </div>
                    <div>
                        <input type="checkbox" id="setVar4" class="custom-checkbox">
                        <label for="setVar4" class="interactive-only">Вариант 4</label>
                    </div>
                </div>
            </div>

            <div id="resultString">

            </div>

            <div class="clearfix"></div>
            <div class="column-33 m-l-48 m-t-20px">
                <div class="window-caption-full window-button-panel">
                    <div class="window-button-in-panel window-border" id="TestNext">Ответить</div>
                </div>
            </div>
        </div>

    </section>
</div>

