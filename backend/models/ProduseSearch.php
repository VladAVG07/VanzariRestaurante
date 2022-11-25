<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Produse;

/**
 * ProduseSearch represents the model behind the search form of `backend\models\Produse`.
 */
class ProduseSearch extends Produse {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pret'], 'string'],
            ['pret','match','pattern'=>'/(=|[<>]=?|<>)\s?\d+/'],
            [['id', 'categorie', 'cod_produs'], 'integer'],
            [['nume', 'descriere', 'data_productie'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Produse::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['categorie'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with “tbl_”
            'asc' => ['categorii.nume' => SORT_ASC],
            'desc' => ['categorii.nume' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['pret'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with “tbl_”
            'asc' => ['preturi_produse.pret' => SORT_ASC],
            'desc' => ['preturi_produse.pret' => SORT_DESC],
        ];

        $this->load($params);
        $query->joinWith('categorie0');
//        $query->joinWith('preturiProduses');
        $query->innerJoin('preturi_produse', 'produse.id=preturi_produse.produs');
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
            // 'pret' => $this->pret,
            'preturi_produse.valid' => 1
        ]);
        $query->andWhere(['preturi_produse.valid' => 1]);
        $query->andFilterWhere(['like', 'nume', $this->nume])
                ->andFilterWhere(['like', 'descriere', $this->descriere])
                ->andFilterCompare('pret', $this->pret);

        return $dataProvider;
    }

}
