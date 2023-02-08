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
        return $actions;
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
