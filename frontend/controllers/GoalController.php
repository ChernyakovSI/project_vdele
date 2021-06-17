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
use common\models\goal\Note;

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
                                        'spheres-save',
                                        'notes',
                                        'note',
                                        'note-save',
                                        'note-delete'],
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

    public function actionNotes()
    {
        $user_id = Yii::$app->user->identity->getId();

        $AllNotes = Note::getAllNotesByUser($user_id);

        return $this->render('notes', [
            "AllNotes" => $AllNotes,
        ]);

    }

    public function actionNote()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        if($params->get('n')) {
            $data = Note::getNoteByUserAndNum($user_id, $params->get('n'));
            $date = $data['date'];
            $sphere = Sphere::getSphereById($data['id_sphere']);
        }
        else {
            $data = new Note();
            $date = time();
            $sphere = new Sphere();
        }


        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('note', [
            "data" => $data,
            "spheres" => $spheres,
            "date" => $date,
            "sphere" => $sphere
        ]);

    }

    public function actionNoteSave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_note = $_POST['id'];

            if((integer)$id_note == 0) {
                Note::addRecord($_POST, $user_id);
            }
            else {
                Note::editRecord($_POST);
            }

            return [
                "data" => $id_note,
                "error" => "",
            ];
        }

    }

    public function actionNoteDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();
                $user_id = Yii::$app->user->identity->getId();

                foreach ($data['sources'] as $item) {
                    if ($item !== '') {
                        Note::deleteRecord($item, $user_id);
                    }
                }

                $allNotes = Note::getAllNotesByUser($user_id);

                $pathNotes = 'note/';
                $colors = [];
                for($i=1; $i<=8; $i++){
                    $colors[$i] = Sphere::getColorForId($i, 1, 1);
                }

                $dates = [];
                foreach ($allNotes as $note) {
                    $dates[$note['id']] = date("d.m.Y H:i:s", $note['date']);
                }

                return [
                    'error' => '',
                    'allNotes' => $allNotes,
                    'pathNotes' => $pathNotes,
                    'colorStyle' => $colors,
                    'dates' => $dates
                ];
            }

        }

        return [
            'error' => ''
        ];

    }


}