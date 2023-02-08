<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

/**
 * Description of ComenziController
 *
 * @author vladg
 */
use api\modules\v1\models\Comenzi;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ComenziController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\Comenzi';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className()
        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['create'], $actions['index']);
        return $actions;
    }

    public function actionCreate() {
        $model = new Comenzi();
        if ($model->saveComanda(Yii::$app->request->post())) {
            $model->refresh();
            return $model;
        }
      //  $model->addError('Eroare');
        return $model;
    }

    public function actionIndex() {
        $query = Comenzi::find()->where(['utilizator' => Yii::$app->user->id]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
                'pageSizeLimit' => [0, 50],
            ]
        ]);
    }
    
    public function actionChangeStatus($id) {
        $model = new Comenzi();
        if($model->changeStatus($id)) {
            $model->refresh();
            return $model;
        }
        $model->addError('Eroare');
        return $model;
    }

}
