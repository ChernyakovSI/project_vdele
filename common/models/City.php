<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 08.06.2019
 * Time: 21:40
 */

namespace common\models;

use yii\db\ActiveRecord;

class City extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%city}}';
    }

    public function attributeLabels()
    {
        return array(
            'code' => 'Код',
            'name' => 'Населенный пункт',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен'
        );
    }

    public static function findByCode($code)
    {
        return static::findOne(['code' => $code]);
    }

    public static function findByCodeAndName($code, $name)
    {
        return static::findOne(['code' => $code, 'name' => $name]);
    }

    public static function findOrCreate($code, $name) {
        $cur_city = static::findByCode($_POST['id_city']);
        if (isset($cur_city)) {
            $cur_city_exactly = static::findByCodeAndName($_POST['id_city'], $_POST['name_city']);
            if (isset($cur_city_exactly)) {
                return $cur_city_exactly->id;
            }
            else {
                $cur_city->name = $_POST['name_city'];
                $cur_city->updated_at = time();
                $cur_city->save();
                return $cur_city->id;
            }
        }
        else {
            $cur_city = new City();
            $cur_city->code = $_POST['id_city'];
            $cur_city->name = $_POST['name_city'];
            $cur_city->created_at = time();
            $cur_city->updated_at = time();
            $cur_city->save();
            return $cur_city->id;
        }
    }

    public static function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
}