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

        $newRec->date = (integer)$params['date'];
        $newRec->dateFinish = (integer)$params['dateFinish'];

        $newRec->name = strip_tags($params['name']);

        $newRec->save();

        return $newRec;
    }

    public static function editRecord($params) {
        $rec = self::getSemesterById((integer)$params['id']);

        $rec->updated_at = time();

        $rec->date = (integer)$params['date'];
        $rec->dateFinish = (integer)$params['dateFinish'];

        $rec->name = strip_tags($params['name']);

        $rec->save();

        return $rec;
    }
}