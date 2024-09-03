<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

/**
 * Description of IstoricAntrenamentController
 *
 * @author stefa
 */
class IstoricAntrenamentController extends BApiController {

    protected function initializeModelClass() {
        $this->modelClass = '\api\modules\v1\models\IstoricAntrenament';
    }

    public function checkAccess($action, $model = null, $params = array()) {
        if ($action === 'update' || $action === 'delete') {
            /* @var $model \api\modules\v1\models\IstoricAntrenament */
            if (is_null(\backend\models\AntrenoriSportivi::findOne(['antrenor' => \Yii::$app->user->identity->profil->id,
                                'sportiv' => $model->sportiv_id]))) {
                throw new \yii\web\ForbiddenHttpException('We record all hacking attempts');
            }
        } else if ($action === 'view') {
            $rol = \Yii::$app->user->identity->getRol()->one()->name;
            if ($rol === 'antrenor' && $model->antrenor_id !== \Yii::$app->user->identity->profil->id) {
                throw new \yii\web\ForbiddenHttpException('We record all hacking attempts');
            } else if ($rol === 'sportiv' && $model->sportiv_id !== \Yii::$app->user->identity->profil->id) {
                throw new \yii\web\ForbiddenHttpException('We record all hacking attempts');
            }
        }
    }

    public function actions() {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider() {
        $searchModel = new \backend\models\IstoricAntrenamentSearch();
        $rol = \Yii::$app->user->identity->getRol()->one()->name;
        if ($rol === 'sportiv') {
            $searchModel->sportiv_id = \Yii::$app->user->identity->profil->id;
        } else if ($rol === 'antrenor') {
            $searchModel->antrenor_id = \Yii::$app->user->identity->profil->id;
        }
        return $searchModel->search(\Yii::$app->request->queryParams);
    }

}
