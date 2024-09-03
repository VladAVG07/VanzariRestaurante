<?php

namespace api\modules\v1\controllers;

use yii\filters\auth\CompositeAuth;
use api\modules\v1\filters\CustomKeyAuth;

class BasicApiController extends \yii\rest\ActiveController {

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except'=>  $this->disableCheckAccessActions(),
            'authMethods' => [
                CustomKeyAuth::className(),
            ],
        ];

        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => ['application/json' => \yii\web\Response::FORMAT_JSON]
        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        $actions['create'] = [
            'class' => 'api\modules\v1\actions\CreateCustomAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'scenario' => $this->createScenario,
        ];
        return $actions;
    }

    protected function verbs() {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    protected function disableCheckAccessActions() {
        return [];
    }

}
