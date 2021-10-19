<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class Ambition extends ActiveRecord
{
    public static function tableName()
    {
        return '{{ambition}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'date', 'id_user', 'id_sphere', 'num', 'id_level', 'status', 'dateDone'], 'integer'],
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
            'id_level' => 'Тип',
            'status' => 'Статус',
            'dateDone' => 'Дата достижения',
            'title' => 'Заголовок',
            'text' => 'Содержание',
        );
    }

    public static function getDreamByUserAndNum($id_user, $num){
        return self::find()->where(['id_user' => $id_user, 'num' => $num])->one();
    }

    public static function getDreamById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function getDreamsForPeriodAndUser($id_user, $startDate = 0, $finishDate = 0, $option = []) {
        $query = new Query();
        $body = $query->Select(['amb.`id` as id',
            'amb.`created_at` as created_at',
            'amb.`date` as date',
            'amb.`id_sphere` as id_sphere',
            'amb.`title` as title',
            'amb.`text` as text',
            'amb.`num` as num',
            'amb.`status` as status',
            'amb.`dateDone` as dateDone',
        ])
            ->from(self::tableName().' as amb');

        $strWhere = 'amb.`id_user`= '.(integer)$id_user;
        if($startDate > 0) {
            $strWhere = $strWhere.' AND amb.`created_at` >= '.(integer)$startDate;
        }
        if($finishDate > 0 && $finishDate > $startDate) {
            $strWhere = $strWhere.' AND amb.`created_at` <= '.(integer)$finishDate;
        }
        $strWhere = $strWhere.' AND amb.`is_deleted` = 0 ';
        //$strWhere = $strWhere.' AND amb.`id_level` = 1';

        if(isset($option['id_sphere']) === true && $option['id_sphere'] != 0) {
            $strWhere = $strWhere.' AND amb.`id_sphere` = '.(integer)$option['id_sphere'];
        }

        if(isset($option['status']) === true && count($option['status']) > 0) {
            if(count($option['status']) == 0) {
                $strWhere = $strWhere.' AND amb.`status` = '.(integer)$option['status'][0];
            } else {
                $strOr = '';
                $strDiv = '';
                foreach ($option['status'] as $status) {
                    $strOr = $strOr.$strDiv.'amb.`status` = '.(integer)$status;
                    $strDiv = ' OR ';
                }
                $strWhere = $strWhere.' AND ('.$strOr.')';
            }

        }

        if(isset($option['level']) === false) {
            $level = 0;
        } else {
            $level = $option['level'];
        }

        if($level > 0) {
            $strWhere = $strWhere.' AND amb.`id_level` = '.(integer)$level;
        }

        if($level == 4) {
            $body = $body->where($strWhere)->orderBy('amb.`date`');
        }
        else {
            $body = $body->where($strWhere)->orderBy('amb.`created_at`');
        }

        return $body->all();
    }

    public static function getLevels() {
        $query = new Query();
        $body = $query->Select(['amb.`id` as id',
            'amb.`name` as name',
        ])
            ->from('ambition_level as amb');

        $body = $body->orderBy('amb.`id`');

        return $body->all();
    }

    public static function addRecord($params, $id_user) {
        $newRec = new Ambition();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        /* Дэдлайн */
        if(isset($params['dateGoal'])) {
            $newRec->date = (integer)$params['dateGoal'];
        }
        else{
            $newRec->date = time();
        };

        $newRec->id_sphere = (integer)$params['id_sphere'];
        $newRec->id_level = (integer)$params['id_level'];
        $newRec->status = (integer)$params['status'];

        $newRec->title = strip_tags($params['title']);
        $newRec->text = strip_tags($params['text']);

        $newRec->num = self::getNumByUser($id_user);

        if(isset($params['dateDone'])) {
            $newRec->dateDone = (integer)$params['dateDone'];
        };

        $newRec->save();

        return $newRec;
    }

    public static function editRecord($params) {
        $rec = self::getDreamById((integer)$params['id']);

        $rec->updated_at = time();

        /* Дэдлайн */
        if(isset($params['dateGoal'])) {
            $rec->date = (integer)$params['dateGoal'];
        }
        else{
            $rec->date = time();
        };

        if(isset($params['dateDone'])) {
            $rec->dateDone = (integer)$params['dateDone'];
        };

        $rec->id_sphere = (integer)$params['id_sphere'];
        $rec->id_level = (integer)$params['id_level'];
        $rec->status = (integer)$params['status'];

        $rec->title = strip_tags($params['title']);
        $rec->text = strip_tags($params['text']);

        $rec->save();

        return $rec;
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
}