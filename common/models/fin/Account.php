<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 27.10.2020
 * Time: 19:06
 */

namespace common\models\fin;

use yii\db\ActiveRecord;
use Yii;

class Account extends ActiveRecord
{

    public static function tableName()
    {
        return '{{fin_account}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'num', 'id_user', 'id_currency', 'is_deleted'], 'integer'],
            ['amount', 'double'],
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

    public static function add($data){
        $newAcc = new Account();

        $newAcc->name = $data['name'];
        $newAcc->amount = (float)$data['amount'];
        $newAcc->comment = $data['comment'];

        if(isset($data['created_at'])) {
            $newAcc->created_at = $data['created_at'];
        }
        else{
            $newAcc->created_at = time();
        };
        if(isset($data['updated_at'])) {
            $newAcc->updated_at = $data['updated_at'];
        }
        else{
            $newAcc->updated_at = time();
        };

        $id_user = Yii::$app->user->identity->getId();
        $newAcc->id_user = $id_user;

        if(isset($data['num'])) {
            $newAcc->num = $data['num'];
        }
        else{
            $newAcc->num = self::getNumByUser($id_user);
        };

        if(isset($data['id_currency'])) {
            $newAcc->id_currency = $data['id_currency'];
        }
        else{
            $newAcc->id_currency = 1;
        };

        $newAcc->save();

        return $newAcc;
    }

    public static function edit($id, $data){
        $Acc = static::findOne(['id' => $id]);

        $Acc->name = $data['name'];
        $Acc->amount = (float)$data['amount'];
        $Acc->comment = $data['comment'];

        if(isset($data['updated_at'])) {
            $Acc->updated_at = $data['updated_at'];
        }
        else{
            $Acc->updated_at = time();
        };

        if(isset($data['num'])) {
            $Acc->num = $data['num'];
        };

        if(isset($data['id_currency'])) {
            $Acc->id_currency = $data['id_currency'];
        };

        $Acc->save();

        return $Acc;
    }

    public static function getNumByUser($id_user){
        $accs =  self::find()->select('num')
            ->where(['id_user' => $id_user])
            ->orderBy('num DESC')->all();

        if (count($accs) > 0){
            $lastAcc = $accs[0]['num'] + 1;
        }
        else{
            $lastAcc = 1;
        }

        return $lastAcc;
    }

    public static function formatNumberToMoney($num){
        return number_format ( $num, 2,  "." , " " );
    }

    public static function formatNumberToMoneyOnlyCents($num){
        return round  ( $num, 2 );
    }

    public static function getTotalAmountAccountsByUser($id_user){
        $amounts = self::find()->select('amount')->where(['id_user' => $id_user])->all();
        $total = 0;

        if(count($amounts) > 0) {
            foreach ($amounts as $amount){
                $total = $total + $amount['amount'];
            }
        };

        return $total;
    }

    public static function getMaxNumByUser($id_user)
    {
        $realNum = self::find()->select('max(num)')->where(['id_user' => $id_user])->scalar();

        if (isset($realNum)) {
            return $realNum;
        }
        else
        {
            return 0;
        }
    }

    public static function existsNameByUser($name, $id_user, $id){
        $amounts = self::find()->select('id')->where(['id_user' => $id_user, 'name' => $name])
            ->andWhere('not id = '.$id)->all();

        if(count($amounts) > 0) {
            return true;
        };

        return false;
    }

    public static function changeOtherNumByUser($num, $id_user, $id){
        $accounts = self::find()->select('id')->where(['id_user' => $id_user, 'num' => $num])
            ->andWhere('not id = '.$id)->all();

        if(count($accounts) > 0) {
            foreach ($accounts as $account){
                $acc = self::findOne($account['id']);
                if($id == 0){
                    $maxNum = self::getMaxNumByUser($id_user);
                    $curNum = $maxNum + 1;
                }
                else{
                    $curAcc = self::findOne($id);
                    $curNum = $curAcc->num;
                }

                $acc->num = $curNum;
                $acc->save();

                return $account['id'];
            }
        };

        return 0;
    }





}