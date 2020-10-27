<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 27.10.2020
 * Time: 19:06
 */

namespace common\models\fin;

use yii\db\ActiveRecord;

class Account extends ActiveRecord
{

    public static function tableName()
    {
        return '{{fin_account}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'num', 'id_user', 'amount', 'id_currency', 'is_deleted'], 'integer'],
            [['name', 'comment'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'num' => 'Номер',
            'name' => 'Название',
            'id_user' => 'Владелец',
            'amount' => 'Сумма',
            'id_currency' => 'Валюта',
            'is_deleted' => 'Скрыт',
            'comment' => 'Примечание'
        );
    }

    public static function getAllAccountsByUser($id_user){
        return self::find()->where(['id_user' => $id_user])->orderBy('num')->all();
    }

}