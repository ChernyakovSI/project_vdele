<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class Calendar extends ActiveRecord
{
    public static function tableName()
    {
        return '{{calendar}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'date', 'id_user', 'id_sphere'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'date' => 'Дата',
            'id_user' => 'Пользователь',
            'id_sphere' => 'Сфера',
        );
    }

    public static function getAllDaysForPeriod($startDate, $finishDate) {
        $allDays = [];
        $curDay = strtotime(date('Y-m-d 00:00:00', $startDate));

        while ($curDay <= $finishDate) {
            $allDays[] = $curDay;
            $curDay = strtotime(date('Y-m-d 00:00:00', strtotime('next day', $curDay)));
        }

        return $allDays;
    }

    public static function regSpecForDay($allDays, $clear = false) {
        $allDaysSpec = [];
        $allSpheres = [1, 2, 3, 4, 5, 6, 7, 8];
        $usedSpheres = [];

        foreach ($allDays as $day) {
            if ($clear === false) {
                if (count($usedSpheres) === 8) {
                    $usedSpheres = [];
                }

                $spec = rand(1, 8 - count($usedSpheres));

                $num = 0;
                $curSphere = 0;
                foreach ($allSpheres as $sphere) {
                    if(in_array($sphere, $usedSpheres) === false) {
                        $num = $num + 1;
                    }
                    if($num === $spec) {
                        $usedSpheres[] = $sphere;
                        $curSphere = $sphere;
                        break;
                    }
                }

                $allDaysSpec[$day] = $curSphere;
            } else
            {
                $allDaysSpec[$day] = 0;
            }
        }

        return $allDaysSpec;
    }

    //++ 003 16/03/2022
    public static function regKeyDay($allDays, $allDaysSpec) {
        $allDaysKey = [];

        $allSpheres = [1, 2, 3, 4, 5, 6, 7, 8];
        foreach ($allSpheres as $sphere) {
            $arrDaysBySpere = [];
            foreach ($allDays as $day) {
                if($allDaysSpec[$day] === $sphere) {
                    $arrDaysBySpere[] = $day;
                }
            }

            if(count($arrDaysBySpere) > 0) {
                $key = rand(1, count($arrDaysBySpere));
                $allDaysKey[] = $arrDaysBySpere[$key-1];
            }
        }

        return $allDaysKey;
    }
    //-- 003 16/03/2022

    //++ 003 15/03/2022
    //*-
    //public static function saveAllDays($allDaysSpec, $id_user) {
    //*+
    public static function saveAllDays($allDaysSpec, $allDaysKey, $id_user) {
    //-- 003 16/03/2022
        foreach ($allDaysSpec as $day => $sphere) {
            $rec = SELF::getRecordByDayAndUser((integer)$day, $id_user);

            if(isset($rec) === false) {
                $rec = new Calendar();

                $rec->created_at = time();
                $rec->id_user = $id_user;
                $rec->date = $day;
            }

            $rec->updated_at = time();
            $rec->id_sphere = $sphere;

            //++ 003 15/03/2022
            if (array_search($day, $allDaysKey) !== false) {
                $rec->is_key = 1;
            } else {
                $rec->is_key = 0;
            }
            //-- 003 16/03/2022

            $rec->save();
        }

        return 1;
    }

    public static function getRecordByDayAndUser($day, $id_user){
        return self::find()->where(['date' => $day, 'id_user' => $id_user])->one();
    }

    public static function getSpecDaysForPeriodAndUser($beginOfMonth, $endOfMonth, $id_user) {
        $query = new Query();
        //++ 003 16/03/2022
        $body = $query->Select(['Calendar.`id` as id',
            'Calendar.`date` as date',
            'Calendar.`id_sphere` as id_sphere',
            'Calendar.`is_key` as is_key',
        ])
         //-- 003 16/03/2022
            ->from(self::tableName().' as Calendar');

        $strWhere = 'Calendar.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND Calendar.`date` >= '.(integer)$beginOfMonth;
        $strWhere = $strWhere.' AND Calendar.`date` <= '.(integer)$endOfMonth;
        $strWhere = $strWhere.' AND Calendar.`id_sphere` > 0';

        $body = $body->where($strWhere)->orderBy('Calendar.`date`');

        return $body->all();
    }

    public static function getCountSpecDaysForPeriodAndUser($beginOfMonth, $endOfMonth, $id_user) {
        $query = new Query();
        $body = $query->Select(['Calendar.`id` as id',
            'Calendar.`date` as date',
            'Calendar.`id_sphere` as id_sphere',
        ])
            ->from(self::tableName().' as Calendar');

        $strWhere = 'Calendar.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND Calendar.`date` >= '.(integer)$beginOfMonth;
        $strWhere = $strWhere.' AND Calendar.`date` <= '.(integer)$endOfMonth;
        $strWhere = $strWhere.' AND Calendar.`id_sphere` > 0';

        $body = $body->where($strWhere)->orderBy('Calendar.`date`');

        return $body->count();
    }

    public static function getAllObjectsByFilter($id_user, $startDate, $finishDate, $sortDate = true){
        $query = new Query();
        $body = $query->Select(['Note.`id` as id',
            'Note.`date` as date',
            'Note.`id_sphere` as id_sphere',
            'Note.`title` as title',
            'Note.`num` as num',
        ])
            ->from('note as Note');

        $strWhere = 'Note.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND Note.`is_deleted` = 0';
        $strWhere = $strWhere.' AND Note.`date` >= '.(integer)$startDate;
        $strWhere = $strWhere.' AND Note.`date` <= '.(integer)$finishDate;

        $body = $body->where($strWhere);

        //цели

        $query = new Query();
        $bodyAmb = $query->Select(['Amb.`id` as id',
            'Amb.`date` as date',
            'Amb.`id_sphere` as id_sphere',
            'Amb.`title` as title',
            'Amb.`num` as num',
        ])
            ->from('ambition as Amb');

        $strWhere = 'Amb.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND Amb.`is_deleted` = 0';
        $strWhere = $strWhere.' AND Amb.`date` >= '.(integer)$startDate;
        $strWhere = $strWhere.' AND Amb.`date` <= '.(integer)$finishDate;

        $bodyAmb = $bodyAmb->where($strWhere);

        $body = $body->union($bodyAmb);

        if($sortDate === true) {
            $body = $body->orderBy('`date`');
        }
        else {
            $body = $body->orderBy('`date` DESC');
        }

        return $body->all();
    }
}