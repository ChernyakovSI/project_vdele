<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 27.10.2020
 * Time: 19:06
 */

namespace common\models\url;

use yii\db\ActiveRecord;
use Yii;

class Url extends ActiveRecord
{

    public static function tableName()
    {
        return '{{url_url}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'num', 'id_user', 'id_category', 'is_deleted'], 'integer'],
            [['name', 'url', 'comment'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'num' => 'Номер',
            'name' => 'Название',
            'id_user' => 'Владелец',
            'url' => 'Веб-ссылка',
            'id_category' => 'Категория',
            'is_deleted' => 'Удаден',
            'comment' => 'Примечание'
        );
    }

    public static function getAllURLsByUserAndCategory($id_user, $id_category){
        return self::find()->where(['id_user' => $id_user, 'is_deleted' => 0, 'id_category' => $id_category])
            ->orderBy('num')->all();
    }

    public static function getAllCategoriesByUser($id_user){
        return self::find()->where(['id_user' => $id_user, 'is_deleted' => 0, 'id_category' => -1])
            ->orderBy('num')->all();
    }

    //***
    public static function getAllURLsByUserWithDeleted($id_user){
        return self::find()->where(['id_user' => $id_user])->orderBy('num')->all();
    }

    public static function add($data){
        /*$newAcc = new Account();

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

        return $newAcc;*/
    }

    public static function edit($id, $data){
        /*$Acc = static::findOne(['id' => $id]);

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

        return $Acc;*/
    }

    //***
    public static function getNumByUser($id_user){
        $urls =  self::find()->select('num')
            ->where(['id_user' => $id_user])
            ->orderBy('num DESC')->all();

        if (count($urls) > 0){
            $lastUrl = $urls[0]['num'] + 1;
        }
        else{
            $lastUrl = 1;
        }

        return $lastUrl;
    }

    public static function getMaxNumURLByUserAndCategory($id_user, $id_category)
    {
        $realNum = self::find()->select('max(num)')->where(['id_user' => $id_user, 'id_category' => $id_category])
            ->andWhere('not url = ""')->scalar();

        if (isset($realNum)) {
            return $realNum;
        }
        else
        {
            return 0;
        }
    }

    public static function getMaxNumCategoryByUser($id_user)
    {
        $realNum = self::find()->select('max(num)')->where(['id_user' => $id_user])
            ->andWhere('url = ""')->scalar();

        if (isset($realNum)) {
            return $realNum;
        }
        else
        {
            return 0;
        }
    }

    //***
    public static function existsUrlByUser($url, $id_user, $id){
        $urls = self::find()->select('id')->where(['id_user' => $id_user, 'url' => $url])
            ->andWhere('not id = '.$id)->all();

        if(count($urls) > 0) {
            return true;
        };

        return false;
    }

    //***
    public static function changeOtherNumByUser($num, $id_user, $id){
        $urls = self::find()->select('id')->where(['id_user' => $id_user, 'num' => $num])
            ->andWhere('not id = '.$id)->all();

        if(count($urls) > 0) {
            foreach ($urls as $url){
                $urlElem = self::findOne($url['id']);
                if($id == 0){
                    $maxNum = self::getMaxNumByUser($id_user);
                    $curNum = $maxNum + 1;
                }
                else{
                    $curUrl = self::findOne($id);
                    $curNum = $curUrl->num;
                }

                $url->num = $curNum;
                $url->save();

                return $urlElem['id'];
            }
        };

        return 0;
    }





}