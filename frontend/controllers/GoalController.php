<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 25.05.2021
 * Time: 9:32
 */

namespace frontend\controllers;

use common\models\AcEdit;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\Cors;
use common\models\goal\Sphere;
use common\models\goal\SphereUser;
use common\models\goal\Note;
use common\models\fin\Register;
use common\models\goal\Calendar;
use common\models\goal\Day;
use common\models\fin\Reports;
use common\models\fin\Account;
use common\models\goal\Ambition;
use common\models\goal\Semester;
//++ 1-2-3-004 26/07/2022
use common\models\goal\Task;
//-- 1-2-3-004 26/07/2022
//++ 1-3-1-003 21/02/2023
use common\models\goal\Diary;
use common\models\goal\DiaryRecord;
use common\models\goal\DiarySettings;
//-- 1-3-1-003 21/02/2023

class GoalController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'controllers' => ['goal'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['spheres',
                                        'spheres-get',
                                        'spheres-save',
                                        'notes',
                                        'get-notes-for-period',
                                        'note',
                                        'note-save',
                                        'note-delete',
                                        'calendar',
                                        'get-data-for-month',
                                        'reg-speciality',
                                        'day',
                                        'get-data-for-day',
                                        'day-save',
                                        'dreams',
                                        'wishes',
                                        'intents',
                                        'goals',
                                        'dream',
                                        'dream-save',
                                        'dreams-refresh',
                                        'priority',
                                        'semester-save',
                                        'semester-del',
                                        'results',
                                        //++ 1-2-3-004 26/07/2022
                                        'tasks',
                                        'tasks-all',
                                        'task',
                                        //-- 1-2-3-004 26/07/2022
                                        //++ 1-3-1-003 21/02/2023
                                        'diaries',
                                        'diary',
                                        'diary_record',
                                        'diary-save',
                                        'diary-record-save',
                                        'diary-record-delete',
                                        'diary-settings',
                                        'diary-settings-save',
                                        'diary-record-fields',
                                        //-- 1-3-1-003 21/02/2023
                                        //++ 1-3-1-004 24/04/2023
                                        'diary-delete',
                                        //-- 1-3-1-004 24/04/2023
                                        //++ 1-3-2-002 19/05/2023
                                        'reminders',
                                        'reminder',
                                        //-- 1-3-2-002 19/05/2023
                                        ],
                        'controllers' => ['goal'],
                        'allow' => true,
                        'roles' => ['@','ws://'],
                    ],
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
        ];
    }

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

    public function actionSpheres()
    {
        $user_id = Yii::$app->user->identity->getId();

        $AllSpheres = Sphere::getSpheresByUser((integer)$user_id);

        return $this->render('spheres', [
            "AllSpheres" => $AllSpheres,
        ]);

    }

    public function actionSpheresGet()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();

            $rec = Sphere::getSphereByUserAndSphere($user_id, $_POST['id']);

            $color = Sphere::getColorForId((integer)$_POST['id'], 1);

            $AllSpheres = Sphere::getSpheresByUser((integer)$user_id);

            return [
                "data" => $rec,
                "color" => $color,
                "error" => "",
                "AllSpheres" => $AllSpheres,
            ];
        }

    }

    public function actionSpheresSave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_sphere = $_POST['id'];
            $name = $_POST['name'];

            SphereUser::editRecord($user_id, (integer)$id_sphere, $name);

            $rec = Sphere::getSphereByUserAndSphere($user_id, (integer)$id_sphere);

            $color = Sphere::getColorForId((integer)$id_sphere, 1);

            $AllSpheres = Sphere::getSpheresByUser((integer)$user_id);


            return [
                "data" => $rec,
                "color" => $color,
                "error" => "",
                "AllSpheres" => $AllSpheres,
            ];
        }

    }

    public function actionNotes()
    {
        $user_id = Yii::$app->user->identity->getId();

        //$AllNotes = Note::getAllNotesByUser($user_id);
        $startDate =  strtotime("-1 month");
        $finishDate =  strtotime("+1 month");
        $AllNotes = Note::getAllNotesByFilter($user_id, $startDate, $finishDate, false);

        $AllSpheres = $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('notes', [
            "AllNotes" => $AllNotes,
            "AllSpheres" => $AllSpheres,
        ]);

    }

    public function actionGetNotesForPeriod()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $startDate = $_POST['dateFrom'];
            $finishDate = $_POST['dateTo'];

            $option['id_sphere'] = $_POST['id_sphere'];

            //++ 1-2-3-006 28/07/2022
            $option['isPublic'] = $_POST['isPublic'];
            //-- 1-2-3-006 28/07/2022

            $allNotes = Note::getAllNotesByFilter($user_id, $startDate, $finishDate, false, $option);

            $pathNotes = 'note/';
            $colors = [];
            for($i=1; $i<=8; $i++){
                $colors[$i] = Sphere::getColorForId($i, 1, 1);
            }

            $dates = [];
            $datesColor = [];
            foreach ($allNotes as $note) {
                $dates[$note['id']] = date("d.m.Y H:i:s", $note['date']);
                $datesColor[$note['id']] = Note::getColorForDate($note['date']);
            }

            return [
                'error' => '',
                'allNotes' => $allNotes,
                'pathNotes' => $pathNotes,
                'colorStyle' => $colors,
                'dates' => $dates,
                'datesColor' => $datesColor
            ];
        }

    }

    public function actionNote()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        if($params->get('n')) {
            $data = Note::getNoteByUserAndNum($user_id, $params->get('n'));
            $date = $data['date'];
            $sphere = Sphere::getSphereById($data['id_sphere']);
        }
        else {
            $data = new Note();
            $date = time();
            $sphere = new Sphere();
        }


        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('note', [
            "data" => $data,
            "spheres" => $spheres,
            "date" => $date,
            "sphere" => $sphere
        ]);

    }

    public function actionNoteSave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_note = $_POST['id'];

            if((integer)$id_note == 0) {
                Note::addRecord($_POST, $user_id);
            }
            else {
                Note::editRecord($_POST);
            }

            return [
                "data" => $id_note,
                "error" => "",
            ];
        }

    }

    public function actionNoteDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();
                $user_id = Yii::$app->user->identity->getId();

                foreach ($data['sources'] as $item) {
                    if ($item !== '') {
                        Note::deleteRecord($item, $user_id);
                    }
                }

                $startDate = $_POST['dateFrom'];
                $finishDate = $_POST['dateTo'];

                $allNotes = Note::getAllNotesByFilter($user_id, $startDate, $finishDate, false);

                $pathNotes = 'note/';
                $colors = [];
                for($i=1; $i<=8; $i++){
                    $colors[$i] = Sphere::getColorForId($i, 1, 1);
                }

                $dates = [];
                $datesColor = [];
                foreach ($allNotes as $note) {
                    $dates[$note['id']] = date("d.m.Y H:i:s", $note['date']);
                    $datesColor[$note['id']] = Note::getColorForDate($note['date']);
                }

                return [
                    'error' => '',
                    'allNotes' => $allNotes,
                    'pathNotes' => $pathNotes,
                    'colorStyle' => $colors,
                    'dates' => $dates,
                    'datesColor' => $datesColor
                ];
            }

        }

        return [
            'error' => ''
        ];

    }

    public function actionCalendar()
    {
        $user_id = Yii::$app->user->identity->getId();

        $period = strtotime(date('Y-m-01 00:00:00'));
        $date = time();

        //список месяцев с названиями для замены
        $_monthsList = array("01" => "Январь", "02" => "Февраль",
            "03" => "Март", "04" => "Апрель", "05" => "Май", "06" => "Июнь",
            "07" => "Июль", "08" => "Август", "09" => "Сентябрь",
            "10" => "Октябрь", "11" => "Ноябрь", "12" => "Декабрь");

        //текущая дата
        $currentDate = date("m Y", $period);
        //переменная $currentDate теперь хранит текущую дату в формате 22.07.2015
        $curYear = date("Y", $period);

        //но так как наша задача - вывод русской даты,
        //заменяем число месяца на название:
        $_mD = date("m", $period); //для замены
        $currentDate = str_replace($_mD." ", $_monthsList[$_mD]." ", $currentDate);
        //теперь в переменной $currentDate хранится дата в формате 22 июня 2015

        $colorUnused = Sphere::getColorForId(-1, 0);
        $colorNone = Sphere::getColorForId(0, 1, 1);

        return $this->render('calendar', [
            'curDate' => $currentDate,
            'date' => $date,
            'colorUnused' => $colorUnused,
            'colorNone' => $colorNone,
            'curMonthName' => $_monthsList[$_mD],
            'curYear' => $curYear
        ]);

    }

    public function actionGetDataForMonth()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();
                $user_id = Yii::$app->user->identity->getId();

                //$beginOfDay = strtotime("today", $date);
                //$endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;
                $beginOfMonth =  strtotime(date('Y-m-01', $data['startDate']));
                $endOfMonth =   strtotime(date('Y-m-t 23:59:59', $data['startDate']));
                $allNotes = Calendar::getAllObjectsByFilter($user_id, $beginOfMonth, $endOfMonth);
                $allRegs = Register::getAllRegsByFilter($user_id, $beginOfMonth, $endOfMonth);

                $Speciality = Calendar::getSpecDaysForPeriodAndUser($beginOfMonth, $endOfMonth, $user_id);

                for($i=1; $i<=8; $i++){
                    $colors[$i] = Sphere::getColorForId($i, 1, 0);
                }

                return [
                    'error' => '',
                    'allNotes' => $allNotes,
                    'colorStyle' => $colors,
                    'regs' => $allRegs,
                    'speciality' => $Speciality,
                ];
            }

        }

        return [
            'error' => ''
        ];

    }

    public function actionRegSpeciality()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();
                $user_id = Yii::$app->user->identity->getId();

                //$beginOfDay = strtotime("today", $date);
                //$endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;
                $beginOfMonth =  strtotime(date('Y-m-01', $data['startDate']));
                $endOfMonth =   strtotime(date('Y-m-t 23:59:59', $data['startDate']));

                $countSpeciality = Calendar::getCountSpecDaysForPeriodAndUser($beginOfMonth, $endOfMonth, $user_id);

                $allDays = Calendar::getAllDaysForPeriod($beginOfMonth, $endOfMonth);
                $allDaysSpec = Calendar::regSpecForDay($allDays, $countSpeciality > 0);
                //++ 003 15/03/2022
                $allDaysKey = Calendar::regKeyDay($allDays, $allDaysSpec);
                //-- 003 15/03/2022

                //++ 003 15/03/2022
                //*-
                //Calendar::saveAllDays($allDaysSpec, $user_id);
                //*+
                Calendar::saveAllDays($allDaysSpec, $allDaysKey, $user_id);
                //-- 003 15/03/2022

                $Speciality = Calendar::getSpecDaysForPeriodAndUser($beginOfMonth, $endOfMonth, $user_id);

                $allNotes = Note::getAllNotesByFilter($user_id, $beginOfMonth, $endOfMonth);
                $allRegs = Register::getAllRegsByFilter($user_id, $beginOfMonth, $endOfMonth);

                for($i=1; $i<=8; $i++){
                    $colors[$i] = Sphere::getColorForId($i, 1, 0);
                }

                return [
                    'error' => '',
                    'allNotes' => $allNotes,
                    'colorStyle' => $colors,
                    'regs' => $allRegs,
                    'speciality' => $Speciality,
                    //++ 003 CherDel 15/03/2022
                    'allDaysKey' => $allDaysKey,
                    //-- 003 15/03/2022
                ];
            }

        }

        return [
            'error' => ''
        ];

    }

    public function actionDay()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        if($params->get('n')) {
            $date = strtotime($params->get('n'));
            $endDay = $date + 24*60*60 - 1;
        }
        else {
            $date = strtotime(date('Y-m-d 00:00:00'));
            $endDay = strtotime(date('Y-m-d 23:59:59'));
        }

        $dayData = Day::getDayDataByUserAndPeriod($user_id, $date, $endDay);
        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('day', [
            'date' => $date,
            'dayData' => $dayData,
            'spheres' => $spheres,
        ]);

    }

    public function actionGetDataForDay()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();
                $user_id = Yii::$app->user->identity->getId();

                $date = strtotime(date('Y-m-d 00:00:00', $data['date']));
                $endDay = strtotime(date('Y-m-d 23:59:59', $data['date']));

                $dayData = Day::getDayDataByUserAndPeriod($user_id, $date, $endDay);

                for($i=1; $i<=8; $i++){
                    $colors[$i] = Sphere::getColorForId($i, 1, 1);
                }

                $allNotes = Note::getAllNotesByFilter($user_id, $date, $endDay);
                $pathNotes = '/goal/note/';

                $resultsProf = Reports::getTotalByProfitCatsByUser($user_id, $date, $endDay);
                $resultsExp = Reports::getTotalByExpenceCatsByUser($user_id, $date, $endDay);

                $totalProf = 0;
                $totalExp = 0;

                $SumFormatProf = [];
                $SumFormatExp = [];

                foreach ($resultsExp as $item) {
                    if ($item['id_category'] != 0) {
                        $totalExp = $totalExp + $item['sum'];
                        $SumFormatExp[$item['id_category']] = Account::formatNumberToMoney($item['sum']);
                    }
                }
                foreach ($resultsProf as $item) {
                    if ($item['id_category'] != 0) {
                        $totalProf = $totalProf + $item['sum'];
                        $SumFormatProf[$item['id_category']] = Account::formatNumberToMoney($item['sum']);
                    }
                }

                $totalDelta = $totalProf - $totalExp;

                $totalProf = Account::formatNumberToMoney($totalProf);
                $totalExp = Account::formatNumberToMoney($totalExp);
                $totalDelta = Account::formatNumberToMoney($totalDelta);

                $option = [];
                $option['level'] = 4;
                $goals = Ambition::getDreamsForPeriodAndUser($user_id, $date, $endDay, $option);
                $pathGoals = '/goal/dream/';

                return [
                    'error' => '',
                    'dayData' => $dayData,
                    'colorStyle' => $colors,
                    'allNotes' => $allNotes,
                    'pathNotes' => $pathNotes,
                    'totalProf' => $totalProf,
                    'totalExp' => $totalExp,
                    'totalDelta' => $totalDelta,
                    'dataProf' => $resultsProf,
                    'dataExp' => $resultsExp,
                    'SumFormatExp' => $SumFormatExp,
                    'SumFormatProf' => $SumFormatProf,
                    'goals' => $goals,
                    'pathGoals' => $pathGoals
                ];
            }

        }

        return [
            'error' => ''
        ];
    }

    public function actionDaySave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            //++ 1-2-2-008 11/04/2022
            //*-
            //$date = $_POST['date'];
            //*+
            $date = [];
            //-- 1-2-2-008 11/04/2022

            Day::editRecord($_POST, $user_id);

            return [
                "data" => $date,
                //++ 1-2-2-008 11/04/2022
                'allNotes' => [],
                'goals' => [],
                'dataExp' => [],
                'dataProf' => [],
                //-- 1-2-2-008 11/04/2022
                "error" => "",
            ];
        }
    }

    public function actionGoals()
    {
        $getData = Yii::$app->request->get();

        $getData['level'] = 4;

        $user_id = Yii::$app->user->identity->getId();

        $startDate =  strtotime("-1 month 00:00");
        $finishDate =  strtotime("+1 year 23:59:59");

        $status = [0];
        $option = [
            'status' => $status
        ];

        if(isset($getData['level'])) {
            $option['level'] = $getData['level'];
        } else {
            $option['level'] = 1; //Мечты
        }

        $AllDreams = Ambition::getDreamsForPeriodAndUser($user_id, $startDate, $finishDate, $option);

        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('amb_dreams', [
            "AllDreams" => $AllDreams,
            "periodFrom" => $startDate,
            "periodTo" => $finishDate,
            "spheres" => $spheres,
            "level" => $option['level'],
        ]);
    }

    public function actionIntents()
    {
        $getData = Yii::$app->request->get();

        $getData['level'] = 3;

        $user_id = Yii::$app->user->identity->getId();

        $startDate =  strtotime("-30 year 00:00");
        $finishDate =  strtotime("+1 year 23:59:59");

        $status = [0];
        $option = [
            'status' => $status
        ];

        if(isset($getData['level'])) {
            $option['level'] = $getData['level'];
        } else {
            $option['level'] = 1; //Мечты
        }

        $AllDreams = Ambition::getDreamsForPeriodAndUser($user_id, $startDate, $finishDate, $option);

        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('amb_dreams', [
            "AllDreams" => $AllDreams,
            "periodFrom" => $startDate,
            "periodTo" => $finishDate,
            "spheres" => $spheres,
            "level" => $option['level'],
        ]);
    }

    public function actionWishes()
    {
        $getData = Yii::$app->request->get();

        $getData['level'] = 2;

        $user_id = Yii::$app->user->identity->getId();

        $startDate =  strtotime("-30 year 00:00");
        $finishDate =  strtotime("+1 year 23:59:59");

        $status = [0];
        $option = [
            'status' => $status
        ];

        if(isset($getData['level'])) {
            $option['level'] = $getData['level'];
        } else {
            $option['level'] = 1; //Мечты
        }

        $AllDreams = Ambition::getDreamsForPeriodAndUser($user_id, $startDate, $finishDate, $option);

        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('amb_dreams', [
            "AllDreams" => $AllDreams,
            "periodFrom" => $startDate,
            "periodTo" => $finishDate,
            "spheres" => $spheres,
            "level" => $option['level'],
        ]);
    }

    public function actionDreams()
    {
        $getData = Yii::$app->request->get();

        $user_id = Yii::$app->user->identity->getId();

        $startDate =  strtotime("-30 year 00:00");
        $finishDate =  strtotime("+1 year 23:59:59");

        $status = [0];
        $option = [
            'status' => $status
        ];

        if(isset($getData['level'])) {
            $option['level'] = $getData['level'];
        } else {
            $option['level'] = 1; //Мечты
        }

        $AllDreams = Ambition::getDreamsForPeriodAndUser($user_id, $startDate, $finishDate, $option);

        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('amb_dreams', [
            "AllDreams" => $AllDreams,
            "periodFrom" => $startDate,
            "periodTo" => $finishDate,
            "spheres" => $spheres,
            "level" => $option['level'],
        ]);
    }

    public function actionDreamsRefresh()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();
                $user_id = Yii::$app->user->identity->getId();

                $startDate =  $data['dateFrom'];
                $finishDate =  $data['dateTo'];

                $status = [];
                if($data['status_dont'] == 1) {
                    $status[] = 2;
                }
                if($data['status_process'] == 1) {
                    $status[] = 0;
                }
                if($data['status_done'] == 1) {
                    $status[] = 1;
                }

                $option = [];
                if(isset($data['id_sphere']) === true && $data['id_sphere'] !== 0) {
                    $option['id_sphere'] = $data['id_sphere'];
                }
                $option['status'] = $status;

                $option['level'] = $data['level'];

                $AllDreams = Ambition::getDreamsForPeriodAndUser($user_id, $startDate, $finishDate, $option);

                for($i=1; $i<=8; $i++){
                    $colors[$i] = Sphere::getColorForId($i, 1, 1);
                }

                $dates = [];
                //$datesColor = [];

                foreach ($AllDreams as $dream) {
                    if($option['level'] == 4) {
                        $dates[$dream['id']] = date("d.m.Y", $dream['date']).(($dream['status'] != 0)?' / '.date("d.m.Y", $dream['dateDone']):'');
                    } else {
                        $dates[$dream['id']] = date("d.m.Y", $dream['created_at']).(($dream['status'] != 0)?' / '.date("d.m.Y", $dream['dateDone']):'');
                    }
                    //$datesColor[$note['id']] = Note::getColorForDate($note['date']);
                }

                return [
                    'error' => '',
                    "data" => $AllDreams,
                    'colorStyle' => $colors,
                    'dates' => $dates,
                    'pathNotes' => 'dream/'
                ];
            }

        }

        return [
            'error' => ''
        ];
    }

    public function actionDream()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        $getData = $params->get();

        if($params->get('n')) {
            $data = Ambition::getDreamByUserAndNum($user_id, $params->get('n'));
            $date = $data['created_at'];
            $dateDone = $data['dateDone'];
            $dateGoal = $data['date'];
            $sphere = Sphere::getSphereById($data['id_sphere']);
        }
        else {
            $data = new Ambition();
            $date = time();
            $sphere = new Sphere();
            $dateDone = 0;
            $dateGoal = strtotime("+1 month");
        }

        if(isset($getData['level'])) {
            $level = $getData['level'];
        } else {
            $level = 1; //Мечты
        }

        $spheres = Sphere::getAllSpheresByUser($user_id);
        $levels = Ambition::getLevels();

        return $this->render('amb_dream', [
            "data" => $data,
            "spheres" => $spheres,
            "date" => $date,
            "dateDone" => $dateDone,
            "sphere" => $sphere,
            "levels" => $levels,
            "level" => $level,
            "dateGoal" => $dateGoal
        ]);

    }

    public function actionDreamSave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_dream = $_POST['id'];

            if((integer)$id_dream == 0) {
                $id_dream = Ambition::addRecord($_POST, $user_id);
            }
            else {
                $id_dream = Ambition::editRecord($_POST);
            }

            return [
                "data" => $id_dream,
                "error" => "",
            ];
        }

    }

    public function actionPriority()
    {
        $user_id = Yii::$app->user->identity->getId();

        $data = Yii::$app->request->get();

        if(isset($data['next'])){
            $curDate =  $data['date'];
            $next =  $data['next'];
            $option['level'] = 4;

            $AllPriority = Semester::getNearestPriorityForDayAndUser($user_id, $curDate, $next, $option);
        } else {

            //++ 1-2-2-014 27/04/2022
            //*-
            //$curDate =  strtotime('now');
            //*+
            $curDate =  strtotime('today');
            //-- 1-2-2-014 27/04/2022
            $option['level'] = 4;

            $AllPriority = Ambition::getPriorityForDayAndUser($user_id, $curDate, $option);
        }


        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('priority', [
            "AllPriority" => $AllPriority,
            "curDate" => $curDate,
            "spheres" => $spheres,
            "level" => 4,
        ]);
    }

    public function actionSemesterSave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_sem = $_POST['id'];

            if((integer)$id_sem == 0) {
                $sem = Semester::addRecord($_POST, $user_id);
            }
            else {
                $sem = Semester::editRecord($_POST);
            }

            return [
                "data" => $sem,
                "error" => "",
            ];
        }
    }

    public function actionSemesterDel()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_sem = $_POST['id'];

            if((integer)$id_sem == 0) {
                $error = 'Не указан id';
            }
            else {
                $sem = Semester::deleteRecord($id_sem);
            }

            return [
                "data" => $sem,
                "error" => $error,
            ];
        }
    }

    public function actionResults()
    {
        $user_id = Yii::$app->user->identity->getId();

        $data = Yii::$app->request->get();

        if(isset($data['typePeriod']) == false) {
            $PeriodType = 1; // 0 - все, 1 - по зачетке

            $curDate = strtotime('now');
            $option['level'] = 4;

            $CurPriority = Ambition::getPriorityForDayAndUser($user_id, $curDate, $option);

            if($CurPriority['sem']['id'] > 0) {
                $option['id'] = $CurPriority['sem']['id'];
                $AllResults = Ambition::getResultsForUser($user_id, $PeriodType, $option);

                $dateFrom = $CurPriority['sem']['date'];
                $dateTo = $CurPriority['sem']['dateFinish'];
            } else {
                $PeriodType = 0;
                $AllResults = Ambition::getResultsForUser($user_id, $PeriodType);

                $dateFrom = $curDate;
                $dateTo = $curDate;
            }
        } else {
            if($data['typePeriod'] == 0) {
                $PeriodType = 0; // 0 - все, 1 - по зачетке

                $AllResults = Ambition::getResultsForUser($user_id, $PeriodType);
                $curDate = strtotime('now');
                $option['level'] = 4;

                $CurPriority = Ambition::getPriorityForDayAndUser($user_id, $curDate, $option);

                if($CurPriority['sem']['id'] > 0) {
                    $dateFrom = $CurPriority['sem']['date'];
                    $dateTo = $CurPriority['sem']['dateFinish'];
                } else {
                    $dateFrom = $curDate;
                    $dateTo = $curDate;
                }
            } elseif ($data['typePeriod'] == 1) {
                $PeriodType = 1; // 0 - все, 1 - по зачетке

                $curDate = strtotime('now');
                $option['level'] = 4;
                $id = $data['sem'];

                $Priority = Semester::getSemesterById($id);
                $CurPriority['sem']['date'] = $Priority['date'];
                $CurPriority['sem']['dateFinish'] = $Priority['dateFinish'];
                $CurPriority['sem']['name'] = $Priority['name'];
                $CurPriority['sem']['id'] = $Priority['id'];

                if(isset($CurPriority['sem'])) {
                    $option['id'] = $CurPriority['sem']['id'];
                    $AllResults = Ambition::getResultsForUser($user_id, $PeriodType, $option);

                    $dateFrom = $CurPriority['sem']['date'];
                    $dateTo = $CurPriority['sem']['dateFinish'];
                } else {
                    $PeriodType = 0;
                    $AllResults = Ambition::getResultsForUser($user_id, $PeriodType);

                    $dateFrom = $curDate;
                    $dateTo = $curDate;
                }
            } else {
                $PeriodType = 2; // 0 - все, 1 - по зачетке, 2 - по периоду

                $curDate = strtotime('now');
                $option['level'] = 4;
                $from = $data['from'];
                $to = $data['to'];

                $CurPriority = Ambition::getPriorityForDayAndUser($user_id, $curDate, $option);

                $option['from'] = $from;
                $option['to'] = $to;
                $AllResults = Ambition::getResultsForUser($user_id, $PeriodType, $option);

                $dateFrom = $from;
                $dateTo = $to;
            }
        }

        $AllPriority = Semester::getPrioritiesForUser($user_id);
        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('results', [
            "AllResults" => $AllResults,
            "AllPriority" => $AllPriority,
            "CurPriority" => $CurPriority,
            "PeriodType" => $PeriodType,
            "spheres" => $spheres,
            "dateFrom" => $dateFrom,
            "dateTo" => $dateTo,
        ]);
    }

    //++ 1-2-3-004 26/07/2022
    public function actionTasks()
    {
        $getData = Yii::$app->request->get();

        $user_id = Yii::$app->user->identity->getId();

        $startDate =  strtotime("-1 month 00:00");
        $finishDate =  strtotime("+1 month 23:59:59");

        $status = [0];
        $option = [
            'status' => $status
        ];

        $AllTasks = [];//Ambition::getDreamsForPeriodAndUser($user_id, $startDate, $finishDate, $option);

        $spheres = Sphere::getAllSpheresByUser($user_id);

        return $this->render('tasks', [
            "AllTasks" => $AllTasks,
            "periodFrom" => $startDate,
            "periodTo" => $finishDate,
            "spheres" => $spheres,
        ]);
    }

    public function actionTasksAll()
    {
        $getData = Yii::$app->request->get();

        $user_id = Yii::$app->user->identity->getId();

        $startDate =  strtotime("-1 month 00:00");
        $finishDate =  strtotime("+1 month 23:59:59");

        /*$status = [0];
        $option = [
            'status' => $status
        ];*/
        $option = [];

        $AllTasks = Task::getTasksForPeriodAndUser($user_id, $startDate, $finishDate, $option);

        $statuses = Task::getStatuses();
        $spheres = Sphere::getAllSpheresByUser($user_id);
        $types = Task::getTypes();

        return $this->render('tasks-all', [
            "AllTasks" => $AllTasks,
            "periodFrom" => $startDate,
            "periodTo" => $finishDate,
            "spheres" => $spheres,
            "types" => $types,
            "statuses" => $statuses,
        ]);
    }

    public function actionTask()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        $getData = $params->get();

        if($params->get('n')) {
            /*$data = Ambition::getDreamByUserAndNum($user_id, $params->get('n'));
            $date = $data['created_at'];
            $dateDone = $data['dateDone'];
            $dateGoal = $data['date'];
            $sphere = Sphere::getSphereById($data['id_sphere']);*/
        }
        else {
            $data = new Task();
            $date = time();
            $sphere = new Sphere();
            $dateDone = 0;
            $dateGoal = strtotime("+1 week");
        }

        if(isset($getData['type'])) {
            $type = $getData['type'];
        } else {
            $type = 0; //Обычная задача
        }
        if(isset($getData['sphere'])) {
            $id_sphere = $getData['sphere'];
        } else {
            $id_sphere = 0;
        }
        if(isset($getData['goal'])) {
            $id_goal = $getData['goal'];
        } else {
            $id_goal = 0;
        }
        if(isset($getData['task'])) {
            $id_task = $getData['task'];
        } else {
            $id_task = 0;
        }

        $statuses = Task::getStatuses();

        $spheres = Sphere::getAllSpheresByUser($user_id);
        $types = Task::getTypes();

        $goals = Ambition::getActualGoalsForUser($user_id);
        $tasks = Task::getActualTasksForUser($user_id);

        return $this->render('task', [
            "data" => $data,
            "spheres" => $spheres,
            "date" => $date,
            "dateDone" => $dateDone,
            "sphere" => $sphere,
            "types" => $types,
            "type" => $type,
            "dateGoal" => $dateGoal,
            "goals" => $goals,
            "tasks" => $tasks,
            "id_sphere" => $id_sphere,
            "id_goal" => $id_goal,
            "id_task" => $id_task,
            "statuses" => $statuses
        ]);
    }
    //-- 1-2-3-004 26/07/2022

    //++ 1-3-1-003 21/02/2023
    public function actionDiaries()
    {
        $params = Yii::$app->request;

        $user_id = Yii::$app->user->identity->getId();

        //++ 1-3-1-004 24/04/2023
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if(isset($_POST['showFirst'])) {
                $showFirst =  $_POST['showFirst'];
            } else {
                $showFirst =  30;
            }
            $option['id_sphere'] = $_POST['id_sphere'];
            $option['isPublic'] = $_POST['isPublic'];

            $AllDiaries = Diary::getDiariesForUser($user_id, $showFirst, $option);

            $pathNotes = 'diary/';
            $colors = [];
            for($i=1; $i<=8; $i++){
                $colors[$i] = Sphere::getColorForId($i, 1, 1);
            }

            $dates = [];
            $datesColor = [];
            foreach ($AllDiaries as $note) {
                $dates[$note['id']] = date("d.m.Y H:i:s", $note['RecordDate']);
                $datesColor[$note['id']] = Note::getColorForDate($note['RecordDate']);
            }

            return [
                'error' => '',
                'AllDiaries' => $AllDiaries,
                'pathNotes' => $pathNotes,
                'colorStyle' => $colors,
                'dates' => $dates,
                'datesColor' => $datesColor
            ];
        } else {
        //-- 1-3-1-004 24/04/2023
            if($params->get('show')) {
                $showFirst =  $params->get('show');
            } else {
                //++ 1-3-1-004 24/04/2023
                //*-
                //$showFirst =  19;
                //*+
                $showFirst =  30;
                //-- 1-3-1-004 24/04/2023
            }
            $option = [];

            $AllDiaries = Diary::getDiariesForUser($user_id, $showFirst, $option);

            $spheres = Sphere::getAllSpheresByUser($user_id);

            return $this->render('diaries', [
                "AllDiaries" => $AllDiaries,
                "showFirst" => $showFirst,
                "spheres" => $spheres,
            ]);
        //++ 1-3-1-004 24/04/2023
        }
        //-- 1-3-1-004 24/04/2023
    }

    public function actionDiary()
    {
        $user_id = Yii::$app->user->identity->getId();
        $isGuest = 0;

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $id_dia = (integer)$_POST['id_diary'];
            $startDate = (integer)$_POST['dateFrom'];
            $finishDate = (integer)$_POST['dateTo'];
            //++ 1-3-1-005 28/04/2023
            $minElem = (integer)$_POST['minElem'];
            //-- 1-3-1-005 28/04/2023

            $showFirst = 0;
            $option = [
                'dateFrom' => $startDate,
                'dateTo' => $finishDate,
                //++ 1-3-1-005 28/04/2023
                'minElem' => $minElem,
                //-- 1-3-1-005 28/04/2023
                'opt' => $_POST
            ];

            $diariesRec = DiaryRecord::getRecordsForDiary($id_dia, $showFirst, $option);

            $data = $diariesRec['records'];
            $userFields = $diariesRec['userFields'];
            $dataTable = $diariesRec['dataTable'];
            $dataRecords = $diariesRec['dataRecords'];

            $dates = [];

            foreach ($data as $record) {
                $dates[$record['id']] = date("d.m.Y", $record['date']);
            }

            return [
                "data" => $data,
                "userFields" => $userFields,
                "id_dia" => $id_dia,
                "option" => $option,
                "error" => "",
                "dates" => $dates,
                "pathDiaryRecords" => '/goal/diary-record/',
                "dataTable" => $dataTable,
                "dataRecords" => $dataRecords,
            ];
        } else {

            $user_id = Yii::$app->user->identity->getId();

            $startDate = strtotime("-1 month 00:00");
            $finishDate = strtotime("+3 month 23:59:59");

            $params = Yii::$app->request;
            if ($params->get('n')) {
                $data = Diary::getDiaryById($params->get('n'));

                if($data->id_user != $user_id && $data->is_public == 0) {
                    return $this->render('/site/errorUser');
                } elseif ($data->id_user != $user_id && $data->is_public == 1) {
                    $isGuest = 1;
                } else {
                    $isGuest = 0;
                }

                $sphere = Sphere::getSphereById($data['id_sphere']);

                if ($params->get('show')) {
                    $showFirst = $params->get('show');
                } else {
                    $showFirst = 25;
                }
                $option = [
                    'dateFrom' => $startDate,
                    'dateTo' => $finishDate,
                    //++ 1-3-2-005 25/07/2023
                    'minElem' => 1,
                    //-- 1-3-2-005 25/07/2023
                ];
                $diariesRec = DiaryRecord::getRecordsForDiary($params->get('n'), $showFirst, $option);

                $records = $diariesRec['records'];
                $userFields = $diariesRec['userFields'];
                $setFields = $diariesRec['setFields'];
                $dataTable = $diariesRec['dataTable'];
                $dataRecords = $diariesRec['dataRecords'];

            } else {
                $data = new Diary();
                $sphere = new Sphere();
                $records = [];
                $userFields = [];
                $dataTable = [];
                $dataRecords = [];
            }

            $spheres = Sphere::getAllSpheresByUser($user_id);

            return $this->render('diary', [
                "data" => $data,
                "spheres" => $spheres,
                "sphere" => $sphere,
                "records" => $records,
                "userFields" => $userFields,
                "dateFrom" => $startDate,
                "dateTo" => $finishDate,
                "isGuest" => $isGuest,
                "dataTable" => $dataTable,
                "dataRecords" => $dataRecords
            ]);
        }

    }

    public function actionDiaryRecord()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        if($params->get('n')) {
            $data = DiaryRecord::getDiaryRecordById($params->get('n'));
            $date = $data['date'];
            $id_diary = $data['id_diary'];
            $diary_data = []; //Получить доп инфо
        }
        else {
            $id_diary = (integer)$params->get('dia');

            $data = new DiaryRecord();
            $date = time();
            $diary_data = [];
            $data['id_diary'] = $id_diary;
        }

        $diary = Diary::getDiaryById($id_diary);
        if($diary->id_user != $user_id && $diary->is_public == 0) {
            return $this->render('/site/errorUser');
        } elseif ($diary->id_user != $user_id && $diary->is_public == 1) {
            $isGuest = 1;
        } else {
            $isGuest = 0;
        }

        return $this->render('diary_record', [
            "data" => $data,
            "id_diary" => $id_diary,
            "date" => $date,
            "diary_data" => $diary_data,
            "isGuest" => $isGuest
        ]);

    }

    public function actionDiarySave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_dia = (integer)$_POST['id'];

            if((integer)$id_dia == 0) {
                $data = Diary::addRecord($_POST, $user_id);
            }
            else {
                $data = Diary::editRecord($_POST);
            }

            return [
                "data" => $data,
                "error" => "",
            ];
        }
    }

    public function actionDiaryRecordSave()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_rec = (integer)$_POST['id'];

            $fields = json_decode($_POST['fields']);

            if((integer)$id_rec == 0) {
                $data = DiaryRecord::addRecord($_POST, $user_id, $fields);
            }
            else {
                $data = DiaryRecord::editRecord($_POST, $fields);
            }

            return [
                "data" => $data,
                "error" => "",
            ];
        }
    }

    public function actionDiaryRecordDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = DiaryRecord::deleteRecord($_POST);

            return [
                "data" => $data,
                "error" => "",
            ];
        }
    }

    public function actionDiarySettings()
    {
        $user_id = Yii::$app->user->identity->getId();

        $params = Yii::$app->request;
        if($params->get('n')) {
            $diary = Diary::getDiaryById($params->get('n'));

            if($diary->id_user != $user_id) {
                return $this->render('/site/errorUser');
            } else {
                $isGuest = 0;
            }

            $data = DiarySettings::getDiarySettingsById($params->get('n'));
            $types = [];
            $types[0]['id'] = 1;
            $types[0]['name'] = "Строка";
            $types[1]['id'] = 2;
            $types[1]['name'] = "Число";
            $types[2]['id'] = 3;
            $types[2]['name'] = "Флаг";
            $types[3]['id'] = 4;
            $types[3]['name'] = "Текст";
            $types[4]['id'] = 5;
            $types[4]['name'] = "Время";
        }
        else {
            return $this->render('/site/errorUser');
        }

        return $this->render('diary-settings', [
            "data" => $data,
            "diary" => $diary,
            "types" => $types,
        ]);

    }

    public function actionDiarySettingsSave()
    {

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_diary = $_POST['id'];

            $fields = json_decode($_POST['fields']);

            if((integer)$id_diary !== 0) {
                DiarySettings::renewFields($id_diary, $fields, $user_id);
            }

            return [
                "data" => $fields,
                "error" => "",
            ];
        }

    }

    public function actionDiaryRecordFields()
    {

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user_id = Yii::$app->user->identity->getId();
            $id_dia = (integer)$_POST['id_diary'];
            $id_rec = (integer)$_POST['id'];

            if((integer)$id_dia == 0) {
                $data = [];
            }
            else {
                $data = DiarySettings::getFieldsValueByRecord($id_rec, $id_dia);
            }

            return [
                "data" => $data,
                "error" => "",
            ];
        }

    }
    //-- 1-3-1-003 21/02/2023

    //++ 1-3-1-004 24/04/2023
    public function actionDiaryDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            if (isset(Yii::$app->user->identity)) {

                $data = Yii::$app->request->post();
                $user_id = Yii::$app->user->identity->getId();

                foreach ($data['sources'] as $item) {
                    if ($item !== '') {
                        Diary::deleteRecord($item);
                    }
                }

                if(isset($_POST['showFirst'])) {
                    $showFirst =  $_POST['showFirst'];
                } else {
                    $showFirst =  30;
                }
                $option['id_sphere'] = $_POST['id_sphere'];
                $option['isPublic'] = $_POST['isPublic'];

                $AllDiaries = Diary::getDiariesForUser($user_id, $showFirst, $option);

                $pathNotes = 'diary/';
                $colors = [];
                for($i=1; $i<=8; $i++){
                    $colors[$i] = Sphere::getColorForId($i, 1, 1);
                }

                $dates = [];
                $datesColor = [];
                foreach ($AllDiaries as $note) {
                    $dates[$note['id']] = date("d.m.Y H:i:s", $note['RecordDate']);
                    $datesColor[$note['id']] = Note::getColorForDate($note['RecordDate']);
                }

                return [
                    'error' => '',
                    'AllDiaries' => $AllDiaries,
                    'pathNotes' => $pathNotes,
                    'colorStyle' => $colors,
                    'dates' => $dates,
                    'datesColor' => $datesColor
                ];
            }

        }

        return [
            'error' => ''
        ];
    }
    //-- 1-3-1-004 24/04/2023

    //++ 1-3-2-002 19/05/2023
    public function actionReminders()
    {
        $params = Yii::$app->request;

        $user_id = Yii::$app->user->identity->getId();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $option['id_sphere'] = $_POST['id_sphere'];

            $AllReminders = [];

            $pathNotes = 'reminder/';
            $colors = [];
            for($i=1; $i<=8; $i++){
                $colors[$i] = Sphere::getColorForId($i, 1, 1);
            }

            $dates = [];
            foreach ($AllReminders as $reminder) {
                $dates[$reminder['id']] = date("d.m.Y H:i:s", $reminder['nextDate']);
            }

            return [
                'error' => '',
                'AllReminders' => $AllReminders,
                'pathNotes' => $pathNotes,
                'colorStyle' => $colors,
                'dates' => $dates
            ];
        } else {
            $option = [];

            $AllReminders = [];//Diary::getDiariesForUser($user_id, $showFirst, $option);

            $spheres = Sphere::getAllSpheresByUser($user_id);
            $ObjectTypes = [];

            return $this->render('reminders', [
                "AllReminders" => $AllReminders,
                "spheres" => $spheres,
                "ObjectTypes" => $ObjectTypes,
            ]);
        }
    }
    //-- 1-3-2-002 19/05/2023
}

//-