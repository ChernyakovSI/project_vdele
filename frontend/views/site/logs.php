<?php

use common\models\User;

$this->title = 'Логи';

$colNameDate = 'Дата';

?>

<div id="paramPeriodFrom" hidden="hidden"><?= $periodFrom ?></div>
<div id="paramPeriodTo" hidden="hidden"><?= $periodTo ?></div>

<div class="window window-border window-caption-2em caption-wrap">
    <div class="caption-begin"><?='&nbsp;'?></div>
    <div class="caption-text-new">Логи<div><?='&nbsp;'?></div></div>
    <div class="caption-close-new">
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
</div>
<div class="clearfix"></div>

<div class="content">
    <div class="window window-border gap-v" id="main-window">
        <div class="Rollup">
            <input class="hide" id="hd-1" type="checkbox">
            <label for="hd-1">Настройки списка</label>
            <div>
                <div class="half_third">
                    <div class="caption-line-half-20">c:</div>
                    <div class="message-wrapper-line-half window-border">
                        <input type="date" class="message-text-line" contentEditable id="valuePeriodFrom">
                    </div>
                    <div class="caption-line-half-20">по:</div>
                    <div class="message-wrapper-line-half window-border">
                        <input type="date" class="message-text-line" contentEditable id="valuePeriodTo">
                    </div>
                </div>
                <div class="half_third">
                    <div class="caption-line-simple-28">Пользователь:</div><div class="message-wrapper-line-half window-border" id="valueUserWrap">
                        <input type="text" class="message-text-line" list="list_user" id="valueUser" value=""/>
                        <datalist id="list_user">
                            <?php foreach ($users as $user): ?>
                                <option data-id=<?= $user['id_user'] ?>><?= User::getFIO_s($user['id_user'], false).' ('.$user['id_user'].')' ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="window-button-in-panel window-border gap-v-13" id="ClearUser">х</div>

                    <div class="caption-line-simple-28">Статус:</div><div class="message-wrapper-line-half window-border" id="valueStatusWrap">
                        <input type="text" class="message-text-line" list="list_status" id="valueStatus" value=""/>
                        <datalist id="list_status">
                            <?php foreach ($statuses as $status): ?>
                                <option data-id=<?= $status['name'] ?>><?= $status['name'] ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="window-button-in-panel window-border gap-v-13" id="ClearStatus">х</div>
                </div>
                <div class="half_third">

                </div>
            </div>
        </div>

        <div class="clearfix gap-v-60"></div>
        <div class="interactive-only">
            <div class="column-10 border-1px-bottom colNameDate">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= $colNameDate ?></div>
                </div>
            </div>
            <div class="column-20 border-1px-bottom colNameUser">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Пользователь' ?></div>
                </div>
            </div>
            <div class="column-20 border-1px-bottom colNameURL">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'URL' ?></div>
                </div>
            </div>
            <div class="column-20 border-1px-bottom colNameStatus">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Статус' ?></div>
                </div>
            </div>
            <div class="column-30 border-1px-all colNameDescription">
                <div class="message-wrapper-title">
                    <div class="message-text-line table-caption"><?= 'Описание' ?></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div id="list-logs">
            <?php
            if (count($logs) == 0){ ?>

                <div id="info1" class="text-font-5 text-center margin-v20">
                    Нет данных
                </div>

            <?php } else {  foreach ($logs as $log): ?>

                <?php $curPath = 'log/'.$log['id']; ?>
                <div class="fin-acc-row interactive-only id="<?= $log['id'] ?>">
                    <a href="<?= $curPath ?>">
                        <div class="column-10 border-1px-bottom col-back-nul colNameDate">
                            <div class="message-wrapper-title">
                                <div class="message-text-line text-center"><?= date("d.m.Y H:i:s", $log['created_at']) ?></div>
                            </div>
                        </div>
                        <div class="column-20 border-1px-bottom col-back-nul colNameUser">
                            <div class="message-wrapper-title">
                                <div class="message-text-line text-center"><?= User::getFIO_s($log['id_user'], false).' ('.$log['id_user'].')' ?></div>
                            </div>
                        </div>
                        <div class="column-20 border-1px-bottom col-back-nul colNameURL">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $log['url'] ?></div>
                            </div>
                        </div>
                        <div class="column-20 border-1px-bottom col-back-nul colNameStatus">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $log['status'] ?></div>
                            </div>
                        </div>
                        <div class="column-30 border-1px-all col-back-nul colNameDescription">
                            <div class="message-wrapper-title">
                                <div class="message-text-line table-caption"><?= $log['description'] ?></div>
                            </div>
                        </div>
                    </a>
                </div>

            <?php endforeach; } ?>
        </div>

    </div>
</div>
