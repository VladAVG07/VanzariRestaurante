<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

class CategoriiController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\Categorii';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class
        ];
        return $behaviors;
    }

    public function actionCategoryProductCount() {
        $result = [];

        // Query to get the category names and the count of products in each category
        $categories = $this->modelClass::find()
                ->select(['categorii.id', 'categorii.nume AS category_name', 'COUNT(produse.id) AS product_count'])
                ->leftJoin('produse', 'categorii.id = produse.categorie')
                ->where(['categorii.valid' => 1])->andWhere(['OR', ['IS', 'produse.id', null], ['produse.valid' => 1]])
                ->groupBy('categorii.id')
                ->asArray()
                ->asArray()
                ->all();

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category['id'],
                'categorie' => $category['category_name'],
                'numar_produse' => (int) $category['product_count'],
            ];
        }

        return $result;
    }

    public function actionMinMaxPrices() {

        //$categoryIds = Yii::$app->request->get('categorii');

        $categoryIds = \Yii::$app->request->get('categorii');
       // var_dump($categoryIds);
        // Ensure $categoryIds is an array
        if (!is_array($categoryIds)) {
            $categoryIds = [];
        }

        // Create a base query to calculate min and max prices for valid products
        $query = \api\modules\v1\models\Produse::find()
                ->select([
                //    'categorie',
                    'MIN(pret_curent) AS min_price',
                    'MAX(pret_curent) AS max_price',
                ])
                ->where(['valid' => 1]);

        // If specific category IDs are provided, filter by those categories
        if (!empty($categoryIds)) {
            $query->andWhere(['IN','categorie',$categoryIds]);
        }

        // Group by category
       // $query->groupBy('categorie');
        
        // Execute the query and get the results
        $results = $query->asArray();
        //$sql = $results->createCommand()->rawSql;
        //var_dump($sql);
        return $results->all();
    }

}
