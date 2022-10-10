<?php


namespace common\models\edu;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\Image;

class CardElement extends ActiveRecord
{
    public static function tableName()
    {
        return '{{edu_member_card}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'date', 'id_user', 'is_active', 'id_sphere', 'parent', 'num', 'id_image1', 'id_image2'], 'integer'],
            [['title', 'value1', 'value2', 'image1', 'image2'], 'safe'],
        ];
    }

    public static function getElementCardById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function addRecord($group, $card) {
        $newRec = new CardElement();

        $newRec->created_at = time();
        $newRec->id_user = $group->id_user;
        $newRec->updated_at = time();

        $newRec->parent = $group->id;

        $newRec->value1 = $card->value1;
        $newRec->value2 = $card->value2;
        $newRec->id_image1 = $card->image1;
        $newRec->id_image2 = $card->image2;

        $newRec->is_deleted = 0;
        $newRec->is_active = 1;
        $newRec->date = time();
        $newRec->id_sphere = 0;
        $newRec->title = '';
        $newRec->image1 = '';
        $newRec->image2 = '';
        $newRec->num = 0;

        $newRec->save();

        return $newRec;
    }

    public static function editRecord($rec, $group, $card) {
        $rec->updated_at = time();

        $rec->parent = $group->id;
        $rec->id_user = $group->id_user;

        $rec->value1 = $card->value1;
        $rec->value2 = $card->value2;
        $rec->id_image1 = $card->image1;
        $rec->id_image2 = $card->image2;

        $rec->save();

        return $rec;
    }

    public static function deleteRecord($id) {
        $elem = self::getElementCardById($id);

        if(isset($elem) == true) {
            $elem->updated_at = time();

            $elem->is_deleted = 1;
            $elem->is_active = 0;

            $elem->save();
        }


        return $id;
    }

}