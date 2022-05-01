<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class Semester extends ActiveRecord
{
    public static function tableName()
    {
        return '{{semester}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'date', 'id_user', 'dateFinish'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'date' => 'Дата начала',
            'id_user' => 'Пользователь',
            'name' => 'Название',
            'dateFinish' => 'Дата окончания',
        );
    }

    public static function getSemesterById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function addRecord($params, $id_user) {
        $newRec = new Semester();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        //++ 1-2-3-001 01/05/2022
        //*-
        //$newRec->date = (integer)$params['date'];
        //$newRec->dateFinish = (integer)$params['dateFinish'];
        //*+
        $newRec->date = strtotime(date('d.m.Y', (integer)$params['date']));
        $newRec->dateFinish = strtotime(date('d.m.Y 23:59:59', (integer)$params['dateFinish']));
        //-- 1-2-3-001 01/05/2022

        $newRec->name = strip_tags($params['name']);

        $correct = SELF::isCorrect($newRec, $id_user);
        if($correct['error'] == false) {
            $newRec->save();
        } else {
            $newRec = $correct;
        }

        return $newRec;
    }

    public static function editRecord($params) {
        $rec = self::getSemesterById((integer)$params['id']);

        $rec->updated_at = time();

        //++ 1-2-3-001 01/05/2022
        //*-
        //$rec->date = (integer)$params['date'];
        //$rec->dateFinish = (integer)$params['dateFinish'];
        //*+
        $rec->date = strtotime(date('d.m.Y', (integer)$params['date']));
        $rec->dateFinish = strtotime(date('d.m.Y 23:59:59', (integer)$params['dateFinish']));
        //-- 1-2-3-001 01/05/2022

        $rec->name = strip_tags($params['name']);

        $correct = SELF::isCorrect($rec, $rec['id_user']);
        if($correct['error'] == false) {
            $rec->save();
        } else {
            $rec = $correct;
        }

        return $rec;
    }

    public static function deleteRecord($id) {
        $rec = self::getSemesterById((integer)$id);

        $rec->updated_at = time();
        $rec->is_deleted = 1;
        $rec->save();

        return $rec;
    }

    public static function isCorrect($rec, $id_user) {
        $eData = [];

        $eData['message'] = [];
        $eData['error'] = false;
        $eData['element'] = [];

        $message = [];

        $query = new Query();
        $body = $query->Select(['sem.`id` as id',
            'sem.`created_at` as created_at',
            'sem.`date` as date',
            'sem.`name` as name',
            'sem.`dateFinish` as dateFinish',
        ])
            ->from(self::tableName().' as sem');

        $strWhere = 'sem.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND sem.`is_deleted` = 0 ';
        $strWhere = $strWhere.' AND sem.`id`<> '.(integer)$rec['id'];

        $strWhere = $strWhere.' AND ((sem.`date` <= '.$rec['date'].' AND sem.`dateFinish` >= '.$rec['date'].') OR '.
                '(sem.`date` <= '.$rec['dateFinish'].' AND sem.`dateFinish` >= '.$rec['dateFinish'].') OR '.
                '(sem.`date` >= '.$rec['date'].' AND sem.`date` <= '.$rec['dateFinish'].'))';

        $result = $body->where($strWhere);

        if($result->count() > 0) {
            $strMessage = 'Есть пересекающие периоды: ';
            $separator = '';
            foreach ($result->each() as $res) {
                $strMessage = $strMessage.$separator.$res['name'].' '.date("d.m.Y", $res['date']).' - '.date("d.m.Y", $res['dateFinish']);
                $separator = '; ';
            }

            $message[] = $strMessage;
            $eData['error'] = true;
        }

        $eData['message'] = $message;
        return $eData;
    }

    public static function getNearestPriorityForDayAndUser($id_user, $curDate = 0, $next = 1, $option = []) {
        $result['zachet'] = [];
        $result['exam'] = [];
        $result['sem'] = [];
        $result['option'] = SELF::getBordersSemestersOfUser($id_user);

        if($result['option']['finish'] > time()) {
            $result['sem']['date'] = $result['option']['finish']+24*60*60;
            $result['sem']['dateFinish'] = strtotime("tomorrow", $result['option']['finish']+24*60*60) - 1;
        } else {
            $result['sem']['date'] = time();
            $result['sem']['dateFinish'] = strtotime("tomorrow", time()) - 1;
        }
        $result['sem']['name'] = '';
        $result['sem']['id'] = 0;


        $query = new Query();
        if($next == 1) {
            $body = $query->Select([
                'MIN(sem.`dateFinish`) as date',
            ])
                ->from('semester as sem');

            $strWhere = 'sem.`dateFinish` >= '.(integer)$curDate;
        } else {
            $body = $query->Select([
                'MAX(sem.`date`) as date',
            ])
                ->from('semester as sem');

            $strWhere = 'sem.`date` <= '.(integer)$curDate;
        }

        $strWhere = $strWhere.' AND sem.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND sem.`is_deleted` = 0 ';
        $body = $body->where($strWhere);

        if($body->count() > 0) {
            foreach ($body->each() as $rec) {
                $query = new Query();
                $bodySem = $query->Select(['sem.`id` as id',
                    'sem.`created_at` as created_at',
                    'sem.`date` as date',
                    'sem.`name` as name',
                    'sem.`dateFinish` as dateFinish',
                ])
                    ->from('semester as sem');

                $strWhere = 'sem.`id_user`= '.(integer)$id_user;
                $strWhere = $strWhere.' AND (sem.`date` <= '.$rec['date'].' AND sem.`dateFinish` >= '.$rec['date'].')';
                $strWhere = $strWhere.' AND sem.`is_deleted` = 0 ';
                $bodySem = $bodySem->where($strWhere)->orderBy('sem.`date`');

                if($bodySem->count() > 0) {
                    foreach ($bodySem->each() as $recSem) {
                        $startDate = $recSem['date'];
                        $finishDate = strtotime("tomorrow", $recSem['dateFinish']) - 1;

                        $sem['date'] = $startDate;
                        $sem['dateFinish'] = $finishDate;
                        $sem['name'] = $recSem['name'];
                        $sem['id'] = $recSem['id'];
                    }
                } else {
                    return $result;
                }

                break;
            }

        } else {
            return $result;
        }

        $result['sem'] = $sem;

        $body = $query->Select(['amb.`id` as id',
            'amb.`created_at` as created_at',
            'amb.`date` as date',
            'amb.`id_sphere` as id_sphere',
            'amb.`title` as title',
            'amb.`text` as text',
            'amb.`num` as num',
            'amb.`status` as status',
            'amb.`dateDone` as dateDone',
            'amb.`id_level` as id_level',
            'amb.`result_type` as result_type',
            'amb.`result_mark` as result_mark',
            'amb.`result_text` as result_text',
        ])
            ->from('ambition as amb');

        $strWhere = 'amb.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND (amb.`date` >= '.(integer)$startDate.' AND amb.`date` <= '.(integer)$finishDate.')';
        $strWhere = $strWhere.' AND amb.`is_deleted` = 0 ';
        $strWhere = $strWhere.' AND amb.`result_type` = 1';

        if(isset($option['level']) === false) {
            $level = 0;
        } else {
            $level = $option['level'];
        }

        if($level > 0) {
            $strWhere = $strWhere.' AND amb.`id_level` = '.(integer)$level;
        }

        if($level == 4) {
            $body = $body->where($strWhere)->orderBy('amb.`date`');
        }
        else {
            $body = $body->where($strWhere)->orderBy('amb.`created_at`');
        }

        $result['zachet'] = $body->all();


        $body = $query->Select(['amb.`id` as id',
            'amb.`created_at` as created_at',
            'amb.`date` as date',
            'amb.`id_sphere` as id_sphere',
            'amb.`title` as title',
            'amb.`text` as text',
            'amb.`num` as num',
            'amb.`status` as status',
            'amb.`dateDone` as dateDone',
            'amb.`id_level` as id_level',
            'amb.`result_type` as result_type',
            'amb.`result_mark` as result_mark',
            'amb.`result_text` as result_text',
        ])
            ->from('ambition as amb');

        $strWhere = 'amb.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND (amb.`date` >= '.(integer)$startDate.' AND amb.`date` <= '.(integer)$finishDate.')';
        $strWhere = $strWhere.' AND amb.`is_deleted` = 0 ';
        $strWhere = $strWhere.' AND amb.`result_type` = 2';

        if(isset($option['level']) === false) {
            $level = 0;
        } else {
            $level = $option['level'];
        }

        if($level > 0) {
            $strWhere = $strWhere.' AND amb.`id_level` = '.(integer)$level;
        }

        if($level == 4) {
            $body = $body->where($strWhere)->orderBy('amb.`date`');
        }
        else {
            $body = $body->where($strWhere)->orderBy('amb.`created_at`');
        }

        $result['exam'] = $body->all();

        return $result;
    }

    public static function getBordersSemestersOfUser($id_user) {
        $result['start'] = 0;
        $result['finish'] = 0;

        $query = new Query();
        $body = $query->Select([
                'MIN(sem.`date`) as start',
                'MAX(sem.`dateFinish`) as finish',
            ])
                ->from('semester as sem');

        $strWhere = 'sem.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND sem.`is_deleted` = 0 ';
        $body = $body->where($strWhere);

        if($body->count() > 0) {
            foreach ($body->each() as $rec) {
                $result['start'] = $rec['start'];
                $result['finish'] = $rec['finish'];

                break;
            }
        } else {
            return $result;
        }

        return $result;
    }

    public static function getPrioritiesForUser($id_user) {

        $query = new Query();
        $bodySem = $query->Select(['sem.`id` as id',
            'sem.`created_at` as created_at',
            'sem.`date` as date',
            'sem.`name` as name',
            'sem.`dateFinish` as dateFinish',
        ])
            ->from('semester as sem');

        $strWhere = 'sem.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND sem.`is_deleted` = 0 ';
        $result = $bodySem->where($strWhere)->orderBy('sem.`date`')->all();

        return $result;
    }
}