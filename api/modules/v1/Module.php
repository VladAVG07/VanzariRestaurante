<?php

namespace api\modules\v1;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Module
 *
 * @author Stefan
 */
class Module extends \yii\base\Module{
    public $controllerNamespace = 'api\modules\v1\controllers';
    public function init(){
        parent::init();
        \Yii::$app->user->enableSession=false;
}
}
