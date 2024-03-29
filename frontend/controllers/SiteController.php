<?php
namespace frontend\controllers;

use common\models\Dialog;
use common\models\DialogUsers;
use common\models\MailUser;
use common\models\Message;
use common\models\Tag;
use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\debug\models\search\Mail;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use common\models\City;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\web\UploadedFile;
use common\models\Image;
use yii\filters\Cors;
//++ 1-2-2-005 30/03/2022
use common\models\Mailer;
//-- 1-2-2-005 30/03/2022
//++ 1-2-3-002 11/05/2022
use common\models\Settings;
use common\models\MailMessage;
//-- 1-2-3-002 11/05/2022
//++ 1-2-3-003 28/06/2022
use common\models\Log;
//-- 1-2-3-003 28/06/2022
//++ 1-2-3-005 27/07/2022
use common\models\goal\Note;
//-- 1-2-3-005 27/07/2022

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],*/
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    /*[
                        'actions' => ['index', 'requestPasswordReset', 'ResetPassword'],
                        'allow' => true,
                        'roles' => ['*'],
                    ],*/
                    [
                        'actions' => ['index',
                                    'request-password-reset',
                                    'reset-password',
                                    'login',
                                    'signup',
                                    'mail',
                                    //++ 1-2-2-004 18/03/2022
                                    'error-user',
                                    'show-error',
                                    //-- 1-2-2-004 18/03/2022
                                    //++ 1-2-3-005 27/07/2022
                                    'public',
                                    //-- 1-2-3-005 27/07/2022
                                    ],
                        'controllers' => ['site'],
                        'allow' => true,
                    ],

                    /*[
                        'actions' => ['login', 'signup'],
                        'controllers' => ['site'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],*/
                    [
                        'actions' => ['logout', 'ac-edit',
                                    'ac-edit-save',
                                    'ac-add-city',
                                    'send-confirm-letter',
                                    'users',
                                    'dialog',
                                    'dialog-send',
                                    'dialog-delete',
                                    'dialog-get-messages',
                                    'foto',
                                    'foto-load',
                                    'foto-delete',
                                    'avatar-load',
                                    'avatar-delete',
                                    'intro',
                                    //++ 001 07/03/2022
                                    'mail',
                                    //-- 001 07/03/2022
                                    //++ 1-2-2-004 18/03/2022
                                    'error-user',
                                    'show-error',
                                    //-- 1-2-2-004 18/03/2022
                                    //++ 1-2-3-002 11/05/2022
                                    'settings',
                                    'settings-save',
                                    'sender-panel',
                                    'sender-panel-post',
                                    //-- 1-2-3-002 11/05/2022
                                    //++ 1-2-3-003 28/06/2022
                                    'logs',
                                    //-- 1-2-3-003 28/06/2022
                                    ],
                        'controllers' => ['site'],
                        'allow' => true,
                        'roles' => ['@','ws://'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'ac-add-city' => ['post'],
                ],
            ],
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
            //'authenticator' => [
            //    'authMethods' => HttpBearerAuth::className()
            //],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /*$current_user_id = Yii::$app->user->identity->getId();

        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $current_user_id, 1);*/

        if (isset(Yii::$app->user->identity)) {
            $months = [
                'января',
                'февраля',
                'марта',
                'апреля',
                'мая',
                'июня',
                'июля',
                'августа',
                'сентября',
                'октября',
                'ноября',
                'декабря'
            ];

            $cur_user = Yii::$app->user->identity;
            if (!empty($_GET['id'])) {
                $id_user = $_GET['id'];
            }
            else{
                $id_user = $cur_user->getId();
            }

            $user = User::findIdentity($id_user);

            $city = City::findById($user->id_city);

            $image = new Image();
            $path_avatar = $image->getPathAvatarForUser($id_user);

            if (!(user::activated($cur_user->getEmail()))) {
            Yii::$app->session->setFlash('error', 'Аккаунт не подтвержден! 
                Поэтому полный функционал сайта пока недоступен. 
                Перейдите по ссылке подтверждения, высланной на почту, по которой зарегистрировались.
                Если письмо не пришло, то запросите повторное письмо по кнопке из меню сверху, либо напишите в поддержку');
            }

            $userTags = Tag::getTagsByUser($id_user);

            return $this->render('ac', [
                'user_id' => $id_user,
                'cur_user_id' => $cur_user->getId(),
                'user' => $user,
                'months' => $months,
                'city' => $city,
                'path_avatar' => $path_avatar,
                'userTags' => $userTags,
            ]);
        }
        else {
            return $this->render('index');
        }
        /*return $this->render('index' , [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]
        );*/
    }

    public function actionIntro()
    {

        return $this->render('index');

    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте вашу электронную почту, на которую было выслано письмо с дальнейшими инструкциями');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, не удалось сбросить пароль для указанного адреса электронной почты.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Назначен новый пароль.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionAcEdit()
    {
        if (isset(Yii::$app->user->identity)) {

            $user_id = Yii::$app->user->identity->getId();
            $cur_user = User::findIdentity($user_id);

            $image = new Image();
            $path_avatar = $cur_user->getAvatarName($user_id);//$image->getPathAvatarForUser($user_id);

            $allPaths = Image::getAllImagePathsForUserAndAlbum($user_id, 0);


            if (Yii::$app->request->isPost) {
                if ($cur_user->load(Yii::$app->request->post()) && $cur_user->validate()) {
                    $cur_user->date_of_birth = strtotime(Yii::$app->request->post()['User']['date_of_birth']);//->getTimestamp();
                    $cur_user->id_city = (integer)Yii::$app->request->post()['User']['id_city'];

                    if ($path_avatar != Yii::$app->request->post()['User']['pathImageFile']) {

                        $cur_user->imageFile = UploadedFile::getInstance($cur_user, 'imageFile');
                        //var_dump($cur_user->image);


                        if ($cur_user->upload()) {
                            Yii::$app->session->setFlash('success', 'Фото профиля обновлено');
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Не удалось обновить фото профиля');
                        }
                        //    Yii::$app->request->post()->image->saveAs('data/img/avatar/' . Yii::$app->request->post()->image->baseName . '.' . Yii::$app->request->post()->image->extension);
                    };

                    $cur_user->imageFile = '';

                    if ($cur_user->save()) {
                        Yii::$app->session->setFlash('success', 'Изменения сохранены');
                        $path_avatar = $image->getPathAvatarForUser($user_id);
                        $cur_user->pathImageFile = $path_avatar;
                    } else {
                        //Yii::$app->session->setFlash('success', 'Не удалось записать изменения');
                    }

                } else {
                    Yii::$app->session->setFlash('success', 'Не удалось записать изменения 2');
                }
            }
            else
            {
                $cur_user->pathImageFile = $path_avatar;
            }

            $city = City::findById($cur_user->id_city);

            $dateOfBirth = $cur_user->date_of_birth; //strtotime(date('Y-m-d 00:00:00', $cur_user->date_of_birth));

            $userTags = Tag::getTagsByUser($user_id);
            $tags = Tag::getAllTags();

            return $this->render('ac-edit', [
                'cur_user' => $cur_user,
                'city' => $city,
                'path_avatar' => $path_avatar,
                'tab' => 1,
                'dateOfBirth' => $dateOfBirth,
                'allPaths' => $allPaths,
                'tags' => $tags,
                'userTags' => $userTags
            ]);
        }
        else {
            return $this->render('index');
        }
    }

    public function actionAcEditSave(){
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $error = '';
            $result = false;

            $data = Yii::$app->request->post();

            $user_id = Yii::$app->user->identity->getId();
            $cur_user = User::findIdentity($user_id);

//            $result = User::edit($data, $user_id);
//            return [
//                'result' => $result,
//                'error' => $error,
//            ];

            if (User::edit($data, $user_id)) {
                $result = true;
                //Yii::$app->session->setFlash('success', 'Изменения сохранены');
                //actionIndex();

            } else {
                $error = 'Не удалось записать изменения';
                //Yii::$app->session->setFlash('error', $error);
            }

            return [
                'result' => $result,
                'error' => $error,
            ];

        }
    }

    public function actionAcAddCity()
    {
        if (!empty($_POST['id_city']) && ($_POST['id_city'] !== '') &&
            !empty($_POST['name_city']) && ($_POST['name_city'] !== '')) {
            return City::findOrCreate($_POST['id_city'], $_POST['name_city']);
        }
        else {
            return 0;
        }
    }

    public function actionSendConfirmLetter() {
        $user_id = Yii::$app->user->identity->getId();
        $cur_user = User::findIdentity($user_id);

        $cur_user->sendConfirmLetter();

        return $this->actionIndex();
    }

    public function actionUsers() {

        //++ 1-3-1-003 21/02/2023
        //*-
        //$query = User::find();
        //*+
        $query = User::find()->where(['email_status' => 1]);
        //-- 1-3-1-003 21/02/2023
        $gFind = false;
        $findTag = '';
        $findFIO = '';
        $findAgeFrom = '';
        $findAgeTo = '';
        $findGender = '';
        $findCity = '';

        $params = Yii::$app->request;
        if(($params->get('tag')) || ($params->get('fio'))
            || ($params->get('af')) || ($params->get('at'))
            || ($params->get('gen')) || ($params->get('city'))) {
            $query = User::findWithParams($params);
            $gFind = true;
            $findTag = $params->get('tag');
            if (!isset($findTag)) {
                $findTag = '';
            }

            $findFIO = $params->get('fio');
            $findFIO = str_replace ( "," , " " , $findFIO);
            if (!isset($findFIO)) {
                $findFIO = '';
            }

            $findAgeFrom = $params->get('af');
            if (!isset($findAgeFrom)) {
                $findAgeFrom = '';
            }

            $findAgeTo = $params->get('at');
            if (!isset($findAgeTo)) {
                $findAgeTo = '';
            }

            $findGenderId = $params->get('gen');
            if ($findGenderId === '1') {
                $findGender = 'Мужской';
            }
            else if ($findGenderId === '2') {
                $findGender = 'Женский';
            }

            $findCity = $params->get('city');
            if (!isset($findCity)) {
                $findCity = '';
            }
        }


        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        //++ 1-2-2-007 05/04/2022
        //*-
        /*$usersAll = $query->orderBy(['last_activity' => SORT_DESC])->all();

        $users = $query->orderBy(['last_activity' => SORT_DESC])
        */
        //*+
        $usersAll = $query->orderBy(['email_status' => SORT_DESC, 'last_activity' => SORT_DESC])->all();

        $users = $query->orderBy(['email_status' => SORT_DESC, 'last_activity' => SORT_DESC])
        //-- 1-2-2-007 05/04/2022
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $tags = Tag::getAllTags();
        $cities = City::getAllCities();

        return $this->render('users', [
            'users' => $users,
            'pagination' => $pagination,
            'usersAll' => $usersAll,
            'tags' => $tags,
            'gFind' => $gFind,
            'findTag' => $findTag,
            'findFIO' => $findFIO,
            'findAgeFrom' => $findAgeFrom,
            'findAgeTo' => $findAgeTo,
            'findGender' => $findGender,
            'cities' => $cities,
            'findCity' => $findCity
        ]);
    }

    public function actionDialog() {
        //DialogUsers::renewSendedLettersAboutUnreadMessages();

        if (Yii::$app->request->isGet) {
            $user_id = Yii::$app->user->identity->getId();
            $user_id2 = Yii::$app->request->get('id');

            if($user_id == $user_id2){
                return $this->goHome();
            }

            $dialog_id = Dialog::getDialogByUser($user_id, $user_id2, 1);
            $dialog_name = Dialog::getNameById($dialog_id);
            if ($dialog_name == ''){
                $dialog_name = User::getI($user_id2);
            }

            $messagesQuery  = Message::findMessages($dialog_id);

            $message = new Message([
                'id_user' => $user_id,
                'id_dialog' => $dialog_id,
                'text' => ''
            ]);

            $option = [
                'limit' => 30,
                'name' => User::getI($user_id).':'
            ];

            $usersWithDialogs = Dialog::getUsersWithOpenedDialogs($user_id);

            //var_dump($usersWithDialogs);
            //exit();

            return $this->render('dialog',
                compact('dialog_id', 'dialog_name', 'messagesQuery', 'message', 'option', 'usersWithDialogs', 'user_id2')
            );

            /*if ($message->load(Yii::$app->request->post()) && $message->validate()) {
                $message->save();
                $message = new Message([
                    //'id_user' => $user_id,
                    'text' => ''
                ]);
                if (Yii::$app->request->isPjax) {
                    return $this->renderAjax('dialog/_chat',
                        compact('dialog_id', 'dialog_name', 'messagesQuery', 'message'));
                }
            }*/


            /*if (Yii::$app->request->isPjax) {
                return $this->renderAjax('dialog/_list',
                    compact('dialog_id', 'dialog_name', 'messagesQuery', 'message'));
            }*/

            /////////
            ///
            /*$entryData = array(
                'text' => 'Всем привет',
                'id_dialog' => $dialog_id,
                'id_user' => $user_id,
                'created_at' => time()
            );

            $newMessage = new Message();
            $newMessage->addMessage($entryData);

            // This is our new stuff
            $context = new ZMQContext();
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'new message');
            $socket->connect("tcp://".Yii::$app->params['domanName'].":5555");

            $socket->send(json_encode($entryData));
               */

        }
        /*elseif (Yii::$app->request->isAjax) {
            $entryData = array(
                'text' => $_POST['text'],
                'id_dialog' => $_POST['id_dialog'],
                'id_user' => $_POST['id_user'],
                'created_at' => time()
            );

            $newMessage = new Message();
            $newMessage->addMessage($entryData);

            // This is our new stuff
            $context = new ZMQContext();
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'new message');
            $socket->connect("tcp://".Yii::$app->params['domanName'].":5555");

            $socket->send(json_encode($entryData));
        }
        */
        else
        {
            return $this->goHome();
        }
    }

    public function actionDialogSend()
    {
        // Создаём экземпляр модели.
        $newMessage = new Message();
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if($data['text'] == ''){
                return [
                    "data" => $data,
                    "error" => "Пришли некорректные данные"
                ];
            }

            // Получаем данные модели из запроса
            if ($newMessage->addMessage($data) > 0) {

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $newMessage,
                    "error" => null
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
                "error" => "Механизм dialog_send работает только с AJAX"
            ];
        }
    }

    public function actionDialogDelete()
    {
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();



            if($data['id'] == ''){
                return [
                    "data" => $data,
                    "error" => "Пришли некорректные данные2"
                ];
            }

            $Message = Message::findOne($data['id']);
            // Получаем данные модели из запроса
            if ($Message->deleteMessage() > 0) {

                //$newMessage->addMessage($data);
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $data,
                    "error" => null
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
                "error" => "Механизм dialog_send работает только с AJAX"
            ];
        }
    }

    public function actionDialogGetMessages()
    {

        //$SetMessages = Message::getSetOfMessages(6, 6, 0);
       // echo "<pre>";
        //var_dump($SetMessages);
        //exit();

        // Создаём экземпляр модели.
        $newMessage = new Message();
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            // Получаем данные модели из запроса

            if(isset($data['id_dialog']) && isset($data['limit']) && isset($data['offset']))
            {
                $user_id = Yii::$app->user->identity->getId();
                $SetMessages = Message::getSetOfMessages($data['id_dialog'], $data['limit'], $data['offset'], $user_id);

                return [
                    "data" => $SetMessages,
                    "error" => null
                ];
            }
            else
            {
                // Если нет, отправляем ответ с сообщением об ошибке
                return [
                    "data" => $data,
                    "error" => "Пришли некорректные данные"
                ];
            }
        }
        else {
            // Если это не AJAX запрос, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "Механизм dialog-get-messages работает только с AJAX"
            ];
        }
    }


    public function actionFoto()
    {
        $cur_user = Yii::$app->user->identity;
        if (!empty($_GET['id'])) {
            $id_user = $_GET['id'];
        }
        else{
            $id_user = $cur_user->getId();
        }
        //$cur_user = User::findIdentity($user_id);

        $allPaths = Image::getAllImagePathsForUserAndAlbum($id_user, 1);

        /*echo '<pre>';
        var_dump($allPaths);
        exit();
*/
        return $this->render('fotoalbum', [
                'user_id' => $id_user,
                'allPaths' => $allPaths
        ]);

    }

    public function actionFotoLoad()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                //$data = Yii::$app->request->post();

                $file = $_FILES['file'];

                $image = new Image();
                $path = $image->upload($file, 1);

                $cur_user = Yii::$app->user->identity;
                $id_user = $cur_user->getId();
                $allPaths = Image::getAllImagePathsForUserAndAlbum($id_user, 1);

                $pathFotos = Yii::$app->params['dataUrl'].'img/main/';

                return [
                    'newPath' => $path,
                    'error' => '',
                    'allPaths' => $allPaths,
                    'pathFotos' => $pathFotos
                ];
            }

        }

        return [
            'newPaths' => [],
            'error' => 'error'
        ];
    }

    public function actionAvatarLoad()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                //$data = Yii::$app->request->post();

                $file = $_FILES['file'];

                $image = new Image();
                $path = $image->upload($file, 0);

                $cur_user = Yii::$app->user->identity;
                $id_user = $cur_user->getId();
                $allPaths = Image::getAllImagePathsForUserAndAlbum($id_user, 0);

                $pathFotos = Yii::$app->params['doman'].'data/img/avatar/';

                return [
                    'newPath' => $path,
                    'error' => '',
                    'allPaths' => $allPaths,
                    'pathFotos' => $pathFotos
                ];
            }

        }

        return [
            'newPaths' => [],
            'error' => 'error'
        ];
    }

    public function actionFotoDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();

                $image = new Image();

                foreach ($data['sources'] as $item) {
                    if ($item !== '') {
                        $thisImage = $image->findImageBySrc($item);

                        $image->replaceFileToDeleted($thisImage['id']);

                    }
                }

                $cur_user = Yii::$app->user->identity;
                $id_user = $cur_user->getId();
                $allPaths = Image::getAllImagePathsForUserAndAlbum($id_user, 1);

                $pathFotos = Yii::$app->params['dataUrl'].'img/main/';

                return [
                    'error' => '',
                    'allPaths' => $allPaths,
                    'pathFotos' => $pathFotos
                ];
            }

        }

        return [
            'newPaths' => [],
            'error' => 'error'
        ];
    }

    public function actionAvatarDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();

                $image = new Image();

                $pathFotos = Yii::$app->params['doman'].'data/img/avatar/';

                foreach ($data['sources'] as $item) {
                    if ($item !== '') {
                        $thisImage = $image->findImageBySrc($item, 0, $pathFotos);

                        $image->replaceFileToDeleted($thisImage['id'], 0);

                    }
                }

                $cur_user = Yii::$app->user->identity;
                $id_user = $cur_user->getId();
                $allPaths = Image::getAllImagePathsForUserAndAlbum($id_user, 0);

                return [
                    'error' => '',
                    'allPaths' => $allPaths,
                    'pathFotos' => $pathFotos
                ];
            }

        }

        return [
            'newPaths' => [],
            'error' => 'error'
        ];
    }

    //++ 1-2-2-004 18/03/2022
    public function actionShowError()
    {
        /*if($error = Yii::app()->errorHandler->error)
            $this->render('error', $error);
        */
        return $this->render('errorUser');
    }

    public function actionErrorUser()
    {
        //++ 1-2-2-005 28/03/2022

        $error = Yii::$app->errorHandler->exception;

        $date = 'Текущая дата: '.date('Y-m-d H:i:s');
        $path = 'URL: '.Yii::$app->request->absoluteUrl;

        $user_ip = 'Пользователь IP: '.Yii::$app->request->userIP;
        $user_host = 'Пользователь host: '.Yii::$app->request->userHost;
        $user_agent = 'Пользователь agent: '.Yii::$app->request->userAgent;

        $message = 'Сообщение: '.$error->getMessage();
        $code = 'Код: '.$error->getCode();
        $file = 'Файл: '.$error->getFile();
        $line = 'Строка: '.$error->getLine();
        $previous = 'Предыдущее: '.$error->getPrevious();
        $stack = 'Стэк вызовов: '.$error->getTraceAsString();

        if (isset(Yii::$app->user->identity)) {
            $user = 'Текущий пользователь: '.Yii::$app->user->identity->getId();
        } else {
            $user = 'Текущий пользователь: не авторизован';
        };

        $textError = $date.'<br>'.$path.'<br>'.$user.'<br>'.
            $user_ip.'<br>'.$user_host.'<br>'.$user_agent.'<br>'.
            $message.'<br>'.$code.'<br>'.$file.'<br>'.$line.'<br>'.$stack.'<br>'.$previous;


        //++ 1-2-2-007 05/04/2022
        $forSend = true;
        //-- 1-2-2-007 05/04/2022
        $is_block = false;
        if(mb_strripos($path,'.php') !== false && isset(Yii::$app->user->identity) === false) {
            $is_block = true;
        }
        if(mb_strripos($path,'.xml') !== false && isset(Yii::$app->user->identity) === false) {
            $is_block = true;
        }
        if(mb_strripos($path,'.txt') !== false && isset(Yii::$app->user->identity) === false) {
            $is_block = true;
        }
        //++ 1-2-2-006 02/04/2022
        if(mb_strripos($path,'.env') !== false && isset(Yii::$app->user->identity) === false) {
            $is_block = true;
        }
        if(mb_strripos($path,'.bak') !== false && isset(Yii::$app->user->identity) === false) {
            $is_block = true;
        }
        if(mb_strripos($path,'.git') !== false && isset(Yii::$app->user->identity) === false) {
            $is_block = true;
        }
        if(mb_strripos($path,'config') !== false && isset(Yii::$app->user->identity) === false) {
            $is_block = true;
        }
        //-- 1-2-2-006 02/04/2022

        if ($is_block) {
            $textError = $textError.'<br> Блокировка!';
        }

        //var_dump($textError);
        //++ 1-2-2-007 05/04/2022
        if(mb_strripos($path,'.ico') !== false) {
            $forSend = false;
        }

        if($forSend === true) {
        //-- 1-2-2-007 05/04/2022
            Mailer::sendLetter('Ошибки ЯВД', $textError, Yii::$app->params['myEmail']);
        //++ 1-2-2-007 05/04/2022
        }
        //-- 1-2-2-007 05/04/2022

        $is_block = false;
        if ($is_block) {
            header('HTTP/1.0 502 Bad Gateway');
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            echo '<html xmlns="http://www.w3.org/1999/xhtml">';
            echo '<head>';
            echo '<title>502 Bad Gateway</title>';
            echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
            echo '</head>';
            echo '<body>';
            echo '<h1 style="text-align:center">502 Bad Gateway</h1>';
            echo '<p style="background:#ccc;border:solid 1px #aaa;margin:30px au-to;padding:20px;text-align:center;width:700px">';
            echo 'К сожалению, Ваш запрос заблокирован, из-за подозрительных действий. Полная информация о ваших действиях передана в службу безопасности для анализа.<br />';
            echo 'Если Вы считаете, что имеете право на доступ к запрашиваемой странице, то обратитесь в службу поддержки для разрешения проблемы.<br />';
            echo '</p>';
            echo '</body>';
            echo '</html>';
            sleep(120);
            exit;
        }
        //-- 1-2-2-005 28/03/2022

        //header('HTTP/1.1 301 Moved Permanently');
        //header('Location: '.Yii::$app->params['doman'].'show-error');
        //exit();

    }
    //-- 1-2-2-004 18/03/2022

    //++ 1-2-3-002 11/05/2022
    public function actionSettings() {
        $user_id = Yii::$app->user->identity->getId();

        $Settings = Settings::getUsersSettings($user_id);
        $tab = 1;

        return $this->render('settings',
            compact('Settings', 'tab', 'user_id')
        );
    }

    public function actionSettingsSave() {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $Settings = $_POST;

            if((integer)$user_id !== 0) {
                $sem = Settings::editSet($user_id, $Settings);
            }

            return [
                "data" => $sem,
                "error" => "",
            ];
        }
    }

    public function actionSenderPanel() {
        $user_id = Yii::$app->user->identity->getId();
        $isAdmin = User::isAdmin($user_id);
        if($isAdmin == false) {
            return $this->render('errorUser');
        }

        return $this->render('senderPanel');
    }

    public function actionSenderPanelPost() {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $Settings = $_POST;

            if((integer)$user_id !== 0) {
                $rec = MailMessage::addRecord($Settings, $user_id);
            }

            return [
                "data" => $rec,
                "error" => "",
            ];
        }
    }
    //-- 1-2-3-002 11/05/2022

    //++ 1-2-3-003 28/06/2022
    public function actionLogs() {
        $user_id = Yii::$app->user->identity->getId();
        $isAdmin = User::isAdmin($user_id);
        if($isAdmin == false) {
            return $this->render('errorUser');
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            $startDate =  $data['dateFrom'];
            $finishDate =  $data['dateTo'];

            $option = [];
            //++ 1-2-3-007 05/08/2022
            //*-
            //if($data['user'] > 0) {
            //*+
            if((integer)$data['user'] >= 0) {
            //-- 1-2-3-007 05/08/2022
                $option['user'] = (integer)$data['user'];
            }
            if($data['status'] !== '') {
                $option['status'] = $data['status'];
            }
            //++ 1-2-3-007 05/08/2022
            if($data['URL'] !== '') {
                $option['URL'] = $data['URL'];
            }
            //-- 1-2-3-007 05/08/2022

            $Logs = Log::getLogs($startDate, $finishDate, $option);

            $dates = [];

            foreach ($Logs as $log) {
                $dates[$log['id']] = date("d.m.Y H:i:s", $log['created_at']);
            }

            $users = Log::getUsers($startDate, $finishDate);
            //++ 1-2-3-007 05/08/2022
            //*-
            //$statuses = Log::getStatuses($periodFrom, $periodTo);
            //*+
            $statuses = Log::getStatuses($startDate, $finishDate);
            $URLs = Log::getURLs($startDate, $finishDate);
            //-- 1-2-3-007 05/08/2022

            return [
                'error' => '',
                "data" => $Logs,
                'dates' => $dates,
                'pathNotes' => 'log/',
                'users' => $users,
                'statuses' => $statuses
                //++ 1-2-3-007 05/08/2022
                , 'URLs' => $URLs
                //-- 1-2-3-007 05/08/2022
            ];

        } else {

            $periodFrom = strtotime(date('Y-m-d 00:00:00'));
            $periodTo = strtotime(date('Y-m-d 23:59:59'));

            $users = Log::getUsers($periodFrom, $periodTo);
            $statuses = Log::getStatuses($periodFrom, $periodTo);
            $logs = Log::getLogs($periodFrom, $periodTo);
            //++ 1-2-3-007 05/08/2022
            $URLs = Log::getURLs($periodFrom, $periodTo);
            //-- 1-2-3-007 05/08/2022

            return $this->render('logs',
                //++ 1-2-3-007 05/08/2022
                //*-
                //compact('users', 'statuses', 'logs', 'periodFrom', 'periodTo'));
                //*+
                compact('users', 'statuses', 'logs', 'periodFrom', 'periodTo', 'URLs'));
                //-- 1-2-3-007 05/08/2022

        }
    }
    //-- 1-2-3-003 28/06/2022

    //++ 1-2-3-005 27/07/2022
    public function actionPublic()
    {
        $params = Yii::$app->request;
        if($params->get('n')) {
            $data = Note::getNoteById($params->get('n'));
            if ($data->isPublic == false) {
                return $this->render('errorUser');
            }
        }
        else {
            return $this->render('errorUser');
        }

        return $this->render('public', [
            "data" => $data,
        ]);

    }
    //-- 1-2-3-005 27/07/2022

}

