<?php

namespace api\modules\v1\models;

use backend\models\Produse as PR;
class Produse extends PR
{
    public function fields() {
        $fields=parent::fields();
        $fields['categorie_text']=function($model){
            return $model->categorie0->nume;
        };
        $fields['categorie_parinte_id'] = function($model) {
            return $model->categorie0->parinte0 == null ? 'Nu exista' : $model->categorie0->parinte0->id;
        };
        $fields['categorie_parinte_nume'] = function($model) {
            return $model->categorie0->parinte0 == null ? 'Nu exista' : $model->categorie0->parinte0->nume;
        };
        return $fields;
    }
}