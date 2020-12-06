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
                                        'accounts-get-all',
                                        'categories',
                                        'cat-add',
                                        'cat-get',
                                        'cat-edit',
                                        'cat-delete',
                                        'sub-add',
                                        'sub-edit',
                                        'sub-delete',
                                        'register',],
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
                            $item['amount'] = Account::formatNumberToMoney($item['amount']);
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

                    if ($data['option-deleted'] == 'true') {
                        $Acc = Account::getAllAccountsByUserWithDeleted($id_user);
                    } else{
                        $Acc = Account::getAllAccountsByUser($id_user);
                    };

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
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category, $isProfit);
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

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

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

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

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

                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $Cat,
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

            $id_category = 0;

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

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $newCat = Category::add($data);

            // Получаем данные модели из запроса
            if ($newCat['id'] != 0) {

                if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }

                $id_category = $data['id_category'];
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category, $isProfit);

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

            $id_category = $data['id_category'];

            $sub = Category::edit($data['id'], $data);

            // Получаем данные модели из запроса
            if ($sub['id'] != 0) {
                if(isset($data['isProfit']) && $data['isProfit'] == '1'){
                    $isProfit = 1;
                }
                else{
                    $isProfit = 0;
                }

                $id_category = $data['id_category'];
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category, $isProfit);

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
                $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category, $isProfit);

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

    public function actionRegister()
    {
        $cur_user = Yii::$app->user->identity;
        $id_user = $cur_user->getId();

        $isExpense = 1;
        $isProfit = 0;
        $isReplacement = 0;

        $transactions = [];

        $total = 0;

        $Accs = Account::getAllAccountsByUser($id_user);
        foreach ($Accs as $item) {
            if ($item['id'] != 0) {
                $item['amount'] = Account::formatNumberToMoney($item['amount']);
            }
        }

        $cats = Category::getAllCategoriesByUser($id_user, $isProfit);

        if(count($cats) > 0){
            $id_category = $cats[0]['id'];
            $subs = Category::getAllSubsByUserAndCategory($id_user, $id_category, $isProfit);
        }
        else
        {
            $subs = [];
        }


        return $this->render('register', [
            'id_user' => $id_user,
            'transactions' => $transactions,
            'isExpense' => $isExpense,
            'isProfit' => $isProfit,
            'isReplacement' => $isReplacement,
            'total' => $total,
            'accs' => $Accs,
            'cats' => $cats,
            'subs' => $subs
        ]);

    }

}