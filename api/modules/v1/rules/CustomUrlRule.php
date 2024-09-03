<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace api\modules\v1\rules;
/**
 * Description of CustomUrlRule
 *
 * @author Marian
 */
class CustomUrlRule extends \yii\rest\UrlRule{
    //put your code here
    
    public function init() {
        $this->pluralize=false;
        $this->patterns=[
                'POST {id}'=>'update',
                'DELETE {id}'=>'delete',
                'GET,HEAD {id}'=>'view',
                'POST'=>'create',
                'GET,HEAD'=>'index',
                '{id}'=>'options',
                ''=>'options'
            ];
        parent::init();
    }
}
