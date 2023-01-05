<?php

namespace api\modules\v1\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Inflector;
use ReflectionClass;

abstract class BApiController extends \yii\rest\ActiveController {

    private $cName;
    public $serializer;

    public function init() {

        $this->initializeModelClass();
        $this->cName = Inflector::pluralize(strtolower((new ReflectionClass($this->modelClass))->getShortName()));
        $this->serializer = [
            'class' => 'yii\rest\Serializer',
            'collectionEnvelope' => $this->cName
        ];
        parent::init();
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => $this->disableCheckAccessActions(),
            'authMethods' => [
                HttpBearerAuth::className(),
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
        $actions['update'] = [
            'class' => 'api\modules\v1\actions\UpdateCustomAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'scenario' => $this->updateScenario,
        ];
        return $actions;
    }

     protected function verbs()
    {
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

    protected abstract function initializeModelClass();
}
