<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\Ac;
use common\models\User;
use common\models\Message;

AppAsset::register($this);

$this->registerLinkTag([
    'rel' => 'shortcut icon',
    'type' => 'image/x-icon',
    'href' => '/frontend/web/favicon.png',
]);

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
        'brandLabel' => Html::img(Yii::$app->params['doman'].'img/logo.png', ['alt' => Yii::$app->name, 'class' => 'logo-pic']), // style="display:inline; vertical-align: top; height:103px;"/>',  //../../../../resources/images/general/logo.png
        'brandUrl' => '/intro',//Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Это тестовый сайт!!!', 'url' => ['/']]
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Зарегистрироваться', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {

        $id = Yii::$app->user->id;
        $curUser = User::findIdentity($id);
        //++ 1-2-3-002 11/05/2022
        $isAdmin = User::isAdmin($id);
        //-- 1-2-3-002 11/05/2022
        if (!User::activated($curUser->email))
        {
            $menuItems[] = ['label' => '(!) Выслать ссылку активации', 'url' => ['/site/send-confirm-letter']];
            $menuItems[] = ['label' => 'Написать в поддержку', 'url' => ['/dialog?id=1']];
        }
        else
        {
            $QUnreadMessages = Message::GetQuantityOfUnreadDialogs($id);
            $menuItems[] = ['label' => 'Система', 'items' => [
                ['label' => 'Календарь', 'url' => ['/goal/calendar']],
                ['label' => ''],['label' => ''],
                //++ 1-2-3-004 26/07/2022
                ['label' => 'Задачи (dev)', 'url' => ['/goal/tasks-all']],
                //-- 1-2-3-004 26/07/2022
                ['label' => 'Цели', 'url' => ['/goal/goals']],
                ['label' => 'Намерения', 'url' => ['/goal/intents']],
                ['label' => 'Желания', 'url' => ['/goal/wishes']],
                ['label' => 'Мечты', 'url' => ['/goal/dreams']],
                ['label' => ''],['label' => ''],
                //++ 1-3-1-003 21/02/2023
                //*-
                //['label' => 'Веб-ссылки', 'url' => ['/url/all']],
                //['label' => ''],['label' => ''],
                //['label' => 'Заметки', 'url' => ['/goal/notes']],
                //*+
                ['label' => 'Заметки', 'url' => ['/goal/notes']],
                ['label' => 'Дневники', 'url' => ['/goal/diaries']],
                ['label' => 'Веб-ссылки', 'url' => ['/url/all']],
                ['label' => ''],['label' => ''],
                //-- 1-3-1-003 21/02/2023
                ['label' => 'Сферы жизни', 'url' => ['/goal/spheres']],
            ]];
            $menuItems[] = ['label' => 'Финансы', 'items' => [
                ['label' => 'Счета', 'url' => ['/fin/accounts']],
                ['label' => 'Категории', 'url' => ['/fin/categories']],
                ['label' => 'Движения', 'url' => ['/fin/register']],
                ['label' => 'Отчеты', 'url' => ['/fin/reports']],
            ]];
            $menuItems[] = ['label' => 'Контакты'.(($QUnreadMessages != 0)?(' ('.$QUnreadMessages.')'):('')), 'items' => [
                    //++ 1-3-1-003 21/02/2023
                    //['label' => 'Моя страница', 'url' => [Yii::$app->homeUrl]],
                    //-- 1-3-1-003 21/02/2023
                    ['label' => 'Пользователи', 'url' => ['/users']],
                    ['label' => 'Диалоги'.(($QUnreadMessages != 0)?(' ('.$QUnreadMessages.')'):('')), 'url' => ['/dialog']],
                    ['label' => ''],['label' => ''],
                    ['label' => 'Написать в поддержку', 'url' => ['/dialog?id=1']],
            ]];
            //++ 1-2-4-001 31/08/2022
            $menuItems[] = ['label' => 'Обучение', 'items' => [
                ['label' => 'Карточки памяти', 'url' => ['/edu/cards']],
                //++ 1-3-1-003 21/02/2023
                ['label' => ''],['label' => ''],
                ['label' => 'gotodo.ru', 'url' => ['/url/gotodo']],
                //-- 1-3-1-003 21/02/2023
            ]];
            //-- 1-2-4-001 31/08/2022
        }
        /*$menuItems = [
            ['label' => 'Мои задачи', 'url' => ['/task']],
            ['label' => 'Мои команды', 'url' => ['/team']],
        ];*/
        $ac = new Ac();
        //++ 1-2-3-002 11/05/2022
        $lastItems = [];
        //++ 1-3-1-003 21/02/2023
        $lastItems[] = ['label' => 'Мой профиль', 'url' => [Yii::$app->homeUrl]];
        //-- 1-3-1-003 21/02/2023
        if($isAdmin === true) {
            $lastItems[] = ['label' => 'Рассылка', 'url' => ['/sender-panel']];
            //++ 1-2-3-003 28/06/2022
            $lastItems[] = ['label' => 'Логи', 'url' => ['/logs']];
            //-- 1-2-3-003 28/06/2022
        }

        if (User::activated($curUser->email))
        {
            $lastItems[] =  ['label' => 'Настройки', 'url' => ['/settings']];
        }
        //++ 1-3-1-003 21/02/2023
        $lastItems[] = ['label' => ''];
        $lastItems[] = ['label' => ''];
        //-- 1-3-1-003 21/02/2023
        $lastItems[] =  ['label' => 'Выйти', 'url' => '/site/logout', 'linkOptions' => ['data-method' => 'post']];
        //-- 1-2-3-002 11/05/2022

        //++ 1-2-3-002 11/05/2022
        //*-
        //$menuItems[] = ['label' => $ac->getFIO(Yii::$app->user->identity->getId(), true), 'items' => [
        //    ['label' => 'Выйти', 'url' => '/site/logout', 'linkOptions' => ['data-method' => 'post']],
        //]];
        //*+
        $menuItems[] = ['label' => $ac->getFIO(Yii::$app->user->identity->getId(), true), 'items' => $lastItems];
        //-- 1-2-3-002 11/05/2022

            /*['label' => 'Выйти (' . Yii::$app->user->identity->username . ')', 'url' => ['/site/logout']];*/
            /*'<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . $ac->getFIO(Yii::$app->user->identity->getId(), true) . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';*/
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="main_container">

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
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
            <div class="right_panel"></div>
        </div>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <!-- //++ 1-2-2-007 05/04/2022 -->
        <p class="pull-left footer-text">&copy; <?= Html::encode(Yii::$app->name) ?> 2020-<?= date('Y') ?> (v.1.3.2.1)</p>
        <!-- //-- 1-2-2-007 05/04/2022 -->

        <p class="pull-right footer-text"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>