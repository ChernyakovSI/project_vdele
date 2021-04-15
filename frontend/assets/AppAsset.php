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

    //public $dataUrl = '@web';

    public $css = [
        'css/site22.css',
        'css/index47.css',
        'css/color22.css',
        'css/acEdit11.css',
        'css/users6.css',
        'css/url2.css',
        'css/fin10.css',
        'css/useful1.css',
        //'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'css/floatingCircles3.css',
        'css/contextMenu.css',
        'css/foto1.css',
        'css/slider.css'
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
            $this->js[] = 'js/profile/ac-edit_pos_ready.js';
        }
        if (\yii::$app->request->url == '/fin/register') {
            $this->js[] = 'js/fin/register_pos_begin.js';
            $this->js[] = 'js/fin/register_pos_ready.js';
        }
        if (\yii::$app->request->url == '/fin/accounts') {
            $this->js[] = 'js/fin/index_pos_begin.js';
            $this->js[] = 'js/fin/index_pos_ready.js';
        }
        if (\yii::$app->request->url == '/fin/reports') {
            $this->js[] = 'js/fin/reports_pos_ready.js';
        }
        if (mb_substr(\yii::$app->request->url, 0, 5) == '/foto') {
            $this->js[] = 'js/fotos.js';

            $this->js[] = 'js/lightbox.js';
            $this->css[] = 'css/lightbox.css';
        }
        if ((mb_substr(\yii::$app->request->url, 0, 2) == '/?') || (\yii::$app->request->url == '/')) {
            $this->js[] = 'js/lightbox.js';
            $this->css[] = 'css/lightbox.css';
        }
    }
}
