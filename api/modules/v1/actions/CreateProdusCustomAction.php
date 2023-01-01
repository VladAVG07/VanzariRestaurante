<?php

namespace api\modules\v1\actions;

use api\modules\v1\models\PreturiProduse;
use api\modules\v1\models\Produse;
use yii\helpers\VarDumper;
use yii\rest\CreateAction;
use yii\web\ServerErrorHttpException;

class CreateProdusCustomAction extends CreateAction
{
    public function run()
    {
        $model = new Produse();
        $model->scenario = $this->scenario;

        if ($model->saveOrUpdateWithPret()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $model->refresh();
//            $id = implode(',', array_values($model->getPrimaryKey(true)));
//            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif ($model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
}