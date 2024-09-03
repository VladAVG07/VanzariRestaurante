<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\models;

use backend\models\ComenziLinii as CL;

/**
 * Description of ComenziLinii
 *
 * @author vladg
 */
class ComenziLinii  extends CL{
    //put your code here
    public function fields() {
        $fields = parent::fields();
        $fields['nume_produs'] = function($model) {
            return $model->produs0->nume;
        };
        unset($fields['id'], $fields['comanda'], $fields['produs']);
        return $fields;
    }
}
