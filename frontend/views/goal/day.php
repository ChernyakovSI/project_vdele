<?php

use common\models\fin\Account;

$this->title = 'День';

?>

<div id="paramDate" hidden="hidden"><?= $date ?></div>
<div id="paramIDSphere" hidden="hidden"><?= 1//($dayData !== null) ? $dayData->id_sphere : 0 ?></div>

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
                <div class="caption-head m-t-10px col-back-inn-light">
                    Заметки, напоминания, задачи
                </div>

                <div class="halfwidth m-t-10px">
                    <div class="interactive-only">
                        <div class="border-1px-right">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= 'Заметки' ?></div>
                            </div>
                        </div>
                    </div>
                    <div id="list-notes">

                    </div>
                </div>
            </div>


            <!-- Начало фин отчета -->
            <div id="content-fin" hidden="hidden">
                <div class="clearfix"></div>
                <div class="caption-head m-t-10px col-back-fin-light">
                    Движения за день
                </div>
                <div class="clearfix"></div>
                <div class="text-size">
                    Прибыль: <span id="delta"></span>
                </div>

                <div class="clearfix"></div>
                <div class="halfwidth-wrapper">
                    <div class="halfwidth">

                        <div  class="text-font-5 text-center">
                            Расходы
                        </div>
                        <div class="clearfix"><hr class="line"></div>
                        <div id="header-expenses">
                            <div class="interactive-only">
                                <div id="expenses-colname-cat" class="fin-reg-cat-60 table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line table-caption"><?= 'Категория' ?></div>
                                    </div>
                                </div>
                                <div id="expenses-colname-amo" class="fin-reg-amount-end table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="">
                                <div id="expenses-coltotal-cat" class="fin-reg-cat-60 table-text brown-back">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line"><?= '' ?></div>
                                    </div>
                                </div>
                                <div id="expenses-coltotal-amo" class="fin-reg-amount-end table-text brown-back">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line right-text" id="totalExp"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"><hr class="line"></div>
                        <div id="list-expenses">
                            <div id="infoExp" class="text-font-5 text-center margin-v20">
                                Нет данных
                            </div>
                        </div>

                    </div>
                    <div class="halfwidth-gap">&nbsp;</div>
                    <div class="halfwidth">

                        <div  class="text-font-5 text-center">
                            Доходы
                        </div>
                        <div class="clearfix"><hr class="line"></div>

                        <div class="interactive-only">
                            <div id="profits-colname-cat" class="fin-reg-cat-60 table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line table-caption"><?= 'Категория' ?></div>
                                </div>
                            </div>
                            <div id="profits-colname-amo" class="fin-reg-amount-end table-text">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line table-caption"><?= 'Сумма' ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="">
                            <div id="profits-coltotal-cat" class="fin-reg-cat-60 table-text brown-back">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line"><?= '' ?></div>
                                </div>
                            </div>
                            <div id="profits-coltotal-amo" class="fin-reg-amount-end table-text brown-back">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line right-text" id="totalProf"></div>
                                </div>
                            </div>
                        </div>



                        <div class="clearfix"><hr class="line"></div>
                        <div id="list-profits">
                            <div id="infoProf" class="text-font-5 text-center margin-v20">
                                Нет данных
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Конец фин отчета -->


        </div>
    </div>
</div>