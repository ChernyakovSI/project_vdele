<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 09.05.2019
 * Time: 20:18
 */

namespace common\models;

use common\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public function search($params, $id_user, $settings = 0)
    {
        $query = User::find()->where('id = '.$id_user);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        /*if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_doer' => $this->id_doer,
            'id_manager' => $this->id_manager,
            'deadline' => $this->deadline,
            'finish_date' => $this->finish_date,
            'id_status' => $this->id_status,
            'id_project' => $this->id_project,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);*/

        return $dataProvider;
    }
}