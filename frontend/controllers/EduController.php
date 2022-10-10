<?php


namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use common\models\edu\Card;
use common\models\goal\Sphere;
use common\models\Image;

class EduController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'cards',
                            'card',
                            'card-save',
                            'foto-card-load',
                            ],
                        'controllers' => ['edu'],
                        'allow' => true,
                        'roles' => ['@','ws://'],
                    ],
                ],
            ],
        ];
    }

    public function actionCards()
    {
        $user_id = Yii::$app->user->identity->getId();

        $startDate =  strtotime("-6 month");
        $finishDate =  strtotime("+1 month");
        $AllGroups = Card::getAllGroupsByFilter($user_id, $startDate, $finishDate, false);

        $AllSpheres = $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('cards', [
            "AllGroups" => $AllGroups,
            "AllSpheres" => $AllSpheres,
        ]);
    }

    public function actionCard()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        if($params->get('n')) {
            $data = Card::getCardByUserAndNum($user_id, $params->get('n'));
            $date = $data['head']['date'];
            $sphere = Sphere::getSphereById($data['head']['id_sphere']);
        }
        else {
            $head = new Card();
            $data['head'] = $head;
            $data['head']['is_active'] = 1;
            $data['cards'] = [];
            $date = time();
            $sphere = new Sphere();
        }

        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('card', [
            "data" => $data['head'],
            "cards" => $data['cards'],
            "spheres" => $spheres,
            "date" => $date,
            "sphere" => $sphere
        ]);
    }

    public function actionCardSave()
    {

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_card = $_POST['id'];

            $cards = json_decode($_POST['cards']);

            if((integer)$id_card == 0) {
                Card::addRecord($_POST, $cards, $user_id);
            }
            else {
                Card::editRecord($_POST, $cards);
            }

            return [
                "data" => $id_card,
                "error" => "",
            ];
        }

    }

    public function actionFotoCardLoad()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $file = $_FILES['file'];

                $image = new Image();
                $path = $image->upload($file, 2);

                //$cur_user = Yii::$app->user->identity;
                //$id_user = $cur_user->getId();
                //$allPaths = Image::getAllImagePathsForUserAndAlbum($id_user, 2);

                $pathFotos = Yii::$app->params['dataUrl'].'img/cards/';

                return [
                    'newPath' => $path,
                    'error' => '',
                    //'allPaths' => $allPaths,
                    'pathFotos' => $pathFotos
                ];
            }

        }

        return [
            'newPaths' => [],
            'error' => 'error'
        ];
    }
}