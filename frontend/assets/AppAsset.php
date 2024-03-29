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
        //++ 1-2-4-001 31/08/2022
        if ((\yii::$app->request->url == '/edu/card') || (mb_substr(\yii::$app->request->url, 0, 10) == '/edu/card/')) {
            $this->js[] = 'js/edu/card.js';
            $this->js[] = 'js/texts.js';
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/ajax.js';
            $this->js[] = 'js/table.js';
            $this->js[] = 'js/files.js';
        }
        //++ 1-2-4-002 10/10/2022
        if ((\yii::$app->request->url == '/edu/card-practice') || (mb_substr(\yii::$app->request->url, 0, 19) == '/edu/card-practice/')) {
            $this->js[] = 'js/edu/cardPractice.js';
            $this->js[] = 'js/ajax.js';
            $this->js[] = 'js/table.js';
        }
        //-- 1-2-4-002 10/10/2022
        if ((\yii::$app->request->url == '/edu/cards') || (mb_substr(\yii::$app->request->url, 0, 11) == '/edu/cards/')) {
            $this->js[] = 'js/edu/cards.js';
            $this->js[] = 'js/texts.js';
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/table.js';
        }
        //-- 1-2-4-001 31/08/2022
        //++ 1-3-1-004 24/04/2023
        if ((\yii::$app->request->url == '/goal/diaries') || (mb_substr(\yii::$app->request->url, 0, 14) == '/goal/diaries?')) {
            $this->js[] = 'js/goal/diaries.js';
            $this->js[] = 'js/ajax.js';
        }
        //-- 1-3-1-004 24/04/2023
        //++ 1-3-1-003 21/02/2023
        if ((\yii::$app->request->url == '/goal/diary') || (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/diary/')
            || (mb_substr(\yii::$app->request->url, 0, 12) == '/goal/diary?')){
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/goal/diary.js';
            $this->js[] = 'js/texts.js';
            $this->js[] = 'js/table.js';
            $this->js[] = 'js/ajax.js';
        }
        if ((\yii::$app->request->url == '/goal/diary-record') || (mb_substr(\yii::$app->request->url, 0, 19) == '/goal/diary-record/')
            || (mb_substr(\yii::$app->request->url, 0, 19) == '/goal/diary-record?')){
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/goal/diary-record.js';
            $this->js[] = 'js/texts.js';
            $this->js[] = 'js/ajax.js';
        }
        if ((\yii::$app->request->url == '/goal/diary-settings') || (mb_substr(\yii::$app->request->url, 0, 21) == '/goal/diary-settings/')
            || (mb_substr(\yii::$app->request->url, 0, 21) == '/goal/diary-settings?')){
            $this->js[] = 'js/goal/diary-settings.js';
            $this->js[] = 'js/table.js';
            $this->js[] = 'js/ajax.js';
        }
        //-- 1-3-1-003 21/02/2023
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
            //++ 1-2-2-014 27/04/2022
            $this->js[] = 'js/dates.js';
            //-- 1-2-2-014 27/04/2022
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
        //++ 1-2-3-004 26/07/2022
        if ((\yii::$app->request->url == '/goal/task') || (mb_substr(\yii::$app->request->url, 0, 11) == '/goal/task/')) {
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/texts.js';
            $this->js[] = 'js/goal/task-pos-ready.js';
        }
        if ((\yii::$app->request->url == '/goal/tasks-all') || (mb_substr(\yii::$app->request->url, 0, 15) == '/goal/tasks-all')) {
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/goal/tasks-pos-ready.js';
        }
        //-- 1-2-3-004 26/07/2022
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
        //++ 1-2-2-009 15/04/2022
        if ((\yii::$app->request->url == '/fin/categories') || (mb_substr(\yii::$app->request->url, 0, 15) == 'fin/categories?')) {
            $this->js[] = 'js/fin/categories_pos_ready.js';
        }
        //-- 1-2-2-009 15/04/2022
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
        //++ 002 12/03/2022
        if (\yii::$app->request->url == '/login') {
            $this->js[] = 'js/site/logIn.js';
        }
        //++ 1-2-3-003 28/06/2022
        if (\yii::$app->request->url == '/logs') {
            $this->js[] = 'js/site/logs.js';
            $this->js[] = 'js/dates.js';
            $this->js[] = 'js/texts.js';
            $this->js[] = 'js/ajax.js';
        }
        //-- 1-2-3-003 28/06/2022
        //++ 1-2-3-005 27/07/2022
        if (mb_substr(\yii::$app->request->url, 0, 8) == '/public/') {
            $this->js[] = 'js/site/public.js';
            $this->js[] = 'js/texts.js';
        }
        //-- 1-2-3-005 27/07/2022
        //++ 1-2-3-002 11/05/2022
        if (\yii::$app->request->url == '/sender-panel') {
            $this->js[] = 'js/site/senderPanel.js';
            $this->js[] = 'js/ajax.js';
        }
        if (\yii::$app->request->url == '/settings') {
            $this->js[] = 'js/site/settings.js';
            $this->js[] = 'js/ajax.js';
        }
        //-- 1-2-3-002 11/05/2022
        if (\yii::$app->request->url == '/signup') {
            $this->js[] = 'js/site/signUp.js';
        }
        //-- 002 12/03/2022
        if ((mb_substr(\yii::$app->request->url, 0, 2) == '/?') || (\yii::$app->request->url == '/')) {
            $this->js[] = 'js/lightbox.js';
            $this->css[] = 'css/lightbox.css';
        }
    }
}
//-

