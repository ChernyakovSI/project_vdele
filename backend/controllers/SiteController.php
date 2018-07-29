<?php
namespace backend\controllers;

use backend\models\TaskSearch;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'logout'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            /*'rules' => [
                [
                    'allow' => true,
                    'actions'=>['login','error'],
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ],*/
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModelDone = new TaskSearch();
        $dataProviderDone = $searchModelDone->search(Yii::$app->request->queryParams, 1);

        $searchModelExpired = new TaskSearch();
        $dataProviderExpired = $searchModelExpired->search(Yii::$app->request->queryParams, 2);

        return $this->render('index', [
            'searchModelDone' => $searchModelDone,
            'dataProviderDone' => $dataProviderDone,
            'searchModelExpired' => $searchModelExpired,
            'dataProviderExpired' => $dataProviderExpired,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
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
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
