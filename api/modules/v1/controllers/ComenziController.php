<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

/**
 * Description of ComenziController
 *
 * @author vladg
 */
use api\modules\v1\models\Comenzi;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ComenziController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\Comenzi';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className()
        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['create'], $actions['index']);
        return $actions;
    }

    public function actionCreate() {
        $model = new Comenzi();
        $produseComanda = Yii::$app->request->post('produse');
        foreach ($produseComanda as $produs){
            if (\backend\models\Produse::findOne(['id' => $produs['id']])->stocabil){
                if (\backend\models\Stocuri::find()->where(['produs' => $produs['id']])->sum('cantitate_ramasa') < $produs['cantitate']){
                    $model->addError('eroare', 'Nu exista suficiente produse pe stoc');
                    return $model;
                }
            }
//            if (!$produs->disponibil){
//                $model->addError('eroare', 'In comanda exista cel putin un produs indisponibil');
//                return $model;
//            }
        }
        if ($model->saveComanda(Yii::$app->request->post())) {
            $model->refresh();
            return $model;
        }
        //  $model->addError('Eroare');
        return $model;
    }

    public function actionIndex() {
        $query = Comenzi::find()->where(['utilizator' => Yii::$app->user->id]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
                'pageSizeLimit' => [0, 50],
            ]
        ]);
    }

    public function actionIstoricComenzi($telefon){
        return \backend\models\Comenzi::getComenzi($telefon);
    }
    
    public function actionChangeStatus($id) {
        $model = Comenzi::findOne($id);
        if ($model->status == 8){
            $model->addError('eroare', 'Comanda este anulata!');
            return $model;
        }
        if ($model->changeStatus($id)) {
            $model->refresh();
            return $model;
        }
        $model->addError('Eroare');
        return $model;
    }

    public function actionChangeMetodaPlata($id) {
        $model = Comenzi::findOne($id);
        $metodaPlata = \backend\models\ModuriPlata::findOne(Yii::$app->request->post('id_mod_plata'));
        if (is_null($metodaPlata)) {
            $model->addError('eroare', 'Modul de plata trebuie sa fie in intervalul [1,2]');
            return $model;
        }
        if ($model->changeMetodaPlata($id)) {
            $model->refresh();
            return $model;
        }
        $model->addError('eroare', 'Eroare nespecificata');
        return $model;
    }
    
    public function actionAdaugareProdusComanda($id){
        $model = Comenzi::findOne($id);
        if ($model->status0->status0->id!=3){
            $model->addError('eroare', 'Comanda este deja in curs de pregatire');
            return $model;
        }
        $id_produs = Yii::$app->request->post('id_produs');
        $cantitate = Yii::$app->request->post('cantitate');
        if (\backend\models\Produse::findOne(['id' => $id_produs])->stocabil){
            if (\backend\models\Stocuri::find()->where(['produs' => $id_produs])->sum('cantitate_ramasa') < $cantitate){
                $model->addError('eroare', 'Nu sunt suficiente produse pe stoc');
                return $model;
            }
        }
        if ($model->adaugareProdusComanda($id)){
            $model->refresh();
            return $model;
        }
        $model->addError('eroare', 'Eroare nespecificata');
        return $model;
    }
    
    public function actionStergereProdusComanda($id){
        $model = Comenzi::findOne($id);
        if ($model->status0->status0->id!=3){
            $model->addError('eroare', 'Comanda este deja in curs de pregatire');
            return $model;
        }
        $id_produs = Yii::$app->request->post('id_produs');
        $cantitate = Yii::$app->request->post('cantitate');
        if (is_null(\backend\models\ComenziLinii::findOne(['comanda' => $id, 'produs' => $id_produs]))){
            $model->addError('eroare', 'Comanda nu contine acest produs');
            return $model;
        }
        if ($cantitate> \backend\models\ComenziLinii::findOne(['comanda' => $id, 'produs' => $id_produs])->cantitate){
            $model->addError('eroare', 'Comanda contine o cantitate mai mica din acest produs');
            return $model;
        }
        if ($model->stergereProdusComanda($id)){
            $model->refresh();
            return $model;
        }
        $model->addError('eroare', 'Eroare nespecificata');
        return $model;
    }
}
