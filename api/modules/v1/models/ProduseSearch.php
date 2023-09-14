<?php
namespace api\modules\v1\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProduseSearch extends Model
{
    public function search($params)
    {
        $query = (new \yii\db\Query())
            ->select([
                'p.*',
                'SUM(s.cantitate_ramasa) AS cantitate'
            ])
            ->from('produse p')
            ->leftJoin('stocuri s', 'p.id = s.produs')
            ->where(['p.disponibil' => 1])
            ->groupBy('p.id')
            ->having(['OR', 'cantitate > 0', 'p.stocabil = 0']);

        // Custom filtering logic based on query parameters
        if (isset($params['custom_filter'])) {
            // Implement your custom filtering logic here
            // For example: $query->andWhere(['custom_column' => $params['custom_filter']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per-page', 10),
            ],
        ]);

        return $dataProvider;
    }
}

