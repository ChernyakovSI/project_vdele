<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_team".
 *
 * @property int $id
 * @property int $id_team
 * @property int $id_user
 * @property int $created_at
 * @property int $updated_at
 */
class UserTeam extends \yii\db\ActiveRecord
{
    public $userFIO;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_team', 'id_user', 'created_at', 'updated_at'], 'integer'],
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
            'id_user' => 'Участник',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
        ];
    }

    public static function getUsersByTeam($id_team)
    {
        if ((isset($id_team)) && ($id_team > 0)) {
            $columns_array = [
                'user_team.id_user as id_user',
                'user_team.id_user as userFIO',
            ];

            $query = UserTeam::find()->select($columns_array);
            $model = $query->where('user_team.id_team = '.$id_team)->all();

            $userModel = new User();
            $arrUsers = [];

            foreach($model as $key => $strUser){
                $newKey = $key+1;
                $arrUsers[$newKey]['id_user'] = $strUser['id_user'];
                $arrUsers[$newKey]['userFIO'] = $userModel->getUserFIO($strUser['id_user']);
            }


            foreach($arrUsers as $key => $strUser){
                $newKey = $key-1;
                $model[$newKey]['id_user'] = $strUser['id_user'];
                $model[$newKey]['userFIO'] = $strUser['userFIO'];
            }

            return $model;

        }
    }

    public static function getUsersForTeam($id_team)
    {
        if ((isset($id_team)) && ($id_team > 0)) {
            $columns_array = [
                'user.id as id_user',
                'user.id as userFIO',
            ];

            $query = User::find()->select($columns_array)->leftJoin('user_team as user_team', 'user.id = user_team.id_user AND user_team.id_team = '.$id_team);
            $model = $query->where('user_team.id_user IS NULL')->all();

            $userModel = new User();
            $arrUsers = [];

            foreach ($model as $key => $strUser) {
                $arrUsers[$strUser['id_user']] = $userModel->getUserFIO($strUser['id_user']);
            }

            return $arrUsers;
        }
    }

    public static function deleteUserFromTeam($id_user, $id_team)
    {
        if ((isset($id_team)) && ($id_team > 0) && isset($id_user) && ($id_user > 0)) {
            $query = UserTeam::find();
            $model = $query->where(['user_team.id_team' => $id_team,
                                    'user_team.id_user' => $id_user])->all();
            //$model->delete();

            foreach ($model as $key => $strUser) {
                $strUser->delete();
            }

            return true;
        }
    }


}
