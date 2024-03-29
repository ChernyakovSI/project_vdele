<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 27.10.2020
 * Time: 18:24
 */

namespace frontend\controllers;

use common\models\fin\Account;
use common\models\fin\Category;
use common\models\fin\Register;
use common\models\fin\Reports;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
//++ 1-2-3-002 11/05/2022
use common\models\Settings;
//-- 1-2-3-002 11/05/2022

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
                                        'accounts-get-all',
                                        'categories',
                                        'cat-add',
                                        'cat-get',
                                        'cat-edit',
                                        'cat-delete',
                                        'sub-add',
                                        'sub-edit',
                                        'sub-delete',
                                        'register',
                                        'reg-add',
                                        'reg-get',
                                        'reg-edit',
                                        'reg-delete',
                                        'reports',],
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
                    "element" => "Name"
                ];
            }

            $changedId = Account::changeOtherNumByUser($data['num'], $id_user, 0);
            $maxNum = Account::getMaxNumByUser($id_user);

            $deletedDefore = ($data['is_deleted'] == true);

            $newAcc = Account::add($data);

            $deletedAfter = ($newAcc['is_deleted'] == 1);
            // Получаем данные модели из запроса
            if ($newAcc['id'] != 0) {

                $total = Account::formatNumberToMoney($newAcc['sum']);

                $id_user = Yii::$app->user->identity->getId();
                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user));

                if($changedId > 0 || $maxNum > $data['num'] || $deletedDefore != $deletedAfter) {
                    if ($changedId == 0) {
                        $changedId = 1;
                    };

                    if ($data['option-deleted'] == 'true') {
                        $newAcc = Account::getAllAccountsByUserWithDeleted($id_user);
                    } else{
                        $newAcc = Account::getAllAccountsByUser($id_user);
                    };

                    foreach ($newAcc as $item) {
                        if ($item['id'] != 0) {
                            $item['sum'] = Account::formatNumberToMoney($item['sum']);
                        }
                    }
                };

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
        };

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
                    "element" => "Name"
                ];
            }

            $changedId = Account::changeOtherNumByUser($data['num'], $id_user, $data['id']);
            $maxNum = Account::getMaxNumByUser($id_user);

            $deletedBefore = ($data['is_deleted'] == 1);

            $Acc = Account::edit($data['id'], $data);

            $deletedAfter = ($Acc['is_deleted'] == 1);
            // Получаем данные модели из запроса
            if ($Acc['id'] != 0) {

                $total = Account::formatNumberToMoney($Acc['sum']);

                $id_user = Yii::$app->user->identity->getId();
                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user));

                if($changedId > 0 || $maxNum > $data['num'] || $deletedBefore != $deletedAfter){
                    if($changedId == 0){
                        $changedId = 1;
                    }

                    if ($data['option-deleted'] == 'true') {
                        $Acc = Account::getAllAccountsByUserWithDeleted($id_user);
                    } else{
                        $Acc = Account::getAllAccountsByUser($id_user);
                    };

                    foreach ($Acc as $item) {
                        if ($item['id'] != 0) {
                            $item['sum'] = Account::formatNumberToMoney($item['sum']);
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
                $Acc['sum'] = Account::formatNumberToMoneyOnlyCents($Acc['sum']);
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

            if(isset($data['calc']) && $data['calc'] == '1'){
                $calc = true;
            }
            else{
                $calc = false;
            }

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if ($withDeleted == 'false') {
                $Acc = Account::getAllAccountsByUser($id_user, $calc);

                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUser($id_user));

                foreach ($Acc as $item) {
                    if ($item['id'] != 0) {
                        $item['sum'] = Account::formatNumberToMoney($item['sum']);
                    }
                }
            } else {
                $Acc = Account::getAllAccountsByUserWithDeleted($id_user, $calc);

                $totalAllAccounts = Account::formatNumberToMoney(Account::getTotalAmountAccountsByUserWithDeleted($id_user));

                foreach ($Acc as $item) {
                    if ($item['id'] != 0) {
                        $item['sum'] = Account::formatNumberToMoney($item['sum']);
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


    //--CATEGORIES--------------------------------------------------------------------------

    public function actionCategories()
    {
        $cur_user = Yii::$app->user->identity;
        $id_user = $cur_user->getId();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                $isProfit = 1;
            }
            else{
                $isProfit = 0;
            }

            $id_category = $data['id_category'];

            if($id_category > 0) {
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category);
            }
            else{
                $subs = [];
            }

            $cats = Category::getAllCategoriesByUser($id_user, $isProfit);

            //Если всё успешно, отправляем ответ с данными
            return [
                "data" => $subs,
                "error" => null,
                'categories' => $cats,
                'isProfit' => $isProfit,
                'id_category' => $id_category,
            ];
        } else {

            $data = Yii::$app->request->post();
            if(isset($data['isProfit'])){
                $isProfit = $data['isProfit'];
            }
            else{
                $isProfit = 0;
            }

            $cats = Category::getAllCategoriesByUser($id_user);

            return $this->render('categories', [
                'id_user' => $id_user,
                'isProfit' => $isProfit,
                'id_category' => 0,
                'categories' => $cats,
            ]);
        }

    }

    public function actionCatAdd()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['name'] == ''){
                return [
                    "data" => $data,
                    "error" => "Не заполнено название",
                    "element" => "Name"
                ];
            }

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if(Category::existsNameByUser($data['name'], $id_user, 0, 0) == true){
                return [
                    "data" => $data,
                    "error" => "Категория с таким же названием уже существует",
                    "element" => "Name"
                ];
            }

            $newCat = Category::add($data);

            // Получаем данные модели из запроса
            if ($newCat['id'] != 0) {

                if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }

                $cats = Category::getAllCategoriesByUser($id_user, $isProfit);

                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $cats,
                    "error" => null,
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
                "error" => "Механизм UtlAdd работает только с AJAX"
            ];
        };

    }

    public function actionCatEdit()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['name'] == ''){
                return [
                    "data" => $data,
                    "error" => "Не заполнено название"
                ];
            }

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if(Category::existsNameByUser($data['name'], $id_user, $data['id'], 0) == true){
                return [
                    "data" => $data,
                    "error" => "Категория с таким же названием уже существует",
                    "element" => "Name"
                ];
            }

            $Cat = Category::edit($data['id'], $data);

            // Получаем данные модели из запроса
            if ($Cat['id'] != 0) {

                if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }

                $cats = Category::getAllCategoriesByUser($id_user, $isProfit);

                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $cats,
                    "error" => null,
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
                "error" => "Механизм UtlAdd работает только с AJAX"
            ];
        };

    }

    public function actionCatGet()
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
                    "error" => "Для получения данных категории необходим id"
                ];
            }

            $Cat = Category::findOne($id);
            // Получаем данные модели из запроса
            if ($Cat['id'] != 0) {

                $cur_user = Yii::$app->user->identity;
                $id_user = $cur_user->getId();

                $subs = Category::getAllSubsByUserAndCategory($id_user, $Cat['id']);

                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $Cat,
                    "subs" => $subs,
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
                "error" => "Механизм UrlGet работает только с AJAX"
            ];
        }

    }

    public function actionCatDelete()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $url = Category::del($data['id']);

            // Получаем данные модели из запроса
            if ($url['id'] != 0) {
                $subs = [];

                if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }

                $cats = Category::getAllCategoriesByUser($id_user, $isProfit);

                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $subs,
                    "error" => null,
                    'categories' => $cats,
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

    public function actionSubAdd()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['name'] == ''){
                return [
                    "data" => $data,
                    "error" => "Не заполнено название"
                ];
            }

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if(Category::existsNameByUser($data['name'], $id_user, 0, $data['id_category']) == true){
                return [
                    "data" => $data,
                    "error" => "Категория с таким же названием уже существует",
                    "element" => "Name"
                ];
            }

            $newCat = Category::add($data);

            // Получаем данные модели из запроса
            if ($newCat['id'] != 0) {

                /*if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }*/

                $id_category = $data['id_category'];
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category);

                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $subs,
                    "error" => null,
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
                "error" => "Механизм UtlAdd работает только с AJAX"
            ];
        };

    }

    public function actionSubEdit()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['name'] == ''){
                return [
                    "data" => $data,
                    "error" => "Не заполнено название"
                ];
            }

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if(Category::existsNameByUser($data['name'], $id_user, $data['id'], $data['id_category']) == true){
                return [
                    "data" => $data,
                    "error" => "Категория с таким же названием уже существует",
                    "element" => "Name"
                ];
            }

            $id_category = $data['id_category'];

            $sub = Category::edit($data['id'], $data);

            // Получаем данные модели из запроса
            if ($sub['id'] != 0) {
                /*if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }*/

                $id_category = $data['id_category'];
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category);

                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $subs,
                    "error" => null,
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

    public function actionSubDelete()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $sub = Category::del($data['id']);

            // Получаем данные модели из запроса
            if ($sub['id'] != 0) {
                if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }

                $id_category = $data['id_category'];
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category);

                $categories = Category::getAllCategoriesByUser($id_user, $isProfit);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $subs,
                    "error" => null,
                    "categories" => $categories
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


    //--REGISTER--------------------------------------------------------------------------

    public function actionRegister()
    {
        $cur_user = Yii::$app->user->identity;
        $id_user = $cur_user->getId();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            $types = [];
            if($data['isExpense'] == 1){
                $types[] = 0;
            }
            if($data['isProfit'] == 1){
                $types[] = 1;
            }
            if($data['isReplacement'] == 1){
                $types[] = 2;
            }

            if(isset($data['selPeriodFrom'])){
                $PeriodFrom = $data['selPeriodFrom'];
            }
            else{
                $PeriodFrom = 0;
            }
            if(isset($data['selPeriodTo'])){
                $PeriodTo = $data['selPeriodTo'];
            }
            else{
                $PeriodTo = 0;
            }

            $option = [];

            if($data['selAccId'] == 0){
                if($data['selAccName'] == ''){
                    $data['selAccId'] = 0;
                }
                else
                {
                    $Elem = Account::getElemByName($data['selAccName'], $id_user);
                    if(isset($Elem)){
                        $data['selAccId'] = $Elem['id'];
                    }
                    else{
                        $data['selAccId'] = 0;
                    }
                }
            }
            $option['selAccId'] = $data['selAccId'];

            if(($data['isExpense'] == 1) || ($data['isProfit'] == 1)){
                if($data['selCatId'] == 0){
                    if($data['selCatName'] == ''){
                        $data['selCatId'] = 0;
                    }
                    else
                    {
                        $Elem = Category::getElemByName($data['selCatName'], $id_user);
                        if(isset($Elem)){
                            $data['selCatId'] = $Elem['id'];
                        }
                        else{
                            $data['selCatId'] = 0;
                        }
                    }
                }
                $option['selCatId'] = $data['selCatId'];

                if($data['selSubId'] == 0){
                    if($data['selSubName'] == ''){
                        $data['selSubId'] = 0;
                    }
                    else
                    {
                        $Elem = Category::getElemByName($data['selSubName'], $id_user);
                        if(isset($Elem)){
                            $data['selSubId'] = $Elem['id'];
                        }
                        else{
                            $data['selSubId'] = 0;
                        }
                    }
                }
                $option['selSubId'] = $data['selSubId'];
            }else{
                $option['selCatId'] = 0;
                $option['selSubId'] = 0;
            }

            $transactions = Register::getAllRegsByUser($id_user, $PeriodFrom, $PeriodTo, $types, $option);

            $total = 0;

            $SumFormat = [];

            if(count($transactions)){
                foreach ($transactions as $item) {
                    if ($item['id'] != 0) {
                        $total = $total + $item['sum'];
                        $SumFormat[$item['id']] = Account::formatNumberToMoney($item['sum']);
                    }
                }
            }

            $total = Account::formatNumberToMoney($total);

            $cats = Category::getAllCategoriesByUser($id_user, $types);

            $subs = Category::getAllSubsByUserAndCategory($id_user, $option['selCatId']);

            return [
                'data' => $transactions,
                'total' => $total,
                'SumFormat' => $SumFormat,
                'cats' => $cats,
                'subs' => $subs,
            ];

        }
        else{

            $isExpense = 1;
            $isProfit = 0;
            $isReplacement = 0;
            $types[] = 0;

            $periodFrom = strtotime(date('Y-m-01 00:00:00'));
            $periodTo = strtotime(date('Y-m-t 23:59:59'));

            $transactions = Register::getAllRegsByUser($id_user, $periodFrom, $periodTo, $types);

            $total = 0;

            foreach ($transactions as $item) {
                if ($item['id'] != 0) {
                    $total = $total + $item['sum'];
                }
            }

            $Accs = Account::getAllAccountsByUser($id_user);


            foreach ($Accs as $item) {
                if ($item['id'] != 0) {
                    $item['amount'] = Account::formatNumberToMoney($item['amount']);
                }
            }

            if($isExpense == 1 && $isProfit == 1){
                $cats = Category::getAllCategoriesByUser($id_user, [0, 1]);
            }else{
                $cats = Category::getAllCategoriesByUser($id_user, $isProfit);
            }

            /*if(count($cats) > 0){
                $id_category = $cats[0]['id'];
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category, $isProfit);
            }
            else
            {
                $subs = [];
            }*/

            $subs = [];

            //++ 1-2-3-002 11/05/2022
            $Settings = Settings::getUsersSettings($id_user);
            //-- 1-2-3-002 11/05/2022

            return $this->render('register', [
                'id_user' => $id_user,
                'transactions' => $transactions,
                'isExpense' => $isExpense,
                'isProfit' => $isProfit,
                'isReplacement' => $isReplacement,
                'total' => $total,
                'accs' => $Accs,
                'cats' => $cats,
                'subs' => $subs,
                'periodFrom' => $periodFrom,
                'periodTo' => $periodTo
                //++ 1-2-3-002 11/05/2022
                , 'Settings' => $Settings,
                //-- 1-2-3-002 11/05/2022
            ]);
        }

    }

    public function actionRegAdd()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if($data['AccId'] == 0){
                if($data['AccName'] == ''){
                    return [
                        "data" => $data,
                        "error" => "Не заполнен счет",
                        "element" => "Acc"
                    ];
                }
                else
                {
                    if(Account::existsNameByUser($data['AccName'], $id_user, 0) == true){
                        return [
                            "data" => $data,
                            "error" => "Счет с таким же наименованием уже существует",
                            "element" => "Acc"
                        ];
                    }
                }

                $newAccData = [
                    'name' => $data['AccName'],
                    'amount' => 0,
                    'comment' => '',
                ];
                $newAcc = Account::add($newAccData);
                $data['AccId'] = $newAcc['id'];
            }

            if($data['type'] != 2) {
                if ($data['CatId'] == 0) {
                    if ($data['CatName'] == '') {
                        return [
                            "data" => $data,
                            "error" => "Не заполнена категория",
                            "element" => "Cat"
                        ];
                    } else {
                        if (Category::existsNameByUser($data['CatName'], $id_user, 0, 0) == true) {
                            return [
                                "data" => $data,
                                "error" => "Категория с таким же названием уже существует",
                                "element" => "Cat"
                            ];
                        }
                    }

                    $newCatData = [
                        'name' => $data['CatName'],
                        'isProfit' => $data['type'],
                    ];
                    $newCat = Category::add($newCatData);
                    $data['CatId'] = $newCat['id'];
                }

                if ($data['SubId'] == 0) {
                    if ($data['SubName'] == '') {
                        return [
                            "data" => $data,
                            "error" => "Не заполнена подкатегория",
                            "element" => "Sub"
                        ];
                    } else {
                        if (Category::existsNameByUser($data['SubName'], $id_user, 0, $data['CatId']) == true) {
                            return [
                                "data" => $data,
                                "error" => "Подкатегория с таким же названием уже существует",
                                "element" => "Sub"
                            ];
                        }
                    }

                    $newSubData = [
                        'name' => $data['SubName'],
                        'id_category' => $data['CatId'],
                        'isProfit' => $data['type'],
                    ];
                    $newSub = Category::add($newSubData);
                    $data['SubId'] = $newSub['id'];
                }
            }
            else{
                if($data['AccToId'] == 0){
                    if($data['AccToName'] == ''){
                        return [
                            "data" => $data,
                            "error" => "Не указано, на какой счет перенести",
                            "element" => "AccTo"
                        ];
                    }
                    else
                    {
                        if(Account::existsNameByUser($data['AccToName'], $id_user, 0) == true){
                            return [
                                "data" => $data,
                                "error" => "Счет с таким же наименованием уже существует",
                                "element" => "AccTo"
                            ];
                        }
                    }

                    $newAccData = [
                        'name' => $data['AccToName'],
                        'amount' => 0,
                        'comment' => '',
                    ];
                    $newAcc = Account::add($newAccData);
                    $data['AccToId'] = $newAcc['id'];
                }
                if($data['AccToId'] == $data['AccId']) {
                    return [
                        "data" => $data,
                        "error" => "Необходимо выбрать разные счета для перемещения",
                        "element" => "AccTo"
                    ];
                }
            }

            if($data['Amount'] == 0) {
                return [
                    "data" => $data,
                    "error" => "Необходимо указать сумму",
                    "element" => "Amo"
                ];
            }

            if($data['type'] != 2) {
                $dataSet = [
                    'id_user' => $id_user,
                    'id_category' => $data['CatId'],
                    'id_subcategory' => $data['SubId'],
                    'date' => $data['date'],
                    'sum' => $data['Amount'],
                    'comment' => $data['Com'],
                    'id_type' => $data['type'],
                    'id_account' => $data['AccId'],
                ];
            }
            else{
                $dataSet = [
                    'id_user' => $id_user,
                    'date' => $data['date'],
                    'sum' => $data['Amount'],
                    'comment' => $data['Com'],
                    'id_type' => $data['type'],
                    'id_account' => $data['AccId'],
                    'id_account_to' => $data['AccToId'],
                ];
            };

            $newReg = Register::add($dataSet);

            // Получаем данные модели из запроса
            if ($newReg['id'] != 0) {

                $types = [];
                if($data['isExpense'] == 1){
                    $types[] = 0;
                }
                if($data['isProfit'] == 1){
                    $types[] = 1;
                }
                if($data['isReplacement'] == 1){
                    $types[] = 2;
                }

                if(isset($data['selPeriodFrom'])){
                    $PeriodFrom = $data['selPeriodFrom'];
                }
                else{
                    $PeriodFrom = 0;
                }
                if(isset($data['selPeriodTo'])){
                    $PeriodTo = $data['selPeriodTo'];
                }
                else{
                    $PeriodTo = 0;
                }

                $option = [];

                if($data['selAccId'] == 0){
                    if($data['selAccName'] == ''){
                        $data['selAccId'] = 0;
                    }
                    else
                    {
                        $Elem = Account::getElemByName($data['selAccName'], $id_user);
                        if(isset($Elem)){
                            $data['selAccId'] = $Elem['id'];
                        }
                        else{
                            $data['selAccId'] = 0;
                        }
                    }
                }

                $option['selAccId'] = $data['selAccId'];

//                $periodFrom = strtotime(date('Y-m-01 00:00:00'));
//                $periodTo = strtotime(date('Y-m-t 23:59:59'));

//                return [
//                    'data' => $PeriodFrom,
//                    'total' => $total,
//                    'SumFormat' => $SumFormat,
//                ];


                $transactions = Register::getAllRegsByUser($id_user, $PeriodFrom, $PeriodTo, $types, $option);

                $total = 0;

                $SumFormat = [];

                foreach ($transactions as $item) {
                    if ($item['id'] != 0) {
                        $total = $total + $item['sum'];
                        $SumFormat[$item['id']] = Account::formatNumberToMoney($item['sum']);
                    }
                }

                $total = Account::formatNumberToMoney($total);

                //Если всё успешно, отправляем ответ с данными
                return [
                    'data' => $transactions,
                    'total' => $total,
                    'SumFormat' => $SumFormat,
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
                "error" => "Механизм UtlAdd работает только с AJAX"
            ];
        };

    }

    public function actionRegGet()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if($data['id'] == 0) {
                return [
                    "data" => $data,
                    "error" => "Не передан идентификатор записи"
                ];
            };

            $Reg = Register::getRegById($data['id']);

            $dataSet = [
                'id' => $Reg['id'],
                'date' => $Reg['date'],
                'type' =>  (integer)$Reg['id_type'],
                'AccId' => $Reg['id_account'],
                'AccName' => $Reg['AccName'],
                'CatId' => $Reg['id_category'],
                'CatName' => $Reg['CatName'],
                'SubId' => $Reg['id_subcategory'],
                'SubName' => $Reg['SubName'],
                'Amount' => (float)$Reg['sum'],
                'Com' => $Reg['comment'],
                'AccToId' => $Reg['id_account_to'],
                'AccToName' => $Reg['AccToName'],
            ];

            if($dataSet['type'] != 2) {
                $dataSet['CatId'] = $Reg['id_category'];
                $dataSet['SubId'] = $Reg['id_subcategory'];

                $cats = Category::getAllCategoriesByUser($id_user, $dataSet['type']);

                if($dataSet['CatId'] > 0) {
                    $subs = Category::getAllSubsByUserAndCategory($id_user, $dataSet['CatId']);
                }
                else{
                    $subs = [];
                }
            }
            else{
                $dataSet['AccToId'] = $Reg['id_account_to'];

                $cats = [];
                $subs = [];
            };

            $accounts = Account::getAllAccountsByUser($id_user);




                //Если всё успешно, отправляем ответ с данными
                return [
                    'data' => $dataSet,
                    'accounts' => $accounts,
                    'categories' => $cats,
                    'subs' => $subs,
                ];

        } else {
            // Если это не AJAX запрос, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "Механизм RegGet работает только с AJAX"
            ];
        };

    }

    public function actionRegEdit()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            if($data['AccId'] == 0){
                if($data['AccName'] == ''){
                    return [
                        "data" => $data,
                        "error" => "Не заполнен счет",
                        "element" => "Acc"
                    ];
                }
                else
                {
                    if(Account::existsNameByUser($data['AccName'], $id_user, $data['AccId']) == true){
                        return [
                            "data" => $data,
                            "error" => "Счет с таким же наименованием уже существует",
                            "element" => "Acc"
                        ];
                    }
                }

                $newAccData = [
                    'name' => $data['AccName'],
                    'amount' => 0,
                    'comment' => '',
                ];
                $newAcc = Account::add($newAccData);
                $data['AccId'] = $newAcc['id'];
            }

            if($data['type'] != 2) {
                if ($data['CatId'] == 0) {
                    if ($data['CatName'] == '') {
                        return [
                            "data" => $data,
                            "error" => "Не заполнена категория",
                            "element" => "Cat"
                        ];
                    } else {
                        if (Category::existsNameByUser($data['CatName'], $id_user, $data['CatId'], 0) == true) {
                            return [
                                "data" => $data,
                                "error" => "Категория с таким же названием уже существует",
                                "element" => "Cat"
                            ];
                        }
                    }

                    $newCatData = [
                        'name' => $data['CatName'],
                        'isProfit' => $data['type'],
                    ];
                    $newCat = Category::add($newCatData);
                    $data['CatId'] = $newCat['id'];
                }

                if ($data['SubId'] == 0) {
                    if ($data['SubName'] == '') {
                        return [
                            "data" => $data,
                            "error" => "Не заполнена подкатегория",
                            "element" => "Sub"
                        ];
                    } else {
                        if (Category::existsNameByUser($data['SubName'], $id_user, $data['SubId'], $data['CatId']) == true) {
                            return [
                                "data" => $data,
                                "error" => "Подкатегория с таким же названием уже существует",
                                "element" => "Sub"
                            ];
                        }
                    }

                    $newSubData = [
                        'name' => $data['SubName'],
                        'id_category' => $data['CatId'],
                        'isProfit' => $data['type'],
                    ];
                    $newSub = Category::add($newSubData);
                    $data['SubId'] = $newSub['id'];
                }
            }
            else{
                if($data['AccToId'] == 0){
                    if($data['AccToName'] == ''){
                        return [
                            "data" => $data,
                            "error" => "Не указано, на какой счет перенести",
                            "element" => "AccTo"
                        ];
                    }
                    else
                    {
                        if(Account::existsNameByUser($data['AccToName'], $id_user, $data['AccToId']) == true){
                            return [
                                "data" => $data,
                                "error" => "Счет с таким же наименованием уже существует",
                                "element" => "AccTo"
                            ];
                        }
                    }

                    $newAccData = [
                        'name' => $data['AccToName'],
                        'amount' => 0,
                        'comment' => '',
                    ];
                    $newAcc = Account::add($newAccData);
                    $data['AccToId'] = $newAcc['id'];
                }
                if($data['AccToId'] == $data['AccId']) {
                    return [
                        "data" => $data,
                        "error" => "Необходимо выбрать разные счета для перемещения",
                        "element" => "AccTo"
                    ];
                }
            }

            if($data['Amount'] == 0) {
                return [
                    "data" => $data,
                    "error" => "Необходимо указать сумму",
                    "element" => "Amo"
                ];
            }

            if($data['type'] != 2) {
                $dataSet = [
                    'id_user' => $id_user,
                    'id_category' => $data['CatId'],
                    'id_subcategory' => $data['SubId'],
                    'date' => $data['date'],
                    'sum' => $data['Amount'],
                    'comment' => $data['Com'],
                    'id_type' => $data['type'],
                    'id_account' => $data['AccId'],
                ];
            }
            else{
                $dataSet = [
                    'id_user' => $id_user,
                    'date' => $data['date'],
                    'sum' => $data['Amount'],
                    'comment' => $data['Com'],
                    'id_type' => $data['type'],
                    'id_account' => $data['AccId'],
                    'id_account_to' => $data['AccToId'],
                ];
            };

            $Reg = Register::edit($dataSet, $data['id']);

            // Получаем данные модели из запроса
            if ($Reg['id'] != 0) {

                $types = [];
                if($data['isExpense'] == 1){
                    $types[] = 0;
                }
                if($data['isProfit'] == 1){
                    $types[] = 1;
                }
                if($data['isReplacement'] == 1){
                    $types[] = 2;
                }

                if(isset($data['selPeriodFrom'])){
                    $PeriodFrom = $data['selPeriodFrom'];
                }
                else{
                    $PeriodFrom = 0;
                }
                if(isset($data['selPeriodTo'])){
                    $PeriodTo = $data['selPeriodTo'];
                }
                else{
                    $PeriodTo = 0;
                }

                $option = [];

                if($data['selAccId'] == 0){
                    if($data['selAccName'] == ''){
                        $data['selAccId'] = 0;
                    }
                    else
                    {
                        $Elem = Account::getElemByName($data['selAccName'], $id_user);
                        if(isset($Elem)){
                            $data['selAccId'] = $Elem['id'];
                        }
                        else{
                            $data['selAccId'] = 0;
                        }
                    }
                }

                $option['selAccId'] = $data['selAccId'];

                $transactions = Register::getAllRegsByUser($id_user, $PeriodFrom, $PeriodTo, $types, $option);

                $total = 0;

                $SumFormat = [];

                foreach ($transactions as $item) {
                    if ($item['id'] != 0) {
                        $total = $total + $item['sum'];
                        $SumFormat[$item['id']] = Account::formatNumberToMoney($item['sum']);
                    }
                }

                $total = Account::formatNumberToMoney($total);

                //Если всё успешно, отправляем ответ с данными
                return [
                    'data' => $transactions,
                    'total' => $total,
                    'SumFormat' => $SumFormat,
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
                "error" => "Механизм UtlAdd работает только с AJAX"
            ];
        };

    }

    public function actionRegDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $Reg = Register::del($data['id']);

            // Получаем данные модели из запроса
            if ($Reg['id'] != 0) {

                $types = [];
                if($data['isExpense'] == 1){
                    $types[] = 0;
                }
                if($data['isProfit'] == 1){
                    $types[] = 1;
                }
                if($data['isReplacement'] == 1){
                    $types[] = 2;
                }

                if(isset($data['selPeriodFrom'])){
                    $PeriodFrom = $data['selPeriodFrom'];
                }
                else{
                    $PeriodFrom = 0;
                }
                if(isset($data['selPeriodTo'])){
                    $PeriodTo = $data['selPeriodTo'];
                }
                else{
                    $PeriodTo = 0;
                }

                $option = [];

                if($data['selAccId'] == 0){
                    if($data['selAccName'] == ''){
                        $data['selAccId'] = 0;
                    }
                    else
                    {
                        $Elem = Account::getElemByName($data['selAccName'], $id_user);
                        if(isset($Elem)){
                            $data['selAccId'] = $Elem['id'];
                        }
                        else{
                            $data['selAccId'] = 0;
                        }
                    }
                }

                $option['selAccId'] = $data['selAccId'];

                $transactions = Register::getAllRegsByUser($id_user, $PeriodFrom, $PeriodTo, $types, $option);

                $total = 0;

                $SumFormat = [];

                foreach ($transactions as $item) {
                    if ($item['id'] != 0) {
                        $total = $total + $item['sum'];
                        $SumFormat[$item['id']] = Account::formatNumberToMoney($item['sum']);
                    }
                }

                $total = Account::formatNumberToMoney($total);

                //Если всё успешно, отправляем ответ с данными
                return [
                    'data' => $transactions,
                    'total' => $total,
                    'SumFormat' => $SumFormat,
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
                "error" => "Механизм UtlAdd работает только с AJAX"
            ];
        };

    }

    //--REGISTER--------------------------------------------------------------------------

    public function actionReports()
    {
        $cur_user = Yii::$app->user->identity;
        $id_user = $cur_user->getId();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            if(isset($data['selPeriodFrom'])){
                $PeriodFrom = $data['selPeriodFrom'];
            }
            else{
                $PeriodFrom = 0;
            }
            if(isset($data['selPeriodTo'])){
                $PeriodTo = $data['selPeriodTo'];
            }
            else{
                $PeriodTo = 0;
            }

            if(isset($data['is_sub'])){
                $is_sub = $data['is_sub'] === 'true';
            }
            else{
                $is_sub = 0;
            }

            $optionE = [];
            if(isset($data['sortCatE'])){
                $sortCat = (integer)$data['sortCatE'];
            }
            else{
                $sortCat = 0;
            }
            $optionE['sortCat'] = $sortCat;
            if(isset($data['sortSubE'])){
                $sortSub = (integer)$data['sortSubE'];
            }
            else{
                $sortSub = 0;
            }
            $optionE['sortSub'] = $sortSub;
            if(isset($data['sortAmoE'])){
                $sortAmo = (integer)$data['sortAmoE'];
            }
            else{
                $sortAmo = 0;
            }
            $optionE['sortAmo'] = $sortAmo;

            $optionP = [];
            if(isset($data['sortCatP'])){
                $sortCat = (integer)$data['sortCatP'];
            }
            else{
                $sortCat = 0;
            }
            $optionP['sortCat'] = $sortCat;
            if(isset($data['sortSubP'])){
                $sortSub = (integer)$data['sortSubP'];
            }
            else{
                $sortSub = 0;
            }
            $optionP['sortSub'] = $sortSub;
            if(isset($data['sortAmoP'])){
                $sortAmo = (integer)$data['sortAmoP'];
            }
            else{
                $sortAmo = 0;
            }
            $optionP['sortAmo'] = $sortAmo;

            if($is_sub === false) {
                $resultsProf = Reports::getTotalByProfitCatsByUser($id_user, $PeriodFrom, $PeriodTo, $optionP);
                $resultsExp = Reports::getTotalByExpenceCatsByUser($id_user, $PeriodFrom, $PeriodTo, $optionE);
            }
            else {
                $resultsProf = Reports::getTotalByProfitSubsByUser($id_user, $PeriodFrom, $PeriodTo, $optionP);
                $resultsExp = Reports::getTotalByExpenceSubsByUser($id_user, $PeriodFrom, $PeriodTo, $optionE);
            }

            $totalProf = 0;
            $totalExp = 0;

            $SumFormatProf = [];
            $SumFormatExp = [];

            foreach ($resultsExp as $item) {
                if ($item['id_category'] != 0) {
                    $totalExp = $totalExp + $item['sum'];
                    if($is_sub === false) {
                        $SumFormatExp[$item['id_category']] = Account::formatNumberToMoney($item['sum']);
                    }else{
                        $SumFormatExp[$item['id_subcategory']] = Account::formatNumberToMoney($item['sum']);
                    }
                }
            }
            foreach ($resultsProf as $item) {
                if ($item['id_category'] != 0) {
                    $totalProf = $totalProf + $item['sum'];
                    if($is_sub === false) {
                        $SumFormatProf[$item['id_category']] = Account::formatNumberToMoney($item['sum']);
                    }else{
                        $SumFormatProf[$item['id_subcategory']] = Account::formatNumberToMoney($item['sum']);
                    }
                }
            }

            $totalDelta = $totalProf - $totalExp;

            $totalProf = Account::formatNumberToMoney($totalProf);
            $totalExp = Account::formatNumberToMoney($totalExp);
            $totalDelta = Account::formatNumberToMoney($totalDelta);

            return [
                'periodFrom' => $PeriodFrom,
                'periodTo' => $PeriodTo,
                'dataExp' => $resultsExp,
                'dataProf' => $resultsProf,
                'totalExp' => $totalExp,
                'totalProf' => $totalProf,
                'totalDelta' => $totalDelta,
                'SumFormatExp' => $SumFormatExp,
                'SumFormatProf' => $SumFormatProf,
            ];

        }
        else{

            $periodFrom = strtotime(date('Y-m-01 00:00:00'));
            $periodTo = strtotime(date('Y-m-t 23:59:59'));

            $resultsProf = Reports::getTotalByProfitCatsByUser($id_user, $periodFrom, $periodTo);
            $resultsExp = Reports::getTotalByExpenceCatsByUser($id_user, $periodFrom, $periodTo);

//            echo '<pre>';
//            var_dump($results);
//            exit();

            $totalProf = 0;
            $totalExp = 0;

            foreach ($resultsExp as $item) {
                if ($item['id_category'] != 0) {
                    $totalExp = $totalExp + $item['sum'];
                }
            }
            foreach ($resultsProf as $item) {
                if ($item['id_category'] != 0) {
                    $totalProf = $totalProf + $item['sum'];
                }
            }

            $totalDelta = $totalProf - $totalExp;

            //За день

            $periodFromDay = strtotime(date('Y-m-d 00:00:00'));
            $periodToDay = strtotime(date('Y-m-d 23:59:59'));

            $resultsProfDay = Reports::getTotalByProfitCatsByUser($id_user, $periodFromDay, $periodToDay);
            $resultsExpDay = Reports::getTotalByExpenceCatsByUser($id_user, $periodFromDay, $periodToDay);

            $totalProfDay = 0;
            $totalExpDay = 0;

            foreach ($resultsExpDay as $item) {
                if ($item['id_category'] != 0) {
                    $totalExpDay = $totalExpDay + $item['sum'];
                }
            }
            foreach ($resultsProfDay as $item) {
                if ($item['id_category'] != 0) {
                    $totalProfDay = $totalProfDay + $item['sum'];
                }
            }

            $totalDeltaDay = $totalProfDay - $totalExpDay;

            //За неделю
            $dayOfWeek = date('w')-1;
            if($dayOfWeek == -1){
                $dayOfWeek = 6;
            }
            $week_start = date('Y-m-d 00:00:00', strtotime('-'.$dayOfWeek.' days'));
            $week_end = date('Y-m-d 23:59:59', strtotime('+'.(6-$dayOfWeek).' days'));

            $periodFromWeek = strtotime($week_start);
            $periodToDayWeek = strtotime($week_end);

            $resultsProfWeek = Reports::getTotalByProfitCatsByUser($id_user, $periodFromWeek, $periodToDayWeek);
            $resultsExpWeek = Reports::getTotalByExpenceCatsByUser($id_user, $periodFromWeek, $periodToDayWeek);

            $totalProfWeek = 0;
            $totalExpWeek = 0;

            foreach ($resultsExpWeek as $item) {
                if ($item['id_category'] != 0) {
                    $totalExpWeek = $totalExpWeek + $item['sum'];
                }
            }
            foreach ($resultsProfWeek as $item) {
                if ($item['id_category'] != 0) {
                    $totalProfWeek = $totalProfWeek + $item['sum'];
                }
            }

            $totalDeltaWeek = $totalProfWeek - $totalExpWeek;

            //За месяц

            $periodFromMonth = strtotime(date('Y-m-01 00:00:00'));
            $periodToMonth = strtotime(date('Y-m-t 23:59:59'));

            $resultsProfMonth = Reports::getTotalByProfitCatsByUser($id_user, $periodFromMonth, $periodToMonth);
            $resultsExpMonth = Reports::getTotalByExpenceCatsByUser($id_user, $periodFromMonth, $periodToMonth);

            $totalProfMonth = 0;
            $totalExpMonth = 0;

            foreach ($resultsExpMonth as $item) {
                if ($item['id_category'] != 0) {
                    $totalExpMonth = $totalExpMonth + $item['sum'];
                }
            }
            foreach ($resultsProfMonth as $item) {
                if ($item['id_category'] != 0) {
                    $totalProfMonth = $totalProfMonth + $item['sum'];
                }
            }

            $totalDeltaMonth = $totalProfMonth - $totalExpMonth;

            //За год

            $periodFromYear = strtotime(date('Y-01-01 00:00:00'));
            $periodToYear = strtotime(date('Y-12-31 23:59:59'));

            $resultsProfYear = Reports::getTotalByProfitCatsByUser($id_user, $periodFromYear, $periodToYear);
            $resultsExpYear = Reports::getTotalByExpenceCatsByUser($id_user, $periodFromYear, $periodToYear);

            $totalProfYear = 0;
            $totalExpYear = 0;

            foreach ($resultsExpYear as $item) {
                if ($item['id_category'] != 0) {
                    $totalExpYear = $totalExpYear + $item['sum'];
                }
            }
            foreach ($resultsProfYear as $item) {
                if ($item['id_category'] != 0) {
                    $totalProfYear = $totalProfYear + $item['sum'];
                }
            }

            $totalDeltaYear = $totalProfYear - $totalExpYear;

            return $this->render('reports', [
                'id_user' => $id_user,
                'resultsProf' => $resultsProf,
                'resultsExp' => $resultsExp,
                'totalProf' => $totalProf,
                'totalExp' => $totalExp,
                'periodFrom' => $periodFrom,
                'periodTo' => $periodTo,
                'typeReport' => 0,
                'totalDelta' => $totalDelta,
                'totalDeltaDay' => $totalDeltaDay,
                'totalDeltaWeek' => $totalDeltaWeek,
                'totalDeltaMonth' => $totalDeltaMonth,
                'totalDeltaYear' => $totalDeltaYear
            ]);
        }

    }

}