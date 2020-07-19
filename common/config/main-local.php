<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=gb_tasktracker',
            'dsn' => 'mysql:host=localhost;dbname=che_goal',
            'username' => 'root',
            'password' => '431107',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            //'useFileTransport' => true,
            'useFileTransport' => false,
            /*'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.hostinger.ru',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'robot@yavdele.net',
                'password' => 'XX|jQ1XC$hf@wetl',
                'port' => '587', // Port 25 is a very common port too
                'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            ],*/
        ],
        /*'bootstrap' => [
            'class' => 'common\models\Bootstrap'
        ]*/

        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'yavdele@list.ru',
                'password' => '431107668727cnfc7',
                'port' => '465', // Port 25 is a very common port too
                //'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            ],
            /*'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.hostinger.ru',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'robot@yavdele.net',
                'password' => '431107668727cnfc7',
                'port' => '587', // Port 25 is a very common port too
                'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            ],*/
        /*],*/
    ],
];
