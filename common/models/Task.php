<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property int $id_doer
 * @property int $id_manager
 * @property int $deadline
 * @property int $finish_date
 * @property int $id_status
 * @property int $id_project
 * @property int $created_at
 * @property int $updated_at
 */
class Task extends \yii\db\ActiveRecord
{
    public $strDeadline;
    public $strFinishDate;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id_doer', 'id_manager', 'id_status', 'id_project', 'created_at', 'updated_at'], 'integer'],
            [['deadline', 'finish_date'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'id_doer' => 'Исполнитель',
            'id_manager' => 'Автор',
            'deadline' => 'Срок выполнения',
            'finish_date' => 'Дата выполнения',
            'id_status' => 'Статус',
            'id_project' => 'Проект',
            'created_at' => 'Создана',
            'updated_at' => 'Изменена',
            'strDeadline' => 'Срок выполенния',
            'strFinishDate' => 'Дата выполнения',
        ];
    }

    public static function getArrayOfUsersForProject($id_project)
    {
        if ((isset($id_project)) && ($id_project > 0)) {
            $columns_array = [
                'user.id as id_user',
                'user.id as userFIO',
            ];

            $query = User::find()->select($columns_array)
                ->leftJoin('user_team as user_team', 'user.id = user_team.id_user')
                ->leftJoin('project_team as project_team', 'user_team.id_team = project_team.id_team AND project_team.id_project = '.$id_project);
            $model = $query->where('NOT project_team.id_team IS NULL')->orderBy('user.surname, user.name, user.middlename')->all();

            $arrUsers = [];
            $modelUser = new User();

            foreach($model as $key => $strUser) {
                $arrUsers[$strUser['id_user']] = $modelUser::getUserFIO($strUser['id_user']);
            }

            return $arrUsers;
        }
        else
        {
            $columns_array = [
                'user.id as id_user',
                'user.id as userFIO',
            ];

            $query = User::find()->select($columns_array);
            $model = $query->orderBy('user.surname, user.name, user.middlename')->all();

            $arrUsers = [];
            $modelUser = new User();

            foreach($model as $key => $strUser) {
                $arrUsers[$strUser['id_user']] = $modelUser::getUserFIO($strUser['id_user']);
            }

            return $arrUsers;
        }
    }

    public static function getArrayOfProjects()
    {
        $columns_array = [
            'project.id as id_project',
            'project.name as name',
        ];

            $query = Project::find()->select($columns_array);
            $model = $query->orderBy('project.name')->all();

            $arrProjects = [];

            foreach($model as $key => $strProject) {
                $arrProjects[$strProject['id_project']] = $strProject['name'];
            }

            return $arrProjects;
    }

    public static function getArrayOfAdmins()
    {
        $columns_array = [
            'user.id as id_user',
            'user.id as userFIO',
        ];

        $query = User::find()->select($columns_array);
        $model = $query->where('user.id_role = 3')->orderBy('user.surname, user.name, user.middlename')->all();

        $arrUsers = [];
        $modelUser = new User();

        foreach($model as $key => $strUser) {
            $arrUsers[$strUser['id_user']] = $modelUser::getUserFIO($strUser['id_user']);
        }

        return $arrUsers;
    }

    public static function getArrayOfStatuses()
    {
        $columns_array = [
            'task_status.id as id',
            'task_status.name as name',
        ];

        $query = TaskStatus::find()->select($columns_array);
        $model = $query->orderBy('task_status.id')->all();

        $arrStatuses = [];

        foreach($model as $key => $strStatus) {
            $arrStatuses[$strStatus['id']] = $strStatus['name'];
        }

        return $arrStatuses;
    }

    public function getDeadline() {
        return $this->deadline? date('d.m.Y', $this->deadline) : '';
    }

    public function setDeadline($date) {
        var_dump(strtotime($date));
        exit();
        $this->deadline= $date ? strtotime($date) : null;
    }


}
