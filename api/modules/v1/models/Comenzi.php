<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\models;

use backend\models\Comenzi as C;

/**
 * Description of Comenzi
 *
 * @author vladg
 */
class Comenzi extends C{
    //put your code here
    public function fields() {
        $fields = parent::fields();
        $fields['produse'] = function($model) {
            return $model->comenziLiniis;
        };
//        $fields['status'] = function($model) {
//            return $model->status0->status;
//        };
        return $fields;
    }
}
