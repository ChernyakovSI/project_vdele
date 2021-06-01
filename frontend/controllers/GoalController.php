<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 25.05.2021
 * Time: 9:32
 */

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\Cors;
use common\models\goal\Sphere;
use common\models\goal\SphereUser;

class GoalController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'controllers' => ['goal'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['spheres',
                                        'spheres-get',
                                        'spheres-save'],
                        'controllers' => ['goal'],
                        'allow' => true,
                        'roles' => ['@','ws://'],
                    ],
                ],
            ],
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
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

    public function actionSpheres()
    {
        $user_id = Yii::$app->user->identity->getId();

        $AllSpheres = Sphere::getSpheresByUser((integer)$user_id);

        return $this->render('spheres', [
            "AllSpheres" => $AllSpheres,
        ]);

    }

    public function actionSpheresGet()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();

            $rec = Sphere::getSphereByUserAndSphere($user_id, $_POST['id']);

            $color = Sphere::getColorForId((integer)$_POST['id'], 1);

            $AllSpheres = Sphere::getSpheresByUser((integer)$user_id);

            return [
                "data" => $rec,
                "color" => $color,
                "error" => "",
                "AllSpheres" => $AllSpheres,
            ];
        }

    }

    public function actionSpheresSave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_sphere = $_POST['id'];
            $name = $_POST['name'];

            SphereUser::editRecord($user_id, (integer)$id_sphere, $name);

            $rec = Sphere::getSphereByUserAndSphere($user_id, (integer)$id_sphere);

            $color = Sphere::getColorForId((integer)$id_sphere, 1);

            $AllSpheres = Sphere::getSpheresByUser((integer)$user_id);


            return [
                "data" => $rec,
                "color" => $color,
                "error" => "",
                "AllSpheres" => $AllSpheres,
            ];
        }

    }
}