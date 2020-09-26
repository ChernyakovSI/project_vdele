<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=gb_tasktracker',
            'dsn' => 'mysql:host=localhost;dbname=che_goal',
            'username' => 'root',
            'password' => '431107',
            'charset' => 'utf8',
        ],
    ],
];
