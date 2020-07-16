<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'QAANdP88NcmIpD09obWDkISwkMoCHQjG',
        ],
    ],

    'controllerMap' => [
        'ac' => 'frontend\controllers\SiteController',
        //'yyy' => [
        //    'class' => 'app\controllers\SiteController',
        //    'enableCsrfValidation' => false,
        //],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
