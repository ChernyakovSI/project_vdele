<?php
use yii\helpers\Html;

$this->title = 'Веб-ссылки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="window window-border window-caption">Веб-ссылки</div>

<div class="content">
    <div class="url-container-wrap">
        <div class="url-window-right window-border url-sidebar-left">
            <div class="dialog-dialogsList-header window-subcaption">
                Категории
            </div>
            <div class="clearfix"><hr class="line"></div>
            <div class="fin-acc-row white-back interactive-only" onclick="fullUrl(0)" ondblclick="editCategory(0)" id="0">
                <div class="url-col-category table-text">
                    <div class="message-wrapper-title">
                        <div class="message-text-line">Без категории</div>
                    </div>
                </div>
                <div class="clearfix"><hr class="line"></div>
            </div>
            <div id="list-categories">

                <?php foreach ($categories as $category): ?>
                    <div class="fin-acc-row white-back interactive-only" onclick="fullUrl(<?= $category['id'] ?>)" ondblclick="editCategory(<?= $category['id'] ?>)" id="<?= $category['id'] ?>">
                        <div class="url-col-category table-text">
                            <div class="message-wrapper-title">
                                <div class="message-text-line"><?= $category['num'] ?>. <?= $category['name'] ?></div>
                            </div>
                        </div>
                        <div class="clearfix"><hr class="line"></div>
                    </div>

                <?php endforeach; ?>

            </div>

            <div class="window-button window-border" id="new-category" onclick="addCategory()">Добавить</div>
        </div>
        <div class="window window-border url-main" id="main-window">

            <div id="list-urls">
                <?php if (count($urls) == 0) { ?>
                    <div id="info" class="text-font text-center margin-v20">
                        У вас пока нет ни одной веб-ссылки.
                        <br>
                        Добавляйте здесь важные для вас веб-ссылки, чтобы не держать открытыми вкладки в браузере
                        <br>
                        и иметь возможность быстро их открывать
                    </div>
                <?php } else { ?>
                    <?php foreach ($urls as $url): ?>
                        <div class="fin-acc-row white-back interactive-only" id="<?= $url['id'] ?>">
                            <a href="<?= $url['url'] ?>">
                                <div class="url-col-name table-text">
                                    <div class="message-wrapper-title">
                                        <div class="message-text-line"><?= $url['num'] ?>. <?= $url['name'] ?></div>
                                    </div>
                                </div>
                            </a>
                            <div class="url-col-panel" ondblclick="editUrl(<?= $url['id'] ?>)">
                                <div class="message-wrapper-title">
                                    <div class="message-text-line unactive">
                                        <span class="glyphicon glyphicon-pencil symbol_style interactive text-center" onclick="editUrl(<?= $url['id'] ?>)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"><hr class="line"></div>
                        </div>

                    <?php endforeach; } ?>
            </div>

            <div class="clearfix"></div>
            <div class="window-button window-border" id="new-url" onclick="addUrl()">Добавить</div>
        </div>
    </div>

    <div id="prompt-form-container">
        <div id="prompt-form" class="window window-border">
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
                <div class="caption-text" id="form-caption">Новый счет</div>
                <div class="caption-close" id="btnClose"><i class="fa fa-times interactive symbol_style" aria-hidden="true"></i></div>
            </div>
            <div class="clearfix"></div>

            <div>
                <div class="caption-line">Счет:</div><div class="message-wrapper-line window-border" id="valueAccWrap">
                    <div class="message-text-line" contentEditable id="valueAcc" >Новый счет</div>
                </div>
            </div>
            <div class="half_width">
                <div class="caption-line-half">Начальный остаток:</div><div class="message-wrapper-line-half window-border">
                    <div class="message-text-line" contentEditable id="valueAmo" >0</div>
                </div>
            </div>
            <div class="half_width">
                <div class="caption-line-half">Порядок:</div><div class="message-wrapper-line-half window-border">
                    <input type="number" class="message-text-line" contentEditable id="valueNum" value="1" min="1">
                </div>
            </div>
            <div>
                <div class="caption-line">Комментарий:</div><div class="message-wrapper-line window-border">
                    <div class="message-text-line" contentEditable id="valueCom" ></div>
                </div>
            </div>
            <div>
                <div class="caption-line">
                    <input type="checkbox" id="valueDel" class="custom-checkbox">
                    <label for="valueDel" class="interactive-only">Скрыть счет</label>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="red-comment" id="red-comment"></div>
            <div class="window-button window-border" id="button-add">Подтвердить</div>
        </div>

    </div>
</div>

