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
        'css/index49.css',
        'css/color35.css',
        'css/acEdit12.css',
        'css/users11.css',
        'css/url4.css',
        'css/fin11.css',
        'css/useful5.css',
        //'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css',
        'css/floatingCircles3.css',
        'css/contextMenu.css',
        'css/foto3.css',
        'css/slider.css',
        'css/size22.css',
        'css/backgrounds.css'
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
        if ((\yii::$app->request->url == '/goal/dream') || (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/dream/')
            || (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/dream?')){
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/goal/amb_dream_pos_ready.js';
            $this->js[] = 'js/texts.js';
        }
        if (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/dreams') {
            $this->js[] = 'js/goal/amb-dreams-pos-ready.js';
            $this->js[] = 'js/dates.js';
        }
        if (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/goals') {
            $this->js[] = 'js/goal/amb-dreams-pos-ready.js';
            $this->js[] = 'js/dates.js';
        }
        if (mb_substr(\yii::$app->request->url, 0, 14) == '/goal/intents') {
            $this->js[] = 'js/goal/amb-dreams-pos-ready.js';
            $this->js[] = 'js/dates.js';
        }
        if ((\yii::$app->request->url == '/goal/note') || (mb_substr(\yii::$app->request->url, 0, 11) == '/goal/note/')) {
            $this->js[] = 'js/goal/note-pos-ready.js';
            $this->js[] = 'js/texts.js';
        }
        if ((\yii::$app->request->url == '/goal/priority') || (mb_substr(\yii::$app->request->url, 0, 14) == '/goal/priority')) {
            $this->js[] = 'js/ajax.js';
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/errors.js';
            $this->js[] = 'js/goal/priority-pos-ready.js';
        }
        if ((\yii::$app->request->url == '/goal/results') || (mb_substr(\yii::$app->request->url, 0, 13) == '/goal/results')) {
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/goal/results-pos-ready.js';
        }
        if ((\yii::$app->request->url == '/goal/task') || (mb_substr(\yii::$app->request->url, 0, 11) == '/goal/task/')) {
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/texts.js';
            $this->js[] = 'js/goal/task-pos-ready.js';
        }
        if ((\yii::$app->request->url == '/goal/tasks-all') || (mb_substr(\yii::$app->request->url, 0, 15) == '/goal/tasks-all')) {
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/goal/tasks-pos-ready.js';
        }
        if (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/wishes') {
            $this->js[] = 'js/goal/amb-dreams-pos-ready.js';
            $this->js[] = 'js/dates.js';
        }
        if ((\yii::$app->request->url == '/goal/calendar') || (mb_substr(\yii::$app->request->url, 0, 15) == '/goal/calendar?')) {
            $this->js[] = 'js/goal/calendar-pos-ready.js';
        }
        if ((\yii::$app->request->url == '/goal/day') || (mb_substr(\yii::$app->request->url, 0, 10) == '/goal/day/')) {
            $this->js[] = 'js/goal/day-pos-ready.js';
        }
        if ((\yii::$app->request->url == '/goal/notes') || (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/notes?')) {
            $this->js[] = 'js/goal/notes-pos-ready.js';
        }
        if ((\yii::$app->request->url == '/goal/spheres') || (mb_substr(\yii::$app->request->url, 0, 14) == '/goal/spheres?')) {
            $this->js[] = 'js/goal/spheres-pos-ready.js';
        }
        if (\yii::$app->request->url == '/site/ac-edit') {
            $this->js[] = 'js/apiGeo.js';
            $this->js[] = 'js/profile/ac-edit_pos_ready.js';
        }
        if ((\yii::$app->request->url == '/users') || (mb_substr(\yii::$app->request->url, 0, 7) == '/users?')) {
            $this->js[] = 'js/profile/users_pos_ready.js';
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
//-

