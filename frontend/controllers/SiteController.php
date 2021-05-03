<?php
namespace frontend\controllers;

use common\models\Dialog;
use common\models\DialogUsers;
use common\models\Message;
use common\models\Tag;
use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
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
                                    'signup'],
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
                                    'intro'],
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
                Если письмо не пришло, то вышлете повторное письмо по кнопке из меню сверху, либо напишите в поддержку');
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

        $query = User::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $usersAll = $query->orderBy(['last_activity' => SORT_DESC])->all();

        $users = $query->orderBy(['last_activity' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $tags = Tag::getAllTags();

        return $this->render('users', [
            'users' => $users,
            'pagination' => $pagination,
            'usersAll' => $usersAll,
            'tags' => $tags,
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



}
