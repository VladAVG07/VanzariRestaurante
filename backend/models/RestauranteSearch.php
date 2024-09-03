<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Restaurante;

/**
 * RestauranteSearch represents the model behind the search form of `backend\models\Restaurante`.
 */
class RestauranteSearch extends Restaurante
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nume', 'cui', 'adresa', 'numar_telefon'], 'safe'],
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
        $query = Restaurante::find();

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
        ]);

        $query->andFilterWhere(['like', 'nume', $this->nume])
            ->andFilterWhere(['like', 'cui', $this->cui])
            ->andFilterWhere(['like', 'adresa', $this->adresa])
            ->andFilterWhere(['like', 'numar_telefon', $this->numar_telefon]);

        return $dataProvider;
    }
}
