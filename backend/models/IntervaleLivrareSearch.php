<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IntervaleLivrare;

/**
 * IntervaleLivrareSearch represents the model behind the search form of `backend\models\IntervaleLivrare`.
 */
class IntervaleLivrareSearch extends IntervaleLivrare
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'restaurant', 'ziua_saptamanii'], 'integer'],
            [['ora_inceput', 'ora_sfarsit'], 'safe'],
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
        $query = IntervaleLivrare::find();

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
            'restaurant' => $this->restaurant,
            'ziua_saptamanii' => $this->ziua_saptamanii,
        ]);

        $query->andFilterWhere(['like', 'ora_inceput', $this->ora_inceput])
            ->andFilterWhere(['like', 'ora_sfarsit', $this->ora_sfarsit]);

        return $dataProvider;
    }
}
