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

    public static function regSpecForDay($allDays) {
        $allDaysSpec = [];
        $allSpheres = [1, 2, 3, 4, 5, 6, 7, 8];
        $usedSpheres = [];

        foreach ($allDays as $day) {
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
        }


        return $allDaysSpec;
    }

    public static function saveAllDays($allDaysSpec, $id_user) {
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

            $rec->save();
        }

        return 1;
    }

    public static function getRecordByDayAndUser($day, $id_user){
        return self::find()->where(['date' => $day, 'id_user' => $id_user])->one();
    }

    public static function getSpecDaysForPeriodAndUser($beginOfMonth, $endOfMonth, $id_user) {
        $query = new Query();
        $body = $query->Select(['Calendar.`id` as id',
            'Calendar.`date` as date',
            'Calendar.`id_sphere` as id_sphere',
        ])
            ->from(self::tableName().' as Calendar');

        $strWhere = 'Calendar.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND Calendar.`date` >= '.(integer)$beginOfMonth;
        $strWhere = $strWhere.' AND Calendar.`date` <= '.(integer)$endOfMonth;

        $body = $body->where($strWhere)->orderBy('Calendar.`date`');

        return $body->all();
    }
}