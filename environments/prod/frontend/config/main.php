<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log',
                    'common\models\Bootstrap'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        /*'request' => [
            'csrfParam' => '_csrf-frontend',
        ],*/
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                //++ 1-2-2-004 20/03/2022
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error', 'warning'],
                    //'categories' => ['yii\db\*'],
                    'message' => [
                        'from' => [$params['robotEmail']],
                        'to' => ['paladin_cool@inbox.ru'],
                        'subject' => 'Ошибки на сайте '.$params['domanName'],
                        ],
                ],
                //-- 1-2-2-004 20/03/2022
            ],
        ],
        'errorHandler' => [
            //++ 1-2-2-004 18/03/2022
            //*-
            //'errorAction' => 'site/error',
            //*+
            'errorAction' => 'site/error-user',
            //-- 1-2-2-004 18/03/2022
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'suffix' => '.html',
            'rules' => [
                '' => 'site/index',


                '<action>'=>'site/<action>',
                '<controller:[\w-]+>/<id:\d+>' => '<controller>/view',//контроллер теперь понимает путь task/123 и работает как с task/view?id=12
                '<controller>' => '<controller>/index',
                '<controller>s' => '<controller>/index',//контроллер теперь понимает путь tasks и работает как с task/index

                '<controller:[\w-]+>/<action:[\w-]+>/<n:\d+>' => '<controller>/<action>',//контроллер теперь понимает путь task/123 и работает как с task/view?id=12
            ],
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets'
        ],
        'request' => [
            'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:j M Y',
            'datetimeFormat' => 'php:j M Y H:i',
            'timeFormat' => 'php:H:i',

            'timeZone' => 'Europe/Moscow',
        ],
    ],
    'timeZone' => 'Europe/Moscow',
    'params' => $params,

];
