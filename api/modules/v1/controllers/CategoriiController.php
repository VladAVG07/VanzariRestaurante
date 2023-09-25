<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

class CategoriiController extends ActiveController
{
    public $modelClass='api\modules\v1\models\Categorii';
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class
        ];
        return $behaviors;
    }
}