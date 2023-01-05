<?php

namespace api\modules\v1\controllers;

use yii\data\ActiveDataProvider;
use SoapClient;

class DosarController extends BasicApiController {

    public $modelClass = 'backend\models\Dosar';

//
    public function actionSaveDosar($numar, $institutie) {
//        $modelClass = $this->modelClass;
//        return new ActiveDataProvider([
//            'query' => $modelClass::find(),
//            'pagination' => ['defaultPageSize' => 20]
//        ]);
        $client = new SoapClient("http://portalquery.just.ro/query.asmx?WSDL", array('soapAction' => 'portalquery.just.ro/CautareDosare2'));
        $params = array(
            "numarDosar" => $numar,
            "institutie" => \backend\models\Institutie::findOne($institutie)->denumire_soap, //"CurteadeApelBUCURESTI"
                //  "arg1" => $desc,
                //  "arg2" => $contactname
        );

        $response = $client->__soapCall('CautareDosare2', array($params));
        $dosare = $response->CautareDosare2Result->Dosar;
        $dosar = new \backend\models\Dosar();
        $dosar->institutie_curenta = intval($institutie);
        \Yii::$app->response->statusText = "Nu au fost gasite date";
        if (!is_null($dosare)) {
            $datas = explode("/", $dosare->numar);
            $dosar->numar = $datas[0];
            $dosar->institutie_id = intval($datas[1]);
            $dosar->an = intval($datas[2]);
            $dosar->rest = null;
            if (count($datas) > 3) {
                $dosar->rest = $datas[4];
            }
            $dosar->categorie = \backend\models\CategorieCaz::findOne(['categorie_caz_no_space' => $dosare->categorieCaz])->id;
            $dosar->stadiu = \backend\models\StadiuProcesual::findOne(['stadiu_no_space' => $dosare->stadiuProcesual])->id;
            $dataUltimaModificare = $dosare->dataModificare;
            $dataAdaugare = $dosare->data;
            $dosar->data_ultima_modificare = date('Y-m-d H:i:s', strtotime($dataUltimaModificare));
            $dosar->data_inregistrare = date('Y-m-d H:i:s', strtotime($dataAdaugare));
            $dosar->departament = $dosare->departament;
            $dosar->obiect = $dosare->obiect;
            if ($dosar->save()) {
                \Yii::$app->response->statusText = "Dosar adaugat cu succes";
            } else {
                return $dosar;
            }
        }
        return [];
//        foreach ($dosare as $dosar) {
//            $institutie = $dosar->institutie;
//            $numarDosar = $dosar->numar;
//            $dataUltimaModificare = $dosar->dataModificare;
//            $seconds = strtotime($dataUltimaModificare);
//            echo $numarDosar . " " . $institutie . " " . $dataUltimaModificare ." - >".date('Y-m-d H:i:s', $seconds). "<br/>";
//        }
    }

    public function actions() {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider() {
        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        $query->innerJoin('dosare_utilizatori', 'dosar.id=dosare_utilizatori.dosar');
        $query->where(['dosare_utilizatori.utilizator' => \Yii::$app->user->id]);
        return $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
