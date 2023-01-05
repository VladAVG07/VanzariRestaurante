<?php

namespace api\modules\v1\controllers;


class ConfigController extends BApiController {


    public function actions() {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider() {
        $modelClass = new $this->modelClass;
        return $modelClass;
    }

    protected function initializeModelClass() {
        $this->modelClass='api\modules\v1\models\Config';
    }
    
    protected function disableCheckAccessActions() {
        return ['index'];
    }

}
