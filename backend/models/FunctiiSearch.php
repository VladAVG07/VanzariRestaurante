<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Functii;

/**
 * FunctiiSearch represents the model behind the search form of `backend\models\Functii`.
 */
class FunctiiSearch extends Functii
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nume', 'data_inceput', 'data_sfarsit'], 'safe'],
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
    public function search($params)
    {
        $query = Functii::find()
                ->innerJoin('restaurante_functii rf','rf.functie = functii.id')
                ->innerJoin('restaurante r','rf.restaurant = r.id')
                ->innerJoin('restaurante_user ru','ru.restaurant = r.id')
                ->innerJoin('user u','ru.user = u.id')
                ->where(['u.id' => \Yii::$app->user->id]);
                

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
            'data_inceput' => $this->data_inceput,
            'data_sfarsit' => $this->data_sfarsit,
        ]);

        $query->andFilterWhere(['like', 'nume', $this->nume]);

        return $dataProvider;
    }
}
