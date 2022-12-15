<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

/**
 * Description of AntrenoriController
 *
 * @author Marian
 */
class SportiviController extends BApiController {

    //put your code here
    protected function initializeModelClass() {
        $this->modelClass = '\api\modules\v1\models\Sportivi';
    }

    protected function disableCheckAccessActions() {
        return ['index'];
    }

    public function checkAccess($action, $model = null, $params = array()) {
        if ($action === 'view' || $action === 'update' || $action === 'delete') {
            /* @var $model \backend\models\Dosar */
            if ($model->profil0->user !== \Yii::$app->user->id) {
                throw new \yii\web\ForbiddenHttpException('We record all hacking attempts');
            }
        }
    }

}
