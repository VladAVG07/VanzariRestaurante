<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\actions;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Description of CreateCustomAction
 *
 * @author Marian
 */
class CreateCustomAction extends \yii\rest\CreateAction {

    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run() {

//        if ($this->checkAccess) {
//            call_user_func($this->checkAccess, $this->id);
//        }
        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $model->refresh();
//            $id = implode(',', array_values($model->getPrimaryKey(true)));
//            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
}
