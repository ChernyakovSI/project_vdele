<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Task;

/**
 * TaskSearch represents the model behind the search form of `common\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_doer', 'id_manager', 'deadline', 'finish_date', 'id_status', 'id_project', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $settings = 0)
    {
        if ($settings == 1) {
            $now = idate('U');
            $now = $now - 60*60*24*7;
            $query = Task::find()->where('finish_date > '.$now.' AND id_status = 3');
        }
        else if ($settings == 2) {
            $now = idate('U');
            $query = Task::find()->where('deadline < '.$now.' AND id_status = 2');
        }
        else {
            $query = Task::find();
        }


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
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

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
