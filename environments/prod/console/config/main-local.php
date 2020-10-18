<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=sql213.main-hosting.eu;dbname=u237454327_vdele',
            'username' => 'u237454327_palad',
            'password' => '431107668727cnfc',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            //'useFileTransport' => true,
            'useFileTransport' => false,
            /*'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.hostinger.ru',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'robot@yavdele.net',
                'password' => 'XX|jQ1XC$hf@wetl',
                'port' => '587', // Port 25 is a very common port too
                //'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            ],*/
        ],

    ],
];
