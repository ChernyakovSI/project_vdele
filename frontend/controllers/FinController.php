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
                        'actions' => ['accounts',
                                        'accounts-add'],
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

    public function actionAccountsAdd()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['name'] == ''){
                return [
                    "data" => $data,
                    "error" => "Пришли некорректные данные2"
                ];
            }

            $newAcc = Account::add($data);
            // Получаем данные модели из запроса
            if ($newAcc['id'] != 0) {

                $total = Account::formatNumberToMoney($newAcc['amount']);

                $id_user = Yii::$app->user->identity->getId();
                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user));

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $newAcc,
                    "error" => null,
                    "total" => $total,
                    "totalAllAccounts" => $totalAllAccounts
                ];
            } else {
                // Если нет, отправляем ответ с сообщением об ошибке
                return [
                    "data" => $data,
                    "error" => "Пришли некорректные данные"
                ];
            }
        } else {
            // Если это не AJAX запрос, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "Механизм AccountsAdd работает только с AJAX"
            ];
        }

    }
}