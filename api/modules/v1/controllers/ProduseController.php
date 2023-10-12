<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Produse;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ProduseController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\Produse';

    public function actions() {
        $actions = parent::actions();
        unset($actions['create'], $actions['update']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
public function actionIndex() {
    $searchModel = new ProduseSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return [
        'items' => $dataProvider->getModels(),
        'total_pages' => $dataProvider->getPagination()->getPageCount(),
        'current_page' => $dataProvider->getPagination()->getPage() + 1,
        'items_per_page' => $dataProvider->getPagination()->getPageSize(),
    ];
}
   /* public function prepareDataProvider() {
        //$modelClass = $this->modelClass;
//        $query = $modelClass::find();
//        $query->innerJoin('dosare_utilizatori', 'dosar.id=dosare_utilizatori.dosar');
//        $query->where(['disponibil' => 1]);

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

       // $results = $query->all();

        return $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
    }*/
   public function prepareDataProvider() {
    $pageSize = Yii::$app->request->get('per-page', 10); // Number of elements per page
    $filterProperty = Yii::$app->request->get('filter-property', null); // Property filter value
    $filterValue = Yii::$app->request->get('filter-value', null); // Property filter value
    $page = Yii::$app->request->get('page', 1); // Page number, default to 1 if not specified

    $query = (new \yii\db\Query())
        ->select([
            'p.*',
            'SUM(s.cantitate_ramasa) AS cantitate'
        ])
        ->from('produse p')
        ->leftJoin('stocuri s', 'p.id = s.produs')
        ->where(['p.disponibil' => 1]);

    // Apply property filter if provided
    if ($filterProperty !== null && $filterValue !== null) {
        $query->andWhere([$filterProperty => $filterValue]);
    }

    $query->groupBy('p.id')
        ->having(['OR', 'cantitate > 0', 'p.stocabil = 0']);

    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => $pageSize,
            'page' => $page - 1, // Convert to 0-based page index
        ],
    ]);

    return [
        'items' => $dataProvider->getModels(),
        'total_pages' => $dataProvider->getPagination()->getPageCount(),
        'current_page' => $dataProvider->getPagination()->getPage() + 1, // Yii pagination is 0-based
        'items_per_page' => $dataProvider->getPagination()->getPageSize(),
    ];
}




    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className()
        ];
        return $behaviors;
    }

    public function actionCreate() {
        $model = new Produse();
        if ($model->saveOrUpdateWithPret(Yii::$app->request->post(), '')) {
            $model->refresh();
            return $model;
        }
        return 'Error creating the product';
    }

    public function actionUpdate($id) {
        $model = Produse::findOne($id);
        if ($model !== null && $model->saveOrUpdateWithPret(Yii::$app->request->post(), '')) {
            $model->refresh();
            return $model;
        }
        return 'error';
    }
    
    public function actionVerificaStoc($id) {
        $produs= Produse::findOne($id);
       // var_dump($produs);
        if ($produs && $produs->stocabil) {
            $query = \backend\models\Stocuri::find()
                    ->select(['stocuri.id', 'produs', new \yii\db\Expression('SUM(cantitate_ramasa) AS cantitate_ramasa')])
                    ->innerJoin('produse p', 'stocuri.produs = p.id')
                    ->where(['p.id' => $produs->id]);
            $stocRamas = $query->one()->cantitate_ramasa;
            return ['stoc'=>is_null($stocRamas)?0:$stocRamas];
        }
        $m=new \yii\base\Model();
        $m->addError('error','Produsul nu este stocabil');
        return $m;
    }
    
    public function actionProdusSesiune(){
        //luam prin post id user, produs, cantitate
        //fac ce a zis chat gpt
        //update la partea din dreapta
        $idUser = \Yii::$app->request->post('id');
        $idProdus = \Yii::$app->request->post('produs');
        $cantitate = \Yii::$app->request->post('cantitate');
        $result = Yii::$app->db->createCommand('SELECT VerificaSiGestioneazaSesiuneProdus(:user_id, :produs_id, :cantitate) as result')
            ->bindValue(':user_id', $idUser)
            ->bindValue(':produs_id', $idProdus)
            ->bindValue(':cantitate', $cantitate)
            ->queryOne();
        return $result;
    }
}
