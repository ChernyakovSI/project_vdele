<?php


namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class Settings extends ActiveRecord
{
    public static function tableName()
    {
        return '{{settings}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user'], 'integer'],
            [['name', 'value'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id_user' => 'Пользователь',
            'name' => 'Настройка',
            'value' => 'Значение',
        );
    }

    public static function getUsersSettings($id_user) {
        $query = new Query();
        $body = $query->Select(['sets.`id` as id',
            'sets.`created_at` as created_at',
            'sets.`updated_at` as updated_at',
            'sets.`name` as name',
            'sets.`value` as value',
        ])
            ->from(self::tableName().' as sets');

        $strWhere = 'sets.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND sets.`is_deleted` = 0 ';

        $body = $body->where($strWhere)->orderBy('sets.`name`');

        $Settings = [];
        if($body->count() > 0) {
            foreach ($body->each() as $rec) {
                $Settings[$rec['name']] = $rec['value'];
            }

        }

        return $Settings;
    }

    public static function getSetByUserAndName($id_user, $name) {
        return self::find()->where(['id_user' => $id_user, 'name' => $name])->one();
    }

    public static function getUsersWithSet($name, $value) {
        $query = new Query();
        $body = $query->Select(['sets.`id_user` as id_user',
        ])
            ->from(self::tableName().' as sets');

        $strWhere = 'sets.`name`= "'.$name.'"';
        $strWhere = $strWhere.' AND sets.`value`= '.$value;
        $strWhere = $strWhere.' AND sets.`is_deleted` = 0 ';

        $result = $body->where($strWhere)->orderBy('sets.`id_user`')->all();

        return $result;
    }

    public static function editSet($id_user, $params) {
        $result = [];

        foreach ($params as $key => $value) {
            if($key === 'id_user') {
                continue;
            }

            $rec = self::getSetByUserAndName((integer)$id_user, $key, $value);
            if(isset($rec) === true) {
                $rec = Settings::editRecord($rec, $value);
            } else {
                $rec = Settings::addRecord($key, $value, (integer)$id_user);
            }
        }

        return $result;
    }

    public static function editRecord($rec, $value) {
        $rec->updated_at = time();

        $rec->value = strip_tags($value);

        $rec->save();

        return $rec;
    }

    public static function addRecord($key, $value, $id_user) {
        $newRec = new Settings();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        $newRec->name = strip_tags($key);
        $newRec->value = strip_tags($value);

        $newRec->save();

        return $newRec;
    }
}