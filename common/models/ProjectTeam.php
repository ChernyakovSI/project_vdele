<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project_team".
 *
 * @property int $id
 * @property int $id_team
 * @property int $id_project
 * @property int $created_at
 * @property int $updated_at
 */
class ProjectTeam extends \yii\db\ActiveRecord
{
    public $team;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_team', 'id_project', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_team' => 'Команда',
            'id_project' => 'Проект',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'team' => 'Команда',
        ];
    }

    public static function deleteTeamFromProject($id_team, $id_project)
    {
        if ((isset($id_team)) && ($id_team > 0) && isset($id_project) && ($id_project > 0)) {
            $query = ProjectTeam::find();
            $model = $query->where(['project_team.id_project' => $id_project,
                'project_team.id_team' => $id_team])->all();

            foreach ($model as $key => $strTeam) {
                $strTeam->delete();
            }

            return true;
        }
    }

    public static function getTeamsForProject($id_project)
    {
        if ((isset($id_project)) && ($id_project > 0)) {
            $columns_array = [
                'team.id as id_team',
                'team.name as team',
            ];

            $query = Team::find()->select($columns_array)->leftJoin('project_team as project_team', 'team.id = project_team.id_team AND project_team.id_project = '.$id_project);
            $model = $query->where('project_team.id_team IS NULL')->orderBy('team.name')->all();

            $arrTeams = [];

            foreach ($model as $key => $strTeam) {
                $arrTeams[$strTeam['id_team']] = $strTeam['team'];
            }

            return $arrTeams;
        }
    }
}
