<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class Diary extends ActiveRecord
{

    public static function tableName()
    {
        return '{{diary}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user', 'id_sphere', 'is_public', 'priority'], 'integer'],
            [['title', 'description'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'updated_at' => 'Дата изменения',
            'id_user' => 'Пользователь',
            'id_sphere' => 'Сфера',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'is_public' => 'Опубликован',
            'priority' => 'Приоритет отображения в списке',
        );
    }

    public static function getDiaryById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function addRecord($params, $id_user) {
        $newRec = new Diary();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        $newRec->id_sphere = (integer)$params['id_sphere'];

        $newRec->title = strip_tags($params['title']);
        $newRec->description = strip_tags($params['description']);

        $newRec->is_public = (integer)$params['is_public'];
        $newRec->priority = (integer)$params['priority'];

        $newRec->save();

        return $newRec;
    }

    public static function editRecord($params) {
        $rec = SELF::getDiaryById((integer)$params['id']);

        $rec->updated_at = time();

        $rec->id_sphere = (integer)$params['id_sphere'];

        $rec->title = strip_tags($params['title']);
        $rec->description = strip_tags($params['description']);

        $rec->is_public = (integer)$params['is_public'];
        $rec->priority = (integer)$params['priority'];

        $rec->save();

        return $rec;
    }

    public static function getDiariesForUser($id_user, $showFirst, $option) {
        if(isset($option['is_public'])) {
            $is_public = (integer)$option['is_public'];
        } else {
            $is_public = -1;
        }

        $query = new Query();
        $body = $query->Select(['diary.`id` as id',
            'diary.`updated_at` as updated_at',
            'diary.`title` as title',
            'diary.`is_public` as is_public',
            'diary.`priority` as priority',
            'diary.`id_sphere` as id_sphere',
        ])
            ->from('diary as diary');

        $strWhere = ' diary.`id_user` = '.(integer)$id_user;
        $strWhere = $strWhere.' AND diary.`is_deleted` = 0';

        if ($is_public > -1) {
            $strWhere = $strWhere.' AND diary.`is_public` = '.$is_public;
        }

        $body = $body
            ->where($strWhere)
            ->orderBy('diary.`priority` DESC, diary.`updated_at`');


        $diaries = $body->all();

        return $diaries;
    }
}