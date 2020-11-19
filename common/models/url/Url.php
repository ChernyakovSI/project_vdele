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
        $newUrl = new Url();

        $newUrl->name = $data['name'];
        $newUrl->url = $data['url'];
        $newUrl->comment = $data['comment'];

        if(isset($data['created_at'])) {
            $newUrl->created_at = $data['created_at'];
        }
        else{
            $newUrl->created_at = time();
        };
        if(isset($data['updated_at'])) {
            $newUrl->updated_at = $data['updated_at'];
        }
        else{
            $newUrl->updated_at = time();
        };

        $id_user = Yii::$app->user->identity->getId();
        $newUrl->id_user = $id_user;

        if(isset($data['num'])) {
            $newUrl->num = $data['num'];
        }
        else{
            $newUrl->num = self::getNumByUserAndCategory($id_user, $data['id_category']);
        };

        if(isset($data['is_deleted'])) {
            if($data['is_deleted'] == 'true'){
                $newUrl->is_deleted = 1;
            }
            else if($data['is_deleted'] == 'false'){
                $newUrl->is_deleted = 0;
            }
            else{
                $newUrl->is_deleted = $data['is_deleted'];
            }
        };

        if(isset($data['id_category'])) {
            $newUrl->id_category = $data['id_category'];
        }
        else{
            $newUrl->id_category = -1;
        }

        $newUrl->save();

        return $newUrl;
    }

    public static function edit($id, $data){
        $url = static::findOne(['id' => $id]);

        $url->name = $data['name'];
        $url->url = $data['url'];
        $url->comment = $data['comment'];

        if(isset($data['updated_at'])) {
            $url->updated_at = $data['updated_at'];
        }
        else{
            $url->updated_at = time();
        };

        if(isset($data['num'])) {
            $url->num = $data['num'];
        };

        if(isset($data['is_deleted'])) {
            if($data['is_deleted'] == 'true'){
                $url->is_deleted = 1;
            }
            else if($data['is_deleted'] == 'false'){
                $url->is_deleted = 0;
            }
            else{
                $url->is_deleted = $data['is_deleted'];
            }
        };

        $url->save();

        return $url;
    }

    public static function del($id){
        $url = static::findOne(['id' => $id]);

        if($url->is_deleted == 0) {
            $url->is_deleted = 1;
        }
        else {
            $url->is_deleted = 0;
        };

        $url->updated_at = time();

        $url->save();

        return $url;
    }

    public static function getNumByUserAndCategory($id_user, $id_category){
        $urls =  self::find()->select('num')
            ->where(['id_user' => $id_user, 'id_category' => $id_category])
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

    public static function getMaxNumCatByUser($id_user)
    {
        $realNum = self::find()->select('max(num)')->where(['id_user' => $id_user, 'id_category' => -1])
            ->scalar();

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

    public static function changeOtherNumByUserAndCategory($num, $id_user, $id_category, $id){
        $urls = self::find()->select('id')->where(['id_user' => $id_user, 'num' => $num, 'id_category' => $id_category])
            ->andWhere('not id = '.$id)->all();

        if(count($urls) > 0) {
            foreach ($urls as $url){
                $urlElem = self::findOne($url['id']);
                if($id == 0){
                    $maxNum = self::getMaxNumURLByUserAndCategory($id_user, $id_category);
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

    public static function changeOtherNumByUser($num, $id_user, $id){
        $urls = self::find()->select('id')->where(['id_user' => $id_user, 'num' => $num, 'id_category' => -1])
            ->andWhere('not id = '.$id)->all();

        if(count($urls) > 0) {
            foreach ($urls as $url){
                $urlElem = self::findOne($url['id']);
                if($id == 0){
                    $maxNum = self::getMaxNumCatByUser($id_user);
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