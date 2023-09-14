<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Persoane;

/**
 * PersoaneSearch represents the model behind the search form of `backend\models\Persoane`.
 */
class PersoaneSearch extends Persoane
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numar_identificare'], 'integer'],
            [['nume', 'prenume'], 'safe'],
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
        $query = Persoane::find()
                ->innerJoin('functii_persoane fp','fp.persoana = persoane.id')
                ->innerJoin('functii f', 'fp.functie = f.id')
                ->innerJoin('restaurante_functii rf','rf.functie = f.id')
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
            'numar_identificare' => $this->numar_identificare,
        ]);

        $query->andFilterWhere(['like', 'nume', $this->nume])
            ->andFilterWhere(['like', 'prenume', $this->prenume]);

        return $dataProvider;
    }
}
