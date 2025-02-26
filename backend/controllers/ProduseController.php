<?php

namespace backend\controllers;

use backend\models\PreturiProduse;
use backend\models\ProdusDetaliuForm;
use Yii;
use backend\models\Produse;
use backend\models\ProduseDetalii;
use backend\models\ProduseSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * ProduseController implements the CRUD actions for Produse model.
 */
class ProduseController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Produse models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProduseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    //    public function actionUpload() {
    //        $model = new UploadForm();
    //
    //        if (Yii::$app->request->isPost) {
    //            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
    //            if ($model->upload()) {
    //                return;
    //            }
    //        }
    //
    //        return $this->render('upload', ['model' => $model]);
    //    }

    public function actionCreate() {
        $postData = Yii::$app->request->post();
        $model = new Produse();
        $model->stocabil = 0;
        $model->ordine = 0;
        $model->disponibil = 1;
        $model->tip_produs = 1;
        $model->produse_detalii[] = new ProduseDetalii(['disponibil' => 1]);
        //  $model->produse_detalii[]=new ProdusDetaliuForm(['disponibil'=>false]);
        //  VarDumper::dump(Yii::$app->request->post());
        //exit();
        if ($model->load($postData)) {

            $details = [];
            foreach ($postData['ProduseDetalii'] as $detailData) {
                $detailModel = new ProduseDetalii();
                $detailModel->load(['ProduseDetalii' => $detailData]);
                $details[] = $detailModel;
            }
            $model->produse_detalii = $details;
            //  VarDumper::dump($details);
            // exit();
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $numeImagine = null;
            if (!is_null($model->imageFile)) {
                $numeImagine = Yii::$app->security->generateRandomString(32);
            }
            if ($model->saveProdus($numeImagine)) //&& $model->upload($numeImagine))
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionIncarcareSesiune() {
        $sesiune = \backend\models\Sesiuni::findOne(['user' => \Yii::$app->user->id, 'data_ora_sfarsit' => NULL]);
        if (!is_null($sesiune)) {
            $sesiuniProduse = \backend\models\SesiuniProduse::find()->where(['sesiune' => $sesiune])->all();
            $comenziLinii = [];
            foreach ($sesiuniProduse as $sesiuneProdus) {
                if ($sesiuneProdus->cantitate > 0) {
                    $comandaLine = new \backend\models\ComenziLinii();
                    $comandaLine->produs = $sesiuneProdus->produs;
                    $comandaLine->cantitate = $sesiuneProdus->cantitate;
                    array_push($comenziLinii, $comandaLine);
                }
            }
            return \yii\helpers\Json::encode($comenziLinii);
        }
        $jsonContent['success'] = false;
        return \yii\helpers\Json::encode($jsonContent);
    }

    /**
     * Displays a single Produse model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionModalProdus($idProdus) {
        return $this->renderAjax('_modal_produs', ['id' => $idProdus]);
    }

    public function actionEditeazaInterfata() {
        $model = new \backend\models\OrdineCategoriiForm();
        //$model->validate();
        //  \yii\helpers\VarDumper::dump($model->errors);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $idUri = explode(',', $model->ordineCategorii);
            $transaction = Yii::$app->db->beginTransaction();
            $save = true;
            foreach ($idUri as $index => $id) {
                $categorie = \backend\models\Categorii::findOne($id);
                $categorie->ordine = $index + 1;
                $save = $save && $categorie->save();
            }
            if ($save) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        }

        return $this->render('edit_interfata', ['model' => $model]);
    }

    //    public function actionModificaOrdine($ordine){
    //        $transaction = Yii::$app->db->beginTransaction();
    //        \yii\helpers\VarDumper::dump('sault');
    //        exit();
    ////        $x=1;
    ////        $save = true;
    ////        foreach ($ordine as $ordin){
    ////            $categorie = \backend\models\Categorii::findOne($ordin);
    ////            $categorie->ordine = $x;
    ////            $save = $categorie->save();
    ////            $x++;
    ////        }
    ////        if ($save) {
    ////            $transaction->commit();
    ////        }  else{
    ////            $transaction->rollBack();
    ////        }
    //        
    //    }

    public function actionAfisareIstoric($telefon) {

        $comenzi = \backend\models\Comenzi::getComenzi($telefon);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $comenzi,
            'pagination' => [
                'pageSize' => 2, // Set the number of records per page here
            ],
        ]);
        return $this->renderAjax('_istoric_view', [
                    'comenzi' => $dataProvider
        ]);
    }

    public function actionIncarcaDetalii($idComanda) {
        $comanda = \backend\models\Comenzi::findOne(['id' => $idComanda]);

        $detalii = [
            'adresa' => $comanda->adresa,
            'mentiuni' => $comanda->mentiuni,
            'telefon' => $comanda->numar_telefon,
        ];
        $detaliiJson = json_encode($detalii);
        return $detaliiJson;
    }

    public function actionProceseazaComanda($update = NULL, $categorie = NULL, $categorieMare = NULL) {
        // \yii\helpers\VarDumper::dump($categorie);
        $linii = []; //Yii::$app->session->get('produseCos', []);
        $searchModel = new ProduseSearch();
        if ($categorie) {
            $searchModel->categorie = $categorie;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //        $linie=new \backend\models\LinieComanda([
        //            'cantitate'=>3,
        //            'produs'=>90,
        //        ]);A
        //        $linii[]=$linie;
        //Yii::$app->session->set('produseCos', $linii);
        $dataProviderCos = new \yii\data\ArrayDataProvider([
            'allModels' => $linii,
        ]);
        $cat = \backend\models\Categorii::findOne($categorie);
        //  \yii\helpers\VarDumper::dump($categorieMare);

        if (\Yii::$app->request->isAjax && is_null($categorieMare)) {

            //            exit();
            $catName = 'rezultate-cautare';
            if ($cat) {
                $catName = \yii\helpers\Inflector::slug($cat->nume);
            }

            $produse = Produse::findAll(['categorie' => $cat->id]);
            if (!$produse) {
                return $this->renderPartial('_faraproduse_view');
            }
            return $this->renderAjax('_list_view', [
                        'searchModel' => $searchModel,
                        'categorie' => sprintf('list-%s', $catName),
                        'dataProvider' => $dataProvider,
            ]);
        }
        if (is_null($update)) {
            $update = 0;
        }
        //  \yii\helpers\VarDumper::dump('sunt aici' . $categorieMare . 'da');
        return $this->render('view_products', [
                    //'searchModel' => $searchModel
                    'update' => $update,
                    'model' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderCos' => $dataProviderCos,
        ]);
    }

    public function actionProduseComanda($idComanda) {
        if ($idComanda != 0) {
            $comenziLinii1 = \backend\models\ComenziLinii::find()->where(['comanda' => $idComanda])->all();
            foreach ($comenziLinii1 as $cl) {
                $produs = $cl->produs0;

                $produsDetaliu = \backend\models\ProduseDetalii::findOne(['id' => $cl->produs_detaliu]);
                $pret = $produsDetaliu->pret;

                $modelData = $produs->toArray();
                $additionalData = $produs->pretMeniu;
                $modelData['pret'] = $additionalData;

                $produseDetalii = \backend\models\ProduseDetalii::find()->where(['produs' => $produs->id])
                                ->andWhere(['disponibil' => 1])->all();
                $detaliu = $cl->produs_detaliu;
                $numeComplet = $produs->nume;
                if (sizeof($produseDetalii) > 1) {
                    $numeComplet = $numeComplet . ' - ' . $produsDetaliu->descriere;
                    $modelData['id_detaliu'] = $cl->produs_detaliu;
                    $modelData['pret_detaliu'] = $pret;
                } else {
                    $modelData['id_detaliu'] = null;
                    $detaliu = null;
                }



                $produsCustom = [
                    'id' => $produs->id,
                    'cantitate' => $cl->cantitate,
                    'denumire' => $numeComplet,
                    'pret' => $pret,
                    'json' => $modelData,
                    'detaliu' => $detaliu
                ];

                $produseCustom[] = $produsCustom;
            }

            $jsonProduseCustom = json_encode($produseCustom);
            return $jsonProduseCustom;
        }
        return;
    }

    public function actionSchimbaCategoria($idCategorie) {
        return $this->renderAjax('_subcategorii_view', ['id' => $idCategorie]);
    }

    public function actionComandaSesiune($idUser, $idProdus, $cantitate) {
        $result = Yii::$app->db->createCommand('SELECT VerificaSiGestioneazaSesiuneProdus(:user_id, :produs_id, :cantitate) as result')
                ->bindValue(':user_id', $idUser)
                ->bindValue(':produs_id', $idProdus)
                ->bindValue(':cantitate', $cantitate)
                ->queryOne();
        return $result;
    }

    /**
     * Creates a new Produse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * Updates an existing Produse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $postData = Yii::$app->request->post();

        $model = $this->findModel($id);
        foreach ($model->produseDetalii as $pd) {
            $model->produse_detalii[] = new ProduseDetalii(['pret' => $pd->pret, 'disponibil' => $pd->disponibil, 'descriere' => $pd->descriere]);
        }
        $model->tip_produs = 1;
        if (count($model->produse_detalii) > 0) {
            $model->tip_produs = 2;
        }
        $model->data_productie = strtotime($model->data_productie);
        $query = PreturiProduse::find()->where([
            'and', ['produs' => $id],
            ['or', ['IS', 'data_sfarsit', NULL], ['>=', 'data_sfarsit', new \yii\db\Expression('now()')]]
        ]);
        $preturiViitoare = count($query->all());
        if ($model->load($postData)) {
            $details = [];
            foreach ($postData['ProduseDetalii'] as $detailData) {
                $detailModel = new ProduseDetalii();
                $detailModel->load(['ProduseDetalii' => $detailData]);
                $details[] = $detailModel;
            }
            $model->produse_detalii = $details;
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $numeImagine = null;
            if (!is_null($model->imageFile)) {
                $numeImagine = Yii::$app->security->generateRandomString(32);
                $model->imageRemoved = 0;
            }
            /* if ($preturiViitoare == 0) {
              if ($model->saveProdus($numeImagine)) {
              return $this->redirect(['view', 'id' => $model->id]);
              }
              } else */
            if ($model->updateProdus($numeImagine)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Produse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        PreturiProduse::deleteAll(['produs' => $id]);
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Produse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Produse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Produse::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
