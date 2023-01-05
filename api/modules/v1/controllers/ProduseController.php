<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Produse;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ProduseController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Produse';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'] , /*$actions['index'],*/ $actions['view'], $actions['update']);
        return $actions;
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className()   
        ]; 
        return $behaviors;
    }

//    public function actionIndex() {
//        $items = [];
//        $produse = Produse::find()->all();
//        foreach ($produse as $produs) {
//            $items[] = [
//                'produs' => $produs,
//                'pret' => $produs->getPretCurent()->pret,
//                'istoric_preturi' => $produs->preturiProduses,
//            ];
//        }
//        return $items;
//    }

    public function actionCreate()
    {
        $model = new Produse();
        if($model->saveOrUpdateWithPret(Yii::$app->request->post(), '')) {
            $model->refresh();
            return [
                'produs' => $model,
                'pret' => $model->getPretCurent()->pret,
                'istoric_preturi' => $model->preturiProduses,
            ];
        }
        return 'Error creating the product';
    }


    public function actionUpdate($id) {
        $model = Produse::findOne($id);
        if($model !== null && $model->saveOrUpdateWithPret(Yii::$app->request->post(), '')) {
            $model->refresh();
            return [
                'produs' => $model,
                'pret' => $model->getPretCurent()->pret,
                'istoric_preturi' => $model->preturiProduses,
            ];
        }
        return 'error';
    }

    public function actionView($id) {
        $model = Produse::findOne($id);
        if($model !== null) {
            return [
                'produs' => $model,
                'pret' => $model->getPretCurent()->pret,
                'istoric_preturi' => $model->preturiProduses,
            ];
        }
        return 'Product with id '.$id.' does not exist';
    }

}