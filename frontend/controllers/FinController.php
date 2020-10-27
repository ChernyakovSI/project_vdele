<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 27.10.2020
 * Time: 18:24
 */

namespace frontend\controllers;

use common\models\fin\Account;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;

class FinController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['accounts'],
                        'controllers' => ['fin'],
                        'allow' => true,
                        'roles' => ['@','ws://'],
                    ],
                ],
            ],
        ];
    }

    public function actionAccounts()
    {
        $cur_user = Yii::$app->user->identity;
        $id_user = $cur_user->getId();

        $accounts = Account::getAllAccountsByUser($id_user);

        return $this->render('index', [
                'id_user' => $id_user,
                'accounts' => $accounts
            ]);

    }
}