<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

class ProduseController extends ActiveController
{
    public $modelClass = 'api/modules/v1/models/Produse.php';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }
}