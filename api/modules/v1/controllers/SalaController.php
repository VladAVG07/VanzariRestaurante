<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

/**
 * Description of SaliController
 *
 * @author Marian
 */
class SalaController extends BasicApiController{
    //put your code here
     public $modelClass = 'api\modules\v1\models\Sali';
      protected function disableCheckAccessActions() {
        return ['index'];
    }
     
     public function actionTest(){
         return [];
     }
}
