<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 03.05.2021
 * Time: 14:24
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class TagUser extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tag_user}}';
    }

    public function attributeLabels()
    {
        return array(
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'id_user' => 'Пользователь',
            'id_tag' => 'Тег'
        );
    }

    public static function addRecord($id_user, $id_tag) {
        $newRec = new TagUser();
        $newRec->id_user = $id_user;
        $newRec->id_tag = $id_tag;
        $newRec->created_at = time();
        $newRec->updated_at = time();
        $newRec->save();
        return $newRec;
    }

    public static function deleteRecord($id_user, $id_tag) {
        static::deleteAll(['id_user' => $id_user, 'id_tag' => $id_tag]);

        return true;
    }
}