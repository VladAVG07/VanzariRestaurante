<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Stocuri;

/**
 * StocuriSearch represents the model behind the search form of `backend\models\Stocuri`.
 */
class StocuriSearch extends Stocuri
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'produs', 'cantitate'], 'integer'],
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
        $query = Stocuri::find()
                ->select(['stocuri.id','produs',new \yii\db\Expression('SUM(cantitate_ramasa) AS cantitate_ramasa')])
                ->innerJoin('produse p', 'stocuri.produs = p.id')
                ->innerJoin('categorii c', 'p.categorie = c.id')
                ->innerJoin('restaurante_categorii rc', 'rc.categorie = c.id')
                ->innerJoin('restaurante r', 'rc.restaurant = r.id')
                ->innerJoin('restaurante_user ru', 'ru.restaurant = r.id')
                ->innerJoin('user u', 'ru.user = u.id')
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
            'produs' => $this->produs,
            'cantitate' => $this->cantitate_ramasa,
        ]);
        $query->groupBy(['produs']);

        return $dataProvider;
    }
}
