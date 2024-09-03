<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

/**
 * Description of LocalitatiController
 *
 * @author Marian
 */
class LocalitatiController extends BApiController {

    protected function initializeModelClass() {
        $this->modelClass = '\api\modules\v1\models\Localitati';
    }

    protected function disableCheckAccessActions() {
        return ['index'];
    }

    public function prepareDataProvider() {
        $searchModel = new \api\modules\v1\models\LocalitatiSearch();
        return $searchModel->search(\Yii::$app->request->queryParams);
    }

    public function actions() {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['index']['dataFilter'] = [
            'class' => \yii\data\ActiveDataFilter::class,
            //'attributeMap' => \api\modules\v1\models\LocalitatiSearch::getAttributeMap(),
            'searchModel' => \api\modules\v1\models\LocalitatiSearch::class,
            /* 'searchModel' => function () {
              return (new \yii\base\DynamicModel(['id' => null, 'nume' => null, 'judet' => null]))
                  ->addRule('id', 'integer')
                  ->addRule('nume', 'trim')
                  ->addRule('nume', 'string')
                  ->addRule('judet', 'integer');
          },*/
        ];
        return $actions;
    }

}
