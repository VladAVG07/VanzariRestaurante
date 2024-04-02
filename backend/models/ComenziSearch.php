<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Comenzi;

/**
 * ComenziSearch represents the model behind the search form of `backend\models\Comenzi`.
 */
class ComenziSearch extends Comenzi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numar_comanda', 'utilizator', 'status', 'mod_plata'], 'integer'],
            [['data_ora_creare', 'data_ora_finalizare', 'mentiuni', 'canal'], 'safe'],
            [['pret', 'tva'], 'number'],
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
        $restaurant= RestauranteUser::find()->where(['user'=> \Yii::$app->user->id])->one();
        $query = Comenzi::find()
                ->innerJoin('user u','comenzi.utilizator = u.id')
                ->innerJoin('restaurante_user ru', 'ru.user=u.id')
                ->where(['ru.restaurant'=>$restaurant->restaurant]);

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
            'numar_comanda' => $this->numar_comanda,
            'utilizator' => $this->utilizator,
            'status' => $this->status,
            'data_ora_creare' => $this->data_ora_creare,
            'data_ora_finalizare' => $this->data_ora_finalizare,
            'pret' => $this->pret,
            'tva' => $this->tva,
            'mod_plata' => $this->mod_plata,
        ]);

        $query->andFilterWhere(['like', 'mentiuni', $this->mentiuni])
            ->andFilterWhere(['like', 'canal', $this->canal]);

        $query->orderBy(['data_ora_creare' => SORT_DESC]);
        
        return $dataProvider;
    }
}
