<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 27.10.2020
 * Time: 18:24
 */

namespace frontend\controllers;

use common\models\url\Url as myURL;
use common\models\User;
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
                        'actions' => ['all',
                                    'url-add',
                                    'url-get',
                                    'url-edit',
                                    'url-delete',
                                    'cat-add',
                                    'cat-edit',
                                    'cat-delete',
                            ],
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

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $id_category = $data['id_category'];

            $urls = myURL::getAllURLsByUserAndCategory($id_user, $id_category);
            $categories = myURL::getAllCategoriesByUser($id_user);

            $maxNumInCategory = myURL::getMaxNumURLByUserAndCategory($id_user, $id_category);

            foreach ($urls as $item) {
                if ($item['id'] != 0) {
                    $item['url'] = User::wrapURL($item['url']);
                }
            };

                //Если всё успешно, отправляем ответ с данными
            return [
                    "data" => $urls,
                    "error" => null,
                    "maxNumInCategory" => $maxNumInCategory,
                    'categories' => $categories,
            ];
        } else {

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $id_category = 0;

            $urls = myURL::getAllURLsByUserAndCategory($id_user, $id_category);

            $maxNumInCategory = myURL::getMaxNumURLByUserAndCategory($id_user, $id_category);

            $categories = myURL::getAllCategoriesByUser($id_user);
            $maxNumCategory = myURL::getMaxNumCatByUser($id_user);

            return $this->render('index', [
                'id_user' => $id_user,
                'urls' => $urls,
                'maxNumInCategory' => $maxNumInCategory,
                'id_category' => $id_category,
                'categories' => $categories,
                'maxNumCategory' => $maxNumCategory,
            ]);

        };



    }

    public function actionUrlAdd()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['url'] == ''){
                return [
                    "data" => $data,
                    "error" => "Не заполнено поле с веб-ссылкой"
                ];
            }

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $id_category = $data['id_category'];

            $changedId = myURL::changeOtherNumByUserAndCategory($data['num'], $id_user, $id_category,0);

            $newUrl = myURL::add($data);

            // Получаем данные модели из запроса
            if ($newUrl['id'] != 0) {

                $urls = myURL::getAllURLsByUserAndCategory($id_user, $id_category);
                $maxNumInCategory = myURL::getMaxNumURLByUserAndCategory($id_user, $id_category);

                foreach ($urls as $item) {
                    if ($item['id'] != 0) {
                        $item['url'] = User::wrapURL($item['url']);
                    }
                };

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $urls,
                    "error" => null,
                    "maxNumInCategory" => $maxNumInCategory,
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

    public function actionUrlEdit()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['url'] == ''){
                return [
                    "data" => $data,
                    "error" => "Не заполнено поле с веб-ссылкой"
                ];
            }

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $id_category = $data['id_category'];

            $changedId = myURL::changeOtherNumByUserAndCategory($data['num'], $id_user, $id_category, $data['id']);

            $url = myURL::edit($data['id'], $data);

            // Получаем данные модели из запроса
            if ($url['id'] != 0) {
                $urls = myURL::getAllURLsByUserAndCategory($id_user, $id_category);
                $maxNumInCategory = myURL::getMaxNumURLByUserAndCategory($id_user, $id_category);

                foreach ($urls as $item) {
                    if ($item['id'] != 0) {
                        $item['url'] = User::wrapURL($item['url']);
                    }
                };

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $urls,
                    "error" => null,
                    "maxNumInCategory" => $maxNumInCategory,
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

    public function actionUrlDelete()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            $id_category = $data['id_category'];

            $url = myURL::del($data['id']);

            // Получаем данные модели из запроса
            if ($url['id'] != 0) {
                $urls = myURL::getAllURLsByUserAndCategory($id_user, $id_category);
                $maxNumInCategory = myURL::getMaxNumURLByUserAndCategory($id_user, $id_category);

                foreach ($urls as $item) {
                    if ($item['id'] != 0) {
                        $item['url'] = User::wrapURL($item['url']);
                    }
                };

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $urls,
                    "error" => null,
                    "maxNumInCategory" => $maxNumInCategory,
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

    public function actionUrlGet()
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
                    "error" => "Для получения данных url необходим id"
                ];
            }

            $url = myURL::findOne($id);
            // Получаем данные модели из запроса
            if ($url['id'] != 0) {
                $url['url'] = User::wrapURL($url['url']);
                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $url,
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

    public function actionCatAdd()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Устанавливаем формат ответа JSON

        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $cur_user = Yii::$app->user->identity;
            $id_user = $cur_user->getId();

            //$data['id_category'] = -1;
            $changedId = myURL::changeOtherNumByUser($data['num'], $id_user,0);

            $newUrl = myURL::add($data);

            // Получаем данные модели из запроса
            if ($newUrl['id'] != 0) {

                $cats = myURL::getAllCategoriesByUser($id_user);
                $maxNumCat = myURL::getMaxNumCatByUser($id_user);


                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $cats,
                    "error" => null,
                    "maxNumCat" => $maxNumCat,
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

            //$data['id_category'] = -1;
            $changedId = myURL::changeOtherNumByUser($data['num'], $id_user,0);

            $curUrl = myURL::edit($data['id'], $data);

            // Получаем данные модели из запроса
            if ($curUrl['id'] != 0) {

                $cats = myURL::getAllCategoriesByUser($id_user);
                $maxNumCat = myURL::getMaxNumCatByUser($id_user);


                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $cats,
                    "error" => null,
                    "maxNumCat" => $maxNumCat,
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

            $url = myURL::del($data['id']);

            // Получаем данные модели из запроса
            if ($url['id'] != 0) {
                $urls = myURL::getAllURLsByUserAndCategory($id_user, $id_category);
                $categories = myURL::getAllCategoriesByUser($id_user);

                $maxNumInCategory = myURL::getMaxNumURLByUserAndCategory($id_user, $id_category);

                foreach ($urls as $item) {
                    if ($item['id'] != 0) {
                        $item['url'] = User::wrapURL($item['url']);
                    }
                };

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $urls,
                    "error" => null,
                    "maxNumInCategory" => $maxNumInCategory,
                    'categories' => $categories,
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