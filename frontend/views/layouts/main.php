<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        //'brandLabel' => Yii::$app->name,
        //'brandLabel' => Html::img('../img/logo.png', ['alt' => Yii::$app->name]),//'<img src="../../../../resources/images/general/logo.png" style="display:inline; vertical-align: top; height:32px;"/>Yii::$app->name',
        'brandLabel' => Html::img('img/logo.png', ['alt' => Yii::$app->name]), // style="display:inline; vertical-align: top; height:103px;"/>',  //../../../../resources/images/general/logo.png
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/']]
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Зарегистрироваться', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {
        /*$menuItems = [
            ['label' => 'Мои задачи', 'url' => ['/task']],
            ['label' => 'Мои команды', 'url' => ['/team']],
        ];*/
        $menuItems[] = /*['label' => 'Выйти (' . Yii::$app->user->identity->username . ')', 'url' => ['/site/logout']];*/
            '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="main_container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="page_row">
            <div class="left_panel">
                <?php
                if (!(Yii::$app->user->isGuest)) { ?>
                    <!--||||||||||||||||<nav class="navmenu navmenu-inverse" role="navigation">->

                      <!--<a class="navmenu-brand" href="#">Название</a>-->

                    <!--|||||||||||<ul class="nav navmenu-nav navmenu-inverse">
                      <li class="active"><a href="#">Пункт 1</a></li>
                      <li><a href="#">Пункт 4</a></li>-->

                        <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Пункт 3 <b class="caret"></b></a>
                        <ul class="dropdown-menu navmenu-nav" role="menu">
                          <li><a href="#">Пункт 3.1</a></li>
                          <li><a href="#">Пункт 3.2</a></li>
                          <li><a href="#">Пункт 3.3</a></li>
                          <li><a href="#">Пункт 3.4</a></li>
                        </ul>
                      </li>-->

                <!-- <div><a href="#">Пункт 2</a></div>
                      </ul>
                    </nav>-->
                <?php }
                ?>
            </div>
            <div class="center_panel">
                <?= $content ?>
            </div>
            <div class="right_panel"></div>
        </div>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left footer-text">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right footer-text"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
