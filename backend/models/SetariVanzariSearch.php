<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SetariVanzari;

/**
 * SetariVanzariSearch represents the model behind the search form of `backend\models\SetariVanzari`.
 */
class SetariVanzariSearch extends SetariVanzari
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'restaurant', 'vanzari_oprite'], 'integer'],
            [['mesaj_oprit', 'mesaj_generic'], 'safe'],
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
        $query = SetariVanzari::find();

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
            'vanzari_oprite' => $this->vanzari_oprite,
        ]);

        $query->andFilterWhere(['like', 'mesaj_oprit', $this->mesaj_oprit])
            ->andFilterWhere(['like', 'mesaj_generic', $this->mesaj_generic]);

        return $dataProvider;
    }
}
