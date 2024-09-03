<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Produse;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class RestauranteController extends ActiveController{

    public $modelClass = 'api\modules\v1\models\Restaurante';
    public function actions() {
        $actions = parent::actions();
        unset($actions['create'], $actions['update']);
       // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
}

?>