<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class DiaryData extends ActiveRecord
{
    public static function tableName()
    {
        return '{{diary_data}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user', 'id_param', 'id_record'], 'integer'],
            [['value_str', 'value_txt', 'value_int', 'value_boo', 'value_dat'], 'safe'],
        ];
    }

    public static function getDiaryDataById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function getDiaryDataByRecordAndField($id_record, $id_param){
        return self::find()->where(['id_record' => $id_record, 'id_param' => $id_param])->one();
    }

    public static function renewRecord($field, $id_record, $id_user) {
        $Rec = SELF::getDiaryDataByRecordAndField($id_record, $field->id);

        if(isset($Rec) == false) {
            $Rec = new DiaryData();
        }

        $Rec->created_at = time();
        $Rec->id_user = $id_user;
        $Rec->updated_at = time();

        $Rec->is_deleted = 0;
        $Rec->id_param = (integer)$field->id;
        $Rec->id_record = (integer)$id_record;

        $Rec->value_str = '';
        $Rec->value_int = 0;
        $Rec->value_boo = 0;
        $Rec->value_txt = '';
        $Rec->value_dat = 0;

        if($field->type == '1') {
            $Rec->value_str = strip_tags($field->value_str);
        } elseif ($field->type == '2') {
            $Rec->value_int = $field->value_int;
        } elseif ($field->type == '3') {
            $Rec->value_boo = (integer)$field->value_boo;
        } elseif ($field->type == '4') {
            $Rec->value_txt = strip_tags($field->value_txt);
        } elseif ($field->type == '5') {
            $Rec->value_dat = (integer)$field->value_dat;
        }

        $Rec->save();

        return $field;
    }

}