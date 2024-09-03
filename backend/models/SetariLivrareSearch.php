<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SetariLivrare;

/**
 * SetariLivrareSearch represents the model behind the search form of `backend\models\SetariLivrare`.
 */
class SetariLivrareSearch extends SetariLivrare
{
        public $produsPret; // Add this sorting attribute

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'restaurant', 'produs'], 'integer'],
            [['comanda_minima','pret'], 'number'],
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
        $query = SetariLivrare::find()
                    ->innerJoin('restaurante r', 'r.id = setari_livrare.restaurant')
                    ->innerJoin('restaurante_user ru', 'ru.restaurant = r.id')
                    ->innerJoin('user u', 'ru.user = u.id')
                    ->where(['u.id'=> \Yii::$app->user->id]);
        if(\Yii::$app->user->identity->email==='admin@admin.com'){
             $query = SetariLivrare::find();
                   // ->innerJoin('restaurante r', 'r.id = setari_livrare.restaurant')
                   // ->innerJoin('restaurante_user ru', 'ru.restaurant = r.id')
                    //->innerJoin('user u', 'ru.user = u.id')
                    //->where(['u.id'=> \Yii::$app->user->id]);
        }
       
        $query->joinWith('produs0');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
            'defaultOrder' => ['id' => SORT_DESC], // Set the default sorting order
        ],
            
        ]);

        $dataProvider->sort->attributes['pret'] = [
            'asc' => ['produse.pret_curent' => SORT_ASC],
            'desc' => ['produse.pret_curent' => SORT_DESC],
        ];
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
            'produs' => $this->produs,
            'comanda_minima' => $this->comanda_minima,
            'produse.pret_curent'=>$this->pret
        ]);
        //$query->andFilterWhere(['like', 'produse.pret_curent', $this->pret]);


        return $dataProvider;
    }
}
