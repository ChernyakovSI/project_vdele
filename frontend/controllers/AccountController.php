<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 09.05.2019
 * Time: 20:14
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\AcEdit;
use yii\filters\AccessControl;

class AccountController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    /*[
                        'actions' => ['index', 'request-password-reset', 'reset-password'],
                        'controllers' => ['site'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['login', 'signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],*/
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {

        var_dump('Test for Account edit');
        die();
        /*if (isset(Yii::$app->user->identity)) {
            return $this->render('acEdit', [
                'user_id' => Yii::$app->user->identity->getId(),
            ]);
        }
        else {
            return $this->render('index');
        }*/

    }
}