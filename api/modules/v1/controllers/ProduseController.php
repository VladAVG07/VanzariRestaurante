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

    public function prepareDataProvider() {
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

}
