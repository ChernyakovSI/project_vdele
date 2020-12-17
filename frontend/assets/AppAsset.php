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
        'css/site18.css',
        'css/index39.css',
        'css/color14.css',
        'css/acEdit9.css',
        'css/users5.css',
        'css/url2.css',
        'css/fin5.css',
        //'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'css/floatingCircles.css'
    ];
    public $js = [
        //'//htmlweb.ru/geo/api.js',
        'js/apiGeo.js',
        //'js/geo/api.js',
        //'js/githubusercontent.js',
        //'js/messages.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
