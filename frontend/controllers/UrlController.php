<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 27.10.2020
 * Time: 18:24
 */

namespace frontend\controllers;

use common\models\url\Url as myURL;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;

class UrlController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'all'],
                        'controllers' => ['url'],
                        'allow' => true,
                        'roles' => ['@','ws://'],
                    ],
                ],
            ],
        ];
    }

    public function actionAll()
    {
        $cur_user = Yii::$app->user->identity;
        $id_user = $cur_user->getId();

        $id_category = 0;

        $urls = myURL::getAllURLsByUserAndCategory($id_user, $id_category);

        $maxNumInCategory = myURL::getMaxNumURLByUserAndCategory($id_user, $id_category);

        $categories = myURL::getAllCategoriesByUser($id_user);
        $maxNumCategory = myURL::getMaxNumCategoryByUser($id_user);

        return $this->render('index', [
            'id_user' => $id_user,
            'urls' => $urls,
            'maxNumInCategory' => $maxNumInCategory,
            'curCategory' => $id_category,
            'categories' => $categories,
            'maxNumCategory' => $maxNumCategory,
        ]);

    }

}