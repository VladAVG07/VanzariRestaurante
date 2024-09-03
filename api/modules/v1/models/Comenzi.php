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
        $fields['status_text'] = function($model){
          //  \yii\helpers\VarDumper::dump($model);
            if (is_null($model->status0)){
                return 'Necunoscut';
            }
            return $model->status0->status0->nume;
        };
        $fields['mod_plata_text'] = function($model){
            if (is_null($model->modPlata0)){
                return 'Necunoscut';
            }
            return $model->modPlata0->nume;
        };
//        $fields['mod_plata'] = function ($model){
//            return $model->modPlata0->nume;
//        };
        $fields['linii_comanda'] = function($model) {
            return ComenziLinii::find()->where(['comanda'=>$model->id])->all();
            
         //   return \yii\helpers\ArrayHelper::toArray($model->comenziLiniis, ['\backend\models\ComenziLinii' =>['nume']]);
           // return \yii\helpers\ArrayHelper::toArray($model->status0, ['\backend\models\ComenziDetalii' =>['comanda','status']]);
         //   return $model->status0;
        };
//        $fields['status'] = function($model) {
//            return $model->status0->status;
//        };
        $fields['restaurant']=function($model){
            if(count($model->produses)>0){
                $produs=$model->produses[0];
                $categorie=$produs->categorie0;
                $restaurant=$categorie->restauranteCategoriis[0]->restaurant0;
                return $restaurant;
            }
            return null;
        };
        return $fields;
    }
}
