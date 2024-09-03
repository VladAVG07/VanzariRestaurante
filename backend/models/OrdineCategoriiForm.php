<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\models;

/**
 * Description of OrdineCategoriiForm
 *
 * @author Stefan
 */
class OrdineCategoriiForm extends \yii\base\Model{
    public $ordineCategorii;
    
    
    public function rules() {
        return [
          ['ordineCategorii','required']  
        ];
    }
    
    
    
}
