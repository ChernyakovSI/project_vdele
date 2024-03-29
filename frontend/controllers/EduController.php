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
                            //++ 1-2-4-002 10/10/2022
                            'card-practice',
                            'card-get-groups',
                            'card-get-cards',
                            //-- 1-2-4-002 10/10/2022
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

    //++ 1-2-4-002 10/10/2022
    public function actionCardPractice()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        if($params->get('n')) {
            $option = [];
            $option['is_active'] = 1;

            $data = Card::getGroupByUserAndNumWithIerarchElements($user_id, $params->get('n'), $option);
            $sphere = Sphere::getSphereById($data['head']['id_sphere']);
        }
        else {
            $head = new Card();
            $data['head'] = $head;
            $data['cards'] = [];
            $sphere = new Sphere();
        }

        $groups = Card::getAllActiveGroup($user_id);
        $spheres = Sphere::getAllSpheresByUser($user_id);

        $path = '/data/img/cards/';

        return $this->render('cardPractice', [
            "data" => $data['head'],
            "groups" => $groups,
            "cards" => $data['cards'],
            "spheres" => $spheres,
            "sphere" => $sphere,
            "path" => $path,
        ]);
    }

    public function actionCardGetGroups()
    {

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();

            $data = $_POST;

            $option = [];
            $option['is_active'] = 1;
            if($data['sphere_id'] > 0){
                $option['id_sphere'] = $data['sphere_id'];
            }

            $groups = Card::getAllGroupsByFilter($user_id, 0, 0, false, $option);

            return [
                "groups" => $groups,
                "error" => "",
            ];
        }

    }

    public function actionCardGetCards()
    {

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();

            $data = $_POST;

            $option = [];
            $option['is_active'] = 1;
            if($data['id'] > 0){
                $option['id'] = $data['id'];
            }

            $cards = Card::getGroupByUserAndNumWithIerarchElements($user_id, 0, $option);

            return [
                "cards" => $cards,
                "error" => "",
            ];
        }

    }
    //-- 1-2-4-002 10/10/2022
}