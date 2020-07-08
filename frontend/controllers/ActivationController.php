<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 08.07.2020
 * Time: 20:20
 */

namespace frontend\controllers;

use common\models\User;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class ActivationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'controllers' => ['activation'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        if (Yii::$app->request->isGet) {

            //"http://yavdele.net/activation.php?token=d4bfff7884c4cd734bd03b72df2d45df&amp;email=paladin_cool@inbox.ru"
            $email = Yii::$app->request->get('email');
            $token = Yii::$app->request->get('token');
            if(user::activate($token, $email) == true){
                $user = User::findByEmail($email);
                $user->email_status = 1;
                $user->save();

                $result = 'Спасибо! Ваш аккаунт подтвержден';
            }
            else
            {
                $result = 'К сожалению, не удалось подтвердить аккаунт';
            }

            return $this->render('result', [
                'result' => $result,
            ]);
        }

        return $this->goHome();
    }

}