<?php

namespace api\modules\v1\models;

use backend\models\Restaurante as RT;
use yii\helpers\Url;

class Restaurante extends RT
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['tip_restaurant'] = function ($model) {
            return $model->tipRestaurant;
        };
        $fields['setari_livrare'] = function ($model) {
            $setari=$model->setariLivrare;
            unset($setari->restaurant,$setari->id,$setari->produs);
            return $setari;
        };
        $fields['categorii']=function($model){
            return $model->categorii;
        };
        $fields['poza_prezentare'] = function ($model) {
            if (!is_null($model->poza_prezentare)) {
                $imageUrl = Url::to('@backend/' . $model->poza_prezentare, true);
                return $imageUrl;
            }
            return null;
        };
        // $fields['categorie_text']=function($model){
        //     return $model->categorie0->nume;
        // };
        // $fields['categorie_parinte_id'] = function($model) {
        //     return $model->categorie0->parinte0 == null ? 'Nu exista' : $model->categorie0->parinte0->id;
        // };
        // $fields['categorie_parinte_nume'] = function($model) {
        //     return $model->categorie0->parinte0 == null ? 'Nu exista' : $model->categorie0->parinte0->nume;
        // };
        return $fields;
    }
}
