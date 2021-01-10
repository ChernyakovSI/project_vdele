<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site20.css',
        'css/index46.css',
        'css/color17.css',
        'css/acEdit10.css',
        'css/users5.css',
        'css/url2.css',
        'css/fin8.css',
        'css/useful1.css',
        //'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'css/floatingCircles.css',
        'css/contextMenu.css'
    ];
    public $js = [];
//    [
//        //'//htmlweb.ru/geo/api.js',
//        'js/apiGeo.js',
//        //'js/geo/api.js',
//        //'js/githubusercontent.js',
//        //'js/messages.js',
//    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        if (\yii::$app->request->url == '/site/ac-edit') {
            $this->js[] = 'js/apiGeo.js';
        }
        if (\yii::$app->request->url == '/fin/register') {
            $this->js[] = 'js/fin/register_pos_ready.js';
        }
    }
}
