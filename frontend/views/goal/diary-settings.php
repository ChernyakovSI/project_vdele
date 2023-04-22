<?php

$this->title = 'Настройки дневника';

?>

<div id="paramIDDiary" hidden="hidden"><?= $diary->id ?></div>

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
    <div class="caption-text" id="form-caption">Настройки дневника "<?= $diary->title ?>"</div>

</div>

<div class="content">
    <div class="container-foto-wrap">

        <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
            <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
            <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
        </div>

        <div class="window window-border main-info-foto">
            <div class="message-wrapper-title">
                <div class="message-text-line table-caption"><?= 'Настройки полей' ?></div>
            </div>
            <div class="window-button-panel">
                <div class="window-button-in-panel window-border m-b-10px" id="new-record">Добавить</div>
            </div>

            <div class="clearfix"></div>
            <div id="header1" class="tableResize">
                <div class="interactive-only">
                    <div class="column-5 colNum border-1px-bottom colResize">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= '№' ?></div>
                        </div>
                    </div>
                    <div class="column-45 colTitle border-1px-bottom colResize">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Название' ?></div>
                        </div>
                    </div>
                    <div class="column-20 colType border-1px-bottom colResize">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Тип' ?></div>
                        </div>
                    </div>
                    <div class="column-10 colShow border-1px-bottom colResize">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Отображение в таблице' ?></div>
                        </div>
                    </div>
                    <div class="column-10 colPrio border-1px-bottom colResize">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Приоритет отображения' ?></div>
                        </div>
                    </div>
                    <div class="column-10 colActive border-1px-all colResize">
                        <div class="message-wrapper-title">
                            <div class="message-text-line table-caption"><?= 'Включено' ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div id="list-settings" class="tableResize">
                <?php
                if (count($data) == 0){ ?>

                    <div id="info1" class="text-font-5 text-center margin-v20">
                        Нет дополнительных полей
                    </div>

                <?php } else {
                    foreach ($data as $record): ?>
                    <div class="fin-acc-row movement-back interactive-only reg_<?= $record['id'] ?>" id="<?= $record['id'] ?>">
                        <div class="column-5 border-1px-bottom colNum colResize">
                            <div class="message-wrapper-title">
                                <div class="message-text-line text-center"><?= $record['num'] ?></div>
                            </div>
                        </div>
                        <div class="column-45 border-1px-bottom colTitle colResize">
                            <div class="wrapper-line">
                                <input type="text" class="message-text-line" contentEditable id="title_<?= $record['id'] ?>" value="<?= $record['title'] ?>" onchange="afterEditValue(this)">
                            </div>
                        </div>
                        <div class="column-20 border-1px-bottom colType colResize">
                            <div class="wrapper-line">
                                <input type="text" class="message-text-line" list="list_type" contentEditable id="type_<?= $record['id'] ?>" value="<?= $record['type']>0?$types[$record['type']-1]['name']:'' ?>" onchange="afterEditValue(this)">
                            </div>
                        </div>
                        <div class="column-10 border-1px-bottom colShow colResize">
                            <div class="wrapper-line">
                                <div class="message-text-line text-center">
                                    <input type="checkbox" id="is_show_<?= $record['id'] ?>" class="custom-checkbox" <?= $record['is_show'] == 1 ? "checked" : "" ?> onchange="afterEditValue(this)">
                                    <label for="is_show_<?= $record['id'] ?>" class="interactive-only"></label>
                                </div>
                            </div>
                        </div>
                        <div class="column-10 border-1px-bottom colPrio colResize">
                            <div class="wrapper-line">
                                <input type="number" class="message-text-line" contentEditable id="show_priority_<?= $record['id'] ?>" value="<?= $record['show_priority'] ?>" onchange="afterEditValue(this)">
                            </div>
                        </div>
                        <div class="column-10 border-1px-all colActive colResize">
                            <div class="wrapper-line">
                                <div class="message-text-line text-center">
                                    <input type="checkbox" id="is_active_<?= $record['id'] ?>" class="custom-checkbox" <?= $record['is_active'] == 1 ? "checked" : "" ?> onchange="afterEditValue(this)">
                                    <label for="is_active_<?= $record['id'] ?>" class="interactive-only"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; } ?>
            </div>
        </div>

        <datalist id="list_type">
            <?php foreach ($types as $type): ?>
                <option data-id=<?= $type['id'] ?>><?= $type['name'] ?></option>
            <?php endforeach; ?>
        </datalist>

    </div>
</div>

