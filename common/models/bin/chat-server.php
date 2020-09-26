<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use common\models\Chat;

require dirname(__DIR__) . '/../../vendor/autoload.php';
require dirname(__DIR__) . '/../../vendor/yiisoft/yii2/Yii.php';

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

$server->run();