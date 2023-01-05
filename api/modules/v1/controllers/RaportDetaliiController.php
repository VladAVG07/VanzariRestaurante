<?php

namespace api\modules\v1\controllers;

class RaportDetaliiController extends BasicApiController {

    public $modelClass = 'api\modules\v1\models\RaportDetalii';
    
    protected function disableCheckAccessActions() {
        return [];
    }

//    public function actions() {
//        $actions = parent::actions();
//        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
//        return $actions;
//    }
//
//    public function prepareDataProvider() {
//        $modelClass = $this->modelClass;
//        $queryFrom = $modelClass::find();
//        $queryTo = $modelClass::find();
//        $uuid = \Yii::$app->request->getHeaders()->get('device-uuid');
//        if (!is_null($device = \api\modules\v1\models\Devices::findOne(['uuid' => $uuid]))) {
//            $queryTo->where(['from' => $device->id]);
//            $queryFrom->where(['to'=>$device->id]);
//            $queryInit = \api\modules\v1\models\Messages::find()->from(['d' => $queryFrom->union($queryTo)]);
//            return new ActiveDataProvider([
//                'query' => $queryInit,
//                'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]]
//            ]);
//        }
//        return [];
//    }
    

}
