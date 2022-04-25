<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\goal\Sphere;
use common\models\goal\Ambition;

class Day extends ActiveRecord
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

    public static function getDayByDate($date, $id_user){
        return self::find()->where(['date' => $date, 'id_user' => $id_user])->one();
    }

    public static function getDayDataByUserAndPeriod($id_user, $startDate, $finishDate){
        $query = new Query();
        $body = $query->Select(['Calendar.`id` as id',
            'Calendar.`date` as date',
            'Calendar.`id_sphere` as id_sphere',
            //++ 1-2-2-008 11/04/2022
            'Calendar.`is_key` as is_key',
            //-- 1-2-2-008 11/04/2022
            'IFNULL(Sphere.`name`, "") as name_sphere',
        ])
            ->from(self::tableName().' as Calendar')
            ->join('LEFT JOIN', Sphere::tableName().' as Sphere', 'Sphere.`id` = Calendar.`id_sphere`');

        $strWhere = 'Calendar.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND Calendar.`date` >= '.(integer)$startDate;
        $strWhere = $strWhere.' AND Calendar.`date` <= '.(integer)$finishDate;

        $body = $body->where($strWhere)->orderBy('Calendar.`date`');

        return $body->all();
    }

    public static function editRecord($params, $id_user) {
        $rec = SELF::getDayByDate((integer)$params['date'], $id_user);

        //++ 1-2-2-013 25/04/2022
        if(isset($rec) === false) {
            $rec = new Day;

            $rec->created_at = time();
            $rec->date = (integer)$params['date'];
            $rec->id_user = (integer)$id_user;
        }
        //-- 1-2-2-013 25/04/2022

        $rec->updated_at = time();

        $rec->id_sphere = (integer)$params['id_sphere'];
        //++ 1-2-2-008 11/04/2022
        $rec->is_key = (bool)$params['isKey'];
        //-- 1-2-2-008 11/04/2022

        $rec->save();

        return $rec;
    }
}