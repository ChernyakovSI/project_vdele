<?php
namespace frontend\controllers;

use frontend\models\TaskSearch;
use Yii;
use yii\base\InvalidParamException;
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
use frontend\models\ContactForm;
use yii\web\UploadedFile;
use common\models\Image;
use yii\helpers\Url;

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
                        'controllers' => ['site','xxx'],
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
                                    'ac-add-city'],
                        'controllers' => ['site','xxx'],
                        'allow' => true,
                        'roles' => ['@'],
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
            $id_user = $cur_user->getId();
            $city = City::findById($cur_user->id_city);

            $image = new Image();
            $path_avatar = $image->getPathAvatarForUser($id_user);

            if (!(user::activated($cur_user->getEmail()))) {
            Yii::$app->session->setFlash('error', 'Аккаунт не подтвержден! Перейдите по ссылке подтверждения, высланной на почту, по которой зарегистрировались!');
            }

            return $this->render('ac', [
                'user_id' => $id_user,
                'cur_user' => $cur_user,
                'months' => $months,
                'city' => $city,
                'path_avatar' => $path_avatar,
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
                $model = new LoginForm();
                //if ($model->load(Yii::$app->request->post()) && $model->login()) {
                    //return $this->goBack();
                //}


                if (Yii::$app->getUser()->login($user)) {
                    if (isset(Yii::$app->user->identity)) {
                        //Yii::$app->session->setFlash('success', 'Авторизован');
                        return Yii::$app->response->redirect(Url::to('site/index'));
                    }

                //    return $this->goHome();
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
            $path_avatar = $image->getPathAvatarForUser($user_id);

            if (Yii::$app->request->isPost) {
                if ($cur_user->load(Yii::$app->request->post()) && $cur_user->validate()) {
                    $cur_user->date_of_birth = strtotime(Yii::$app->request->post()['User']['date_of_birth']);//->getTimestamp();
                    $cur_user->id_city = (integer)Yii::$app->request->post()['User']['id_city'];

                    $cur_user->imageFile = UploadedFile::getInstance($cur_user, 'imageFile');
                    //var_dump($cur_user->image);

                    //if (isset($cur_user->imageFile)) {
                        if ($cur_user->upload()) {
                            Yii::$app->session->setFlash('success', 'Фото профиля обновлено');
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Не удалось обновить фото профиля');
                        }
                        //    Yii::$app->request->post()->image->saveAs('data/img/avatar/' . Yii::$app->request->post()->image->baseName . '.' . Yii::$app->request->post()->image->extension);
                    //}

                    $cur_user->imageFile = '';

                    if ($cur_user->save()) {
                        Yii::$app->session->setFlash('success', 'Изменения сохранены');
                    } else {
                        //Yii::$app->session->setFlash('success', 'Не удалось записать изменения');
                    }

                } else {
                    Yii::$app->session->setFlash('success', 'Не удалось записать изменения 2');
                }
            }

            $city = City::findById($cur_user->id_city);

            return $this->render('acEdit', [
                'cur_user' => $cur_user,
                'city' => $city,
                'path_avatar' => $path_avatar,
            ]);
        }
        else {
            return $this->render('index');
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
}
