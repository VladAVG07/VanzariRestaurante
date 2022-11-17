<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Produse;

/**
 * ProduseSearch represents the model behind the search form of `backend\models\Produse`.
 */
class ProduseSearch extends Produse
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'categorie', 'cod_produs'], 'integer'],
            [['nume', 'descriere', 'data_productie'], 'safe'],
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
        $query = Produse::find();

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
            'categorie' => $this->categorie,
            'cod_produs' => $this->cod_produs,
            'data_productie' => $this->data_productie,
        ]);

        $query->andFilterWhere(['like', 'nume', $this->nume])
            ->andFilterWhere(['like', 'descriere', $this->descriere]);

        return $dataProvider;
    }
}
