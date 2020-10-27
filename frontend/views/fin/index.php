<?php

/* @var $this yii\web\View class="site-index"*/
use frontend\assets\AppAsset;

AppAsset::register($this);

$this->registerLinkTag([
    'rel' => 'shortcut icon',
    'type' => 'image/x-icon',
    'href' => 'favicon.png',
]);

$this->title = 'Финансы: Счета';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="window window-border window-caption">Счета</div>

<div class="window window-border">
    <?php if (count($accounts) == 0) { ?>
        <div class="text-font text-center">
            У вас пока нет ни одного счета.
        </div>
    <?php } else { ?>
    <?php foreach ($accounts as $account): var_dump($account); ?>



    <?php endforeach; } ?>
</div>

