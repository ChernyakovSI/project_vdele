<?php

$this->title = 'Настройки';

?>

<div id="paramID" hidden="hidden"><?= $user_id ?></div>
<div id="divParamGenSubscribeNews" hidden="hidden"><?= (isset($Settings['b_gen_SubscribeNews']) === true)?$Settings['b_gen_SubscribeNews']:0 ?></div>

<div id="divParamFinUserColor" hidden="hidden"><?= (isset($Settings['b_fin_UserColor']) === true)?$Settings['b_fin_UserColor']:0 ?></div>

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
    <div class="caption-text" id="form-caption">Настройки</div>
</div>

<div class="content">
    <div id="panel-colored" class="window window-border window-caption-full window-button-panel FIO-foto h-46px">
        <div class="window-button-in-panel window-border" id="button-save">Сохранить</div>
        <div class="window-button-in-panel window-border" id="button-cancel">Отменить</div>
    </div>

    <div class="m-t-10px submenu">
        <?php if ($tab === 1){ ?>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-main">Основные</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-sys">Система</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-fin">Финансы</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-con">Контакты</span>
        <?php } ?>
        <?php if ($tab === 2){ ?>
            <span class="btn-submenu btn-submenu-interactive" id="tab-main">Основные</span>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-sys">Система</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-fin">Финансы</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-con">Контакты</span>
        <?php } ?>
        <?php if ($tab === 3){ ?>
            <span class="btn-submenu btn-submenu-interactive" id="tab-main">Основные</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-sys">Система</span>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-fin">Финансы</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-con">Контакты</span>
        <?php } ?>
        <?php if ($tab === 4){ ?>
            <span class="btn-submenu btn-submenu-interactive" id="tab-main">Основные</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-sys">Система</span>
            <span class="btn-submenu btn-submenu-interactive" id="tab-fin">Финансы</span>
            <span class="btn-submenu btn-active btn-submenu-interactive" id="tab-con">Контакты</span>
        <?php } ?>
    </div>

    <section id="content-main" class="window window-border" <?= $tab === 1 ? '' : 'hidden' ?>>
        <div class="w-50 w-m-507px float-left">
            <div class="float-left m-t-17px m-l-10px" id="valueSubscribeNewsWrap">
                <input type="checkbox" id="b_gen_SubscribeNews" class="custom-checkbox" <?= (isset($Settings['b_gen_SubscribeNews']))?(($Settings['b_gen_SubscribeNews'] === '1')?'checked':''):'' ?>>
                <label for="b_gen_SubscribeNews" class="interactive-only">Получать письма о новостях проекта</label>
            </div>
        </div>
    </section>

    <section id="content-sys" class="window window-border" <?= $tab === 2 ? '' : 'hidden' ?>>

    </section>

    <section id="content-fin" class="window window-border" <?= $tab === 3 ? '' : 'hidden' ?>>
        <div class="w-50 w-m-507px float-left">
            <div class="float-left m-t-17px m-l-10px" id="valueColorOnWrap">
                <input type="checkbox" id="b_fin_UserColor" class="custom-checkbox" <?= (isset($Settings['b_fin_UserColor']))?(($Settings['b_fin_UserColor'] === '1')?'checked':''):'' ?>>
                <label for="b_fin_UserColor" class="interactive-only">Использовать пользовательские цвета для категорий</label>
            </div>
        </div>
    </section>

    <section id="content-con" class="window window-border" <?= $tab === 4 ? '' : 'hidden' ?>>

    </section>
</div>