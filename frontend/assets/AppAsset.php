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
        'css/site14.css',
        'css/index19.css',
        'css/color6.css',
        'css/acEdit9.css',
        'css/users4.css',
        //'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'
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
