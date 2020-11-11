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
                                        'accounts-add',
                                        'accounts-get',
                                        'accounts-edit',
                                        'accounts-get-all',],
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

        $maxNum = Account::getMaxNumByUser($id_user);

        return $this->render('index', [
                'id_user' => $id_user,
                'accounts' => $accounts,
                'maxNum' => $maxNum
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

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();
            if(Account::existsNameByUser($data['name'], $id_user, 0) == true){
                return [
                    "data" => $data,
                    "error" => "Счет с таким же наименованием уже существует",
                    "element" => "name"
                ];
            }

            $changedId = Account::changeOtherNumByUser($data['num'], $id_user, 0);
            $maxNum = Account::getMaxNumByUser($id_user);

            $deletedDefore = ($data['is_deleted'] == true);

            $newAcc = Account::add($data);

            $deletedAfter = ($newAcc['is_deleted'] == 1);
            // Получаем данные модели из запроса
            if ($newAcc['id'] != 0) {

                $total = Account::formatNumberToMoney($newAcc['amount']);

                $id_user = Yii::$app->user->identity->getId();
                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user));

                if($changedId > 0 || $maxNum > $data['num'] || $deletedDefore != $deletedAfter){
                    if($changedId == 0){
                        $changedId = 1;
                    }
                    $newAcc = Account::getAllAccountsByUser($id_user);

                    foreach ($newAcc as $item) {
                        if ($item['id'] != 0) {
                            $item['amount'] = Account::formatNumberToMoney($item['amount']);
                        }
                    }
                }

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $newAcc,
                    "error" => null,
                    "total" => $total,
                    "totalAllAccounts" => $totalAllAccounts,
                    "changedNumId" => $changedId
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

    public function actionAccountsEdit()
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

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();
            if(Account::existsNameByUser($data['name'], $id_user, $data['id']) == true){
                return [
                    "data" => $data,
                    "error" => "Счет с таким же наименованием уже существует",
                    "element" => "name"
                ];
            }

            $changedId = Account::changeOtherNumByUser($data['num'], $id_user, $data['id']);
            $maxNum = Account::getMaxNumByUser($id_user);

            $deletedDefore = ($data['is_deleted'] == true);

            $Acc = Account::edit($data['id'], $data);

            $deletedAfter = ($Acc['is_deleted'] == 1);
            // Получаем данные модели из запроса
            if ($Acc['id'] != 0) {

                $total = Account::formatNumberToMoney($Acc['amount']);

                $id_user = Yii::$app->user->identity->getId();
                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user));

                if($changedId > 0 || $maxNum > $data['num'] || $deletedDefore != $deletedAfter){
                    if($changedId == 0){
                        $changedId = 1;
                    }
                    $Acc = Account::getAllAccountsByUser($id_user);

                    foreach ($Acc as $item) {
                        if ($item['id'] != 0) {
                            $item['amount'] = Account::formatNumberToMoney($item['amount']);
                        }
                    }


                }

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $Acc,
                    "error" => null,
                    "total" => $total,
                    "totalAllAccounts" => $totalAllAccounts,
                    "changedNumId" => $changedId
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

    public function actionAccountsGet()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id = $data['id'];

            if($id == 0){
                return [
                    "data" => $id,
                    "error" => "Пришли некорректные данные2"
                ];
            }

            $Acc = Account::findOne($id);
            // Получаем данные модели из запроса
            if ($Acc['id'] != 0) {
                $Acc['amount'] = Account::formatNumberToMoneyOnlyCents($Acc['amount']);
                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $Acc,
                    "error" => null,
                ];
            } else {
                // Если нет, отправляем ответ с сообщением об ошибке
                return [
                    "data" => $id,
                    "error" => "Пришли некорректные данные"
                ];
            }
        } else {
            // Если это не AJAX запрос, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "Механизм AccountsGet работает только с AJAX"
            ];
        }

    }

    public function actionAccountsGetAll()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $withDeleted = $data['is_deleted'];

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if ($withDeleted == 'false') {
                $Acc = Account::getAllAccountsByUser($id_user);

                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user));

                foreach ($Acc as $item) {
                    if ($item['id'] != 0) {
                        $item['amount'] = Account::formatNumberToMoney($item['amount']);
                    }
                }
            } else {
                $Acc = Account::getAllAccountsByUserWithDeleted($id_user);

                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUserWithDeleted($id_user));

                foreach ($Acc as $item) {
                    if ($item['id'] != 0) {
                        $item['amount'] = Account::formatNumberToMoney($item['amount']);
                    }
                }
            }

            return [
                "data" => $Acc,
                "error" => null,
                "totalAllAccounts" => $totalAllAccounts
            ];
        } else {
            // Если это не AJAX запрос, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "Механизм AccountsGetAll работает только с AJAX"
            ];
        }

    }


}