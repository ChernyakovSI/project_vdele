<?php

namespace common\models\edu;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\Image;
use common\models\edu\CardElement;

class Card extends ActiveRecord
{
    public static function tableName()
    {
        return '{{edu_member_group}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'date', 'id_user', 'is_active', 'id_sphere', 'parent', 'num'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'date' => 'Дата',
            'id_user' => 'Пользователь',
            'id_sphere' => 'Сфера',
            'num' => 'Номер',
            'title' => 'Заголовок',
            'is_active' => 'Активность',
            'parent' => 'Группа',
        );
    }

    public static function getCardById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function getAllGroupsByFilter($id_user, $startDate, $finishDate, $sortDate = true, $option = []){
        if(isset($option['id_sphere']) && $option['id_sphere'] > 0) {
            $id_sphere = (integer)$option['id_sphere'];
        } else {
            $id_sphere = 0;
        }

        if(isset($option['is_active'])) {
            $is_active = (integer)$option['is_active'];
        } else {
            $is_active = -1;
        }

        if(isset($option['parent'])) {
            $parent = (integer)$option['parent'];
        } else {
            $parent = 0;
        }

        $query = new Query();
        $body = $query->Select(['Grp.`id` as id',
            'Grp.`date` as date',
            'Grp.`id_sphere` as id_sphere',
            'Grp.`title` as title',
            'Grp.`num` as num',
            'Grp.`parent` as parent',
            'Grp.`is_active` as is_active',
        ])
            ->from(self::tableName().' as Grp');

        $strWhere = ' Grp.`id_user` = '.(integer)$id_user;
        $strWhere = $strWhere.' AND Grp.`is_deleted` = 0';
        $strWhere = $strWhere.' AND Grp.`date` >= '.(integer)$startDate;
        $strWhere = $strWhere.' AND Grp.`date` <= '.(integer)$finishDate;
        $strWhere = $strWhere.' AND Grp.`parent` = '.$parent;
        if ($id_sphere > 0) {
            $strWhere = $strWhere.' AND Grp.`id_sphere` = '.$id_sphere;
        }
        if ($is_active > -1) {
            $strWhere = $strWhere.' AND Grp.`is_active` = '.$is_active;
        }

        if($sortDate === true) {
            $body = $body
                ->where($strWhere)
                ->orderBy('Grp.`date`');
        }
        else {
            $body = $body
                ->where($strWhere)
                ->orderBy('Grp.`date` DESC');
        }


        return $body->all();
    }

    public static function getCardByUserAndNum($id_user, $num, $option = []){
        $result = [];

        $head = self::find()->where(['id_user' => $id_user, 'num' => $num])->one();
        $result['head'] = $head;

        if (!(isset($head['id']) && $head['id'] > 0)) {
            $result['cards'] = [];
            return $result;
        }

        if(isset($option['is_active'])) {
            $is_active = (integer)$option['is_active'];
        } else {
            $is_active = -1;
        }

        if(isset($option['except'])) {
            $except = $option['except'];
        } else {
            $except = [];
        }

        $query = new Query();
        $body = $query->Select(['Card.`id` as id',
            'Card.`is_active` as is_active',
            'Card.`value1` as value1',
            'Card.`value2` as value2',
            'Card.`image1` as image1',
            'Card.`image2` as image2',
            'Card.`id_image1` as image1_id',
            'Card.`id_image2` as image2_id',
            'Img1.`src` as image1_src',
            'Img2.`src` as image2_src',
        ])
            ->from('edu_member_card as Card')
            ->join('LEFT JOIN', Image::tableName().' as Img1', 'Img1.`id` = Card.`id_image1`')
            ->join('LEFT JOIN', Image::tableName().' as Img2', 'Img2.`id` = Card.`id_image2`');

        $strWhere = ' Card.`id_user` = '.(integer)$id_user;
        $strWhere = $strWhere.' AND Card.`is_deleted` = 0';
        $strWhere = $strWhere.' AND Card.`parent` = '.(integer)$head['id'];
        if ($is_active > -1) {
            $strWhere = $strWhere.' AND Card.`is_active` = '.$is_active;
        }
        if (count($except) > 0) {
            $strWhere = $strWhere.' AND NOT Card.`id` IN ('.implode(",", $except).')';
        }

        $body = $body
                ->where($strWhere)
                ->orderBy('Card.`id`');


        $cards = $body->all();
        $result['cards'] = $cards;

        return $result;
    }

    public static function addRecord($params, $cards, $id_user) {
        $newRec = new Card();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        if(isset($params['date'])) {
            $newRec->date = (integer)$params['date'];
        }
        else{
            $newRec->date = time();
        };

        $newRec->id_sphere = (integer)$params['id_sphere'];

        $newRec->title = strip_tags($params['title']);

        $newRec->is_active = (integer)$params['is_active'];
        $newRec->parent = (integer)$params['parent'];

        $newRec->num = self::getNumByUser($id_user);

        $newRec->save();

        SELF::saveCards($newRec, $cards);

        return $newRec;
    }

    public static function getNumByUser($id_user){
        $cardss =  self::find()->select('num')
            ->where(['id_user' => $id_user])
            ->orderBy('num DESC')->all();

        if (count($cardss) > 0){
            $lastNum = $cardss[0]['num'] + 1;
        }
        else{
            $lastNum = 1;
        }

        return $lastNum;
    }

    public static function editRecord($params, $cards) {
        $rec = SELF::getCardById((integer)$params['id']);

        $rec->updated_at = time();

        if(isset($params['date'])) {
            $rec->date = (integer)$params['date'];
        };

        $rec->id_sphere = (integer)$params['id_sphere'];

        $rec->title = strip_tags($params['title']);

        $rec->is_active = (integer)$params['is_active'];
        $rec->parent = (integer)$params['parent'];

        $rec->save();

        SELF::saveCards($rec, $cards);

        return $rec;
    }

    public static function saveCards($group, $cards) {
        $allID = [];
        foreach ($cards as $card) {
            if($card->id > 0) {
                $element = CardElement::getElementCardById($card->id);
                if(isset($element) == true) {
                    $card = CardElement::editRecord($element, $group, $card);
                    array_push($allID, $card->id);
                } else {
                    $card = CardElement::addRecord($group, $card);
                    array_push($allID, $card->id);
                }
            } else {
                $card = CardElement::addRecord($group, $card);
                array_push($allID, $card->id);
            }
        }

        $option = [];
        $option['except'] = $allID;
        $result = SELF::getCardByUserAndNum($group->id_user, $group->num, $option);
        $cardsDel = $result['cards'];
        foreach ($cardsDel as $card) {
            $element = CardElement::getElementCardById($card['id']);
            CardElement::deleteRecord($element->id);
        }

        return true;
    }

}