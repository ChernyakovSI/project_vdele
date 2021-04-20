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
use common\models\fin\Register;

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
            [['amount', 'sum'], 'double'],
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
            'sum' => 'Сумма',
            'id_currency' => 'Валюта',
            'is_deleted' => 'Скрыт',
            'comment' => 'Примечание'
        );
    }

    public static function getNameById($id){
        return self::find()->where(['id' => $id])->one()['name'];
    }

    public static function getElemByName($name, $id_user){
        return self::find()->where(['name' => $name, 'id_user' => $id_user, 'is_deleted' => 0])->one();
    }

    public static function getAllAccountsByUser($id_user, $calc = false){
        $Accs = self::find()->where(['id_user' => $id_user, 'is_deleted' => 0])->orderBy('num')->all();

        if($calc == true){
            foreach ($Accs as $Acc){
                $newSum = static::recalculateAcc($Acc['id'], $Acc['amount'], $id_user);

                $Acc = static::findOne($Acc['id']);
                $Acc->sum = $newSum;
                $Acc->save();
            }
        }

        $Accs = self::find()->where(['id_user' => $id_user, 'is_deleted' => 0])->orderBy('num')->all();

        return $Accs;
    }

    public static function getAllAccountsByUserWithDeleted($id_user, $calc = false){
        $Accs = self::find()->where(['id_user' => $id_user])->orderBy('num')->all();

        if($calc == true){
            foreach ($Accs as $Acc){
                static::recalculateAcc($Acc['id'], $Acc['amount'], $id_user);
            }
        }

        return $Accs;
    }

    public static function add($data){
        $newAcc = new Account();

        $newAcc->name = $data['name'];
        $newAcc->amount = (float)$data['amount'];
        $newAcc->comment = strip_tags($data['comment']);

        $newAcc->sum = (float)$data['amount'];

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

        if(isset($data['is_deleted'])) {
            if($data['is_deleted'] == 'true'){
                $newAcc->is_deleted = 1;
            }
            else if($data['is_deleted'] == 'false'){
                $newAcc->is_deleted = 0;
            }
            else{
                $newAcc->is_deleted = $data['is_deleted'];
            }
        };

        $newAcc->save();

        return $newAcc;
    }

    public static function edit($id, $data){
        $Acc = static::findOne(['id' => $id]);

        $id_user = Yii::$app->user->identity->getId();

        $amount = (float)$data['amount'];

        $Acc->name = $data['name'];
        $Acc->amount = $amount;
        $Acc->comment = strip_tags($data['comment']);

        $Acc->sum = static::recalculateAcc($id, $amount, $id_user);

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

        if(isset($data['is_deleted'])) {
            if($data['is_deleted'] == 'true'){
                $Acc->is_deleted = 1;
            }
            else if($data['is_deleted'] == 'false'){
                $Acc->is_deleted = 0;
            }
            else{
                $Acc->is_deleted = $data['is_deleted'];
            }
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
        $amounts = self::find()->select('sum')->where(['id_user' => $id_user, 'is_deleted' => 0])->all();
        $total = 0;

        if(count($amounts) > 0) {
            foreach ($amounts as $amount){
                $total = $total + $amount['sum'];
            }
        };

        return $total;
    }

    public static function getTotalAmountAccountsByUserWithDeleted($id_user){
        $amounts = self::find()->select('sum')->where(['id_user' => $id_user])->all();
        $total = 0;

        if(count($amounts) > 0) {
            foreach ($amounts as $amount){
                $total = $total + $amount['sum'];
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

    public static function recalculateAcc($id, $amount, $id_user){
        $sum = $amount;

        $Register = Register::find()->select('sum, id_type, id_account')
            ->where(['id_user' => $id_user, 'is_deleted' => 0, 'id_account' => $id])
            ->orWhere(['id_user' => $id_user, 'is_deleted' => 0, 'id_account_to' => $id])
            ->all();

        foreach ($Register as $reg){
            if($reg['id_type'] == 0) {
                $sum = $sum - $reg['sum'];
            }
            else if($reg['id_type'] == 1){
                $sum = $sum + $reg['sum'];
            }
            else {
                if($reg['id_account'] == $id) {
                    $sum = $sum - $reg['sum'];
                }
                else{
                    $sum = $sum + $reg['sum'];
                }
            }
        }

        return $sum;
    }

    public function calculateAcc($sum){
        $this->sum = $this->sum + $sum;
        $this->save();

        return 1;
    }



}