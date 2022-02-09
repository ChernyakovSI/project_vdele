<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class Task extends ActiveRecord
{
    public static function tableName()
    {
        return '{{task}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'date', 'id_user', 'id_sphere', 'num', 'id_goal',
                'id_task', 'type', 'status', 'dateDone', 'is_auto', 'plan', 'fact', 'priority'], 'integer'],
            [['title', 'text', 'result_text'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'date' => 'Дата',
            'id_user' => 'Пользователь',
            'id_sphere' => 'Сфера',
            'num' => 'Номер',
            'type' => 'Тип',
            'status' => 'Статус',
            'dateDone' => 'Дата достижения',
            'title' => 'Заголовок',
            'text' => 'Содержание',
            'result_text' => 'Решение',
            'is_auto' => 'Авторежим',
            'plan' => 'План',
            'fact' => 'Факт',
            'priority' => 'Приоритет',
        );
    }

    public static function getTaskByUserAndNum($id_user, $num){
        return self::find()->where(['id_user' => $id_user, 'num' => $num])->one();
    }

    public static function getTaskById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function getTasksForPeriodAndUser($id_user, $startDate = 0, $finishDate = 0, $option = []) {
        $query = new Query();
        $body = $query->Select(['task.`id` as id',
            'task.`created_at` as created_at',
            'task.`date` as date',
            'task.`id_sphere` as id_sphere',
            'task.`title` as title',
            'task.`text` as text',
            'task.`num` as num',
            'task.`id_goal` as id_goal',
            'task.`id_task` as id_task',
            'task.`type` as type',
            'Typ.`name` as typeName',
            'task.`status` as status',
            'task.`dateDone` as dateDone',
            'task.`is_auto` as is_auto',
            'task.`plan` as plan',
            'task.`fact` as fact',
            'task.`priority` as priority',
        ])
            ->from(self::tableName().' as task')
            ->join('LEFT JOIN', 'task_type as Typ', 'task.`type` = Typ.`id`');

        $strWhere = 'task.`id_user`= '.(integer)$id_user;
        if($startDate > 0) {
            $strWhere = $strWhere.' AND task.`date` >= '.(integer)$startDate;
        }
        if($finishDate > 0 && $finishDate > $startDate) {
            $strWhere = $strWhere.' AND task.`date` <= '.(integer)$finishDate;
        }
        $strWhere = $strWhere.' AND task.`is_deleted` = 0 ';

        if(isset($option['id_sphere']) === true && $option['id_sphere'] != 0) {
            $strWhere = $strWhere.' AND task.`id_sphere` = '.(integer)$option['id_sphere'];
        }

        if(isset($option['status']) === true && count($option['status']) > 0) {
            $strOr = '';
            $strDiv = '';
            foreach ($option['status'] as $status) {
                $strOr = $strOr.$strDiv.'task.`status` = '.(integer)$status;
                $strDiv = ' OR ';
            }
            $strWhere = $strWhere.' AND ('.$strOr.')';
        }

        if(isset($option['type']) === false) {
            $strWhere = $strWhere.' AND task.`type` = '.(integer)$option['type'];
        }

        $body = $body->where($strWhere)->orderBy('task.`date`, task.`priority`');

        return $body->all();
    }

    public static function getTypes() {
        $query = new Query();
        $body = $query->Select(['type.`id` as id',
            'type.`name` as name',
        ])
            ->from('task_type as type');

        $body = $body->orderBy('type.`id`');

        return $body->all();
    }

}