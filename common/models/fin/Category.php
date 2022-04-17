<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 29.11.2020
 * Time: 14:27
 */

namespace common\models\fin;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return '{{fin_category}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'id_user', 'is_deleted', 'id_category', 'isProfit'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Название',
            'id_user' => 'Владелец',
            'is_deleted' => 'Скрыт',
            'id_category' => 'Категория',
            'isProfit' => 'Это доход'
        );
    }

    public static function add($data){
        $newCat = new Category();

        $newCat->name = strip_tags($data['name']);

        //++ 1-2-2-009 15/04/2022
        $newCat->color = $data['color'];
        if($data['isColored'] === 'true') {
            $newCat->isColored = 1;
        } else {
            $newCat->isColored = 0;
        }
        //-- 1-2-2-009 15/04/2022

        if(isset($data['created_at'])) {
            $newCat->created_at = $data['created_at'];
        }
        else{
            $newCat->created_at = time();
        };
        if(isset($data['updated_at'])) {
            $newCat->updated_at = $data['updated_at'];
        }
        else{
            $newCat->updated_at = time();
        };

        $id_user = Yii::$app->user->identity->getId();
        $newCat->id_user = $id_user;

        if(isset($data['is_deleted'])) {
            if($data['is_deleted'] == 'true'){
                $newCat->is_deleted = 1;
            }
            else if($data['is_deleted'] == 'false'){
                $newCat->is_deleted = 0;
            }
            else{
                $newCat->is_deleted = $data['is_deleted'];
            }
        };

        if(isset($data['id_category'])) {
            $newCat->id_category = $data['id_category'];
        }
        else{
            $newCat->id_category = 0;
        };

        if(isset($data['isProfit'])) {
            $newCat->isProfit = $data['isProfit'];
        }
        else{
            $newCat->isProfit = 0;
        };

        $newCat->save();

        return $newCat;
    }

    public static function edit($id, $data){
        $Cat = static::findOne(['id' => $id]);

        $Cat->name = strip_tags($data['name']);

        //++ 1-2-2-009 15/04/2022
        $Cat->color = $data['color'];
        if($data['isColored'] === 'true') {
            $Cat->isColored = 1;
        } else {
            $Cat->isColored = 0;
        }
        //-- 1-2-2-009 15/04/2022

        if(isset($data['updated_at'])) {
            $Cat->updated_at = $data['updated_at'];
        }
        else{
            $Cat->updated_at = time();
        };

        if(isset($data['is_deleted'])) {
            if($data['is_deleted'] == 'true'){
                $Cat->is_deleted = 1;
            }
            else if($data['is_deleted'] == 'false'){
                $Cat->is_deleted = 0;
            }
            else{
                $Cat->is_deleted = $data['is_deleted'];
            }
        };

        if(isset($data['id_category'])) {
            $Cat->id_category = $data['id_category'];
        }
        else{
            $Cat->id_category = 0;
        }

        if(isset($data['isProfit'])) {
            $Cat->isProfit = $data['isProfit'];
        };

        $Cat->save();

        return $Cat;
    }

    public static function del($id){
        $Cat = static::findOne(['id' => $id]);

        if($Cat->is_deleted == 0) {
            $Cat->is_deleted = 1;
        }
        else {
            $Cat->is_deleted = 0;
        };

        $Cat->updated_at = time();

        $Cat->save();

        $Cats = self::find()->where(['id_category' => $id, 'is_deleted' => 0])
            ->all();

        foreach ($Cats as $item) {
            if ($item['id'] != 0) {
                $item->is_deleted = 1;
                $item->save();
            }
        }

        return $Cat;
    }

    public static function getAllCategoriesByUser($id_user, $isProfit = 0){
        $body = self::find()->where(['id_user' => $id_user, 'is_deleted' => 0, 'id_category' => 0, 'isProfit' => $isProfit]);

        if(gettype($isProfit) == 'integer'){
            $body = $body->andWhere('isProfit = '.$isProfit);
        }else{
            $body = $body->andWhere('isProfit IN ('.implode(',',$isProfit).')');
        }

        $result = $body->orderBy('name')->all();

        return $result;
    }

    public static function getAllSubsByUserAndCategory($id_user, $id_category){
        return self::find()->where(['id_user' => $id_user, 'is_deleted' => 0, 'id_category' => $id_category])
            ->orderBy('name')->all();
    }

    public static function existsNameByUser($name, $id_user, $id, $id_category){
        $amounts = self::find()->select('id')->where(['id_user' => $id_user, 'name' => $name, 'is_deleted' => 0, 'id_category' => $id_category])
            ->andWhere('not id = '.$id)->all();

        if(count($amounts) > 0) {
            return true;
        };

        return false;
    }

    public static function getElemByName($name, $id_user){
        return self::find()->where(['name' => $name, 'id_user' => $id_user, 'is_deleted' => 0])->one();
    }
}