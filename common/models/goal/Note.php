<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 09.06.2021
 * Time: 9:24
 */

namespace common\models\goal;

use yii\db\ActiveRecord;

class Note extends ActiveRecord
{

    public static function tableName()
    {
        return '{{note}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'date', 'id_user', 'id_sphere', 'num'], 'integer'],
            [['title', 'text'], 'safe'],
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
            'text' => 'Содержание',
        );
    }

    public static function getAllNotesByUser($id_user){
        return self::find()->where(['id_user' => $id_user, 'is_deleted' => false])->orderBy('date DESC')->all();
    }

    public static function getNoteByUserAndNum($id_user, $num){
        return self::find()->where(['id_user' => $id_user, 'num' => $num])->one();
    }

    public static function getNoteById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function getNewNote(){
        return new Note();
    }

    public static function addRecord($params, $id_user) {
        $newRec = new Note();

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
        $newRec->text = strip_tags($params['text']);

        $newRec->num = self::getNumByUser($id_user);

        $newRec->save();

        return $newRec;
    }

    public static function getNumByUser($id_user){
        $notes =  self::find()->select('num')
            ->where(['id_user' => $id_user])
            ->orderBy('num DESC')->all();

        if (count($notes) > 0){
            $lastNum = $notes[0]['num'] + 1;
        }
        else{
            $lastNum = 1;
        }

        return $lastNum;
    }

    public static function editRecord($params) {
        $rec = SELF::getNoteById((integer)$params['id']);

        $rec->updated_at = time();

        if(isset($params['date'])) {
            $rec->date = (integer)$params['date'];
        };

        $rec->id_sphere = (integer)$params['id_sphere'];

        $rec->title = strip_tags($params['title']);
        $rec->text = strip_tags($params['text']);

        $rec->save();

        return $rec;
    }

    public static function deleteRecord($num, $id_user) {
        $rec = SELF::getNoteByUserAndNum($id_user, (integer)$num);

        $rec->updated_at = time();
        $rec->is_deleted = true;

        $rec->save();

        return $rec;
    }

    public static function isToday($date){
        $curDay = date('d', $date);
        $curMonth = date('m', $date);
        $curYear = date('Y', $date);

        $nowDay = date('d');
        $nowMonth = date('m');
        $nowYear = date('Y');

        $isToday = false;

        if($curDay === $nowDay && $curMonth === $nowMonth && $curYear === $nowYear) {
            $isToday = true;
        }

        return $isToday;
    }

    public static function isPast($date){
        $curDay = date('d', $date);
        $curMonth = date('m', $date);
        $curYear = date('Y', $date);

        $nowDay = date('d');
        $nowMonth = date('m');
        $nowYear = date('Y');

        $isPast = false;

        if($curDay === $nowDay && $curMonth === $nowMonth && $curYear === $nowYear) {
            $isPast = false;
        } else {
            if(time() > $date) {
                $isPast = true;
            }
        }

        return $isPast;
    }

    public static function getColorForDate($date){
        $color = 'text-color-darkBlue';

        if (SELF::isToday($date) === true) {
            $color = 'text-color-red';
        } elseif (SELF::isPast($date) === true) {
            $color = '';
        }

        return $color;
    }
}