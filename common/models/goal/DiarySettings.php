<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class DiarySettings extends ActiveRecord
{
    public static function tableName()
    {
        return '{{diary_settings}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user', 'id_diary', 'num', 'type',
                'is_show', 'show_priority', 'is_active'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'updated_at' => 'Дата изменения',
            'id_user' => 'Пользователь',
            'id_diary' => 'Дневник',
            'title' => 'Название',
            'num' => 'Номер',
            'type' => 'Тип',
            'is_show' => 'Отображать в таблице',
            'show_priority' => 'Приоритет отображения',
            'is_active' => 'Включено',
        );
    }

    public static function getFieldById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function getDiarySettingsById($id_diary) {
        $query = new Query();
        $body = $query->Select(['diary_settings.`id` as id',
            'diary_settings.`updated_at` as updated_at',
            'diary_settings.`id_diary` as id_diary',
            'diary_settings.`num` as num',
            'diary_settings.`title` as title',
            'diary_settings.`type` as type',
            'diary_settings.`is_show` as is_show',
            'diary_settings.`show_priority` as show_priority',
            'diary_settings.`is_active` as is_active',
        ])
            ->from('diary_settings as diary_settings');

        $strWhere = ' diary_settings.`id_diary` = '.(integer)$id_diary;
        $strWhere = $strWhere.' AND diary_settings.`is_deleted` = 0';

        $body = $body
            ->where($strWhere)
            ->orderBy('diary_settings.`show_priority` DESC, diary_settings.`num`');


        $diaries = $body->all();

        return $diaries;
    }

    public static function getFieldsValueByRecord($id_record, $id_diary) {
        $query = new Query();
        $body = $query->Select(['diary_settings.`id` as id',
            'diary_settings.`updated_at` as updated_at',
            'diary_settings.`id_diary` as id_diary',
            'diary_settings.`num` as num',
            'diary_settings.`title` as title',
            'diary_settings.`type` as type',
            'diary_settings.`is_show` as is_show',
            'diary_settings.`show_priority` as show_priority',
            'diary_settings.`is_active` as is_active',
            'diary_data.`value_str` as value_str',
            'diary_data.`value_int` as value_int',
            'diary_data.`value_boo` as value_boo',
            'diary_data.`value_txt` as value_txt',
            'diary_data.`value_dat` as value_dat',
        ])
            ->from('diary_settings as diary_settings')
            ->join('LEFT JOIN', 'diary_data as diary_data', 'diary_data.`id_param` = diary_settings.`id` AND diary_data.`id_record` = '.(integer)$id_record);

        $strWhere = ' diary_settings.`id_diary` = '.(integer)$id_diary;
        $strWhere = $strWhere.' AND diary_settings.`is_deleted` = 0';

        $body = $body
            ->where($strWhere)
            ->orderBy('diary_settings.`show_priority` DESC, diary_settings.`num`');


        $diaries = $body->all();

        return $diaries;
    }

    public static function renewFields($id_diary, $fields, $user_id) {
        $allID = [];
        foreach ($fields as $fieldData) {
            if($fieldData->id > 0) {
                $element = SELF::getFieldById($fieldData->id);
                if(isset($element) == true) {
                    $element = SELF::editRecord($element, $fieldData);
                    array_push($allID, $element->id);
                } else {
                    $element = SELF::addRecord($id_diary, $fieldData, $user_id);
                    array_push($allID, $element->id);
                }
            } else {
                $element = SELF::addRecord($id_diary, $fieldData, $user_id);
                array_push($allID, $element->id);
            }
        }

        return $allID;
    }

    public static function addRecord($id_diary, $params, $id_user) {
        $newRec = new DiarySettings();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        $newRec->title = strip_tags($params->title);

        $newRec->is_deleted = 0;
        $newRec->type = (integer)$params->type;
        $newRec->is_show = (integer)$params->is_show;
        $newRec->show_priority = (integer)$params->show_priority;
        $newRec->is_active = (integer)$params->is_active;

        $newRec->id_diary = (integer)$id_diary;

        $newRec->num = self::getNumByDiary($newRec->id_diary);

        $newRec->save();

        return $newRec;
    }

    public static function getNumByDiary($id_diary){
        $fields =  self::find()->select('num')
            ->where(['id_diary' => $id_diary])
            ->orderBy('num DESC')->all();

        if (count($fields) > 0){
            $lastNum = $fields[0]['num'] + 1;
        }
        else{
            $lastNum = 1;
        }

        return $lastNum;
    }

    public static function editRecord($element, $params) {
        $element->updated_at = time();

        $element->title = strip_tags($params->title);

        $element->type = (integer)$params->type;
        $element->is_show = (integer)$params->is_show;
        $element->show_priority = (integer)$params->show_priority;
        $element->is_active = (integer)$params->is_active;

        $element->save();

        return $element;
    }

}