<?php

namespace backend\controllers;

use Yii;
use backend\models\Comenzi;
use backend\models\ComenziSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;

/**
 * ComenziController implements the CRUD actions for Comenzi model.
 */
class ComenziController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Comenzi models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ComenziSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comenzi model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionDisplayBon($id) {

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \backend\models\ComenziLinii::find()->where(['comanda' => $id]),
            'pagination' => false,
        ]);

        return $this->renderPartial('bon', [
                    'idComanda' => $id,
                    'listDataProvider' => $dataProvider,
        ]);
    }
    
    public function actionDisplayBonProduse($id){
        return $this->renderPartial('_bon_produse',['id'=>$id]);
    }

    public function actionPrinteaza() {
        //todo: printarea catre imprimanta, nu stim inca cum se face, daca aceasta s-a efectuat cu succes, atunci schimba starea comenzi in FINALIZAT

        $id = Yii::$app->request->post('id');
        $jsonContent['success'] = true;
        $jsonContent['message'] = '';
        $transanction = Yii::$app->db->beginTransaction();
        $comanda = Comenzi::findOne($id);
        $statusCurent = $comanda->status0;
        $statusCurent->data_ora_sfarsit = new Expression('NOW()');
        $saved = $statusCurent->save();
        $statusNou = new \api\modules\v1\models\ComenziDetalii([
            'comanda' => $id,
            'status' => 7,
            'data_ora_inceput' => new Expression('NOW()'),
            'data_ora_sfarsit' => new Expression('NOW()'),
            'detalii' => 'Printare bon fiscal'
        ]);
        $saved = $saved && $statusNou->save();
        $comanda->status = $statusNou->id;
        $comanda->data_ora_finalizare = new Expression('NOW()');
        $saved = $saved && $comanda->save();
        if ($saved) {
            $transanction->commit();
        } else {
            $transanction->rollBack();
            $jsonContent['success'] = false;
            $jsonContent['message'] = 'Eroare la schimbarea stausului comenzii curente';
        }
        return \yii\helpers\Json::encode($jsonContent);
    }

    /**
     * Creates a new Comenzi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $mentiuni = Yii::$app->request->post('mentiuni');
        $adresa = Yii::$app->request->post('adresa');
        $telefon = Yii::$app->request->post('telefon');
        $produse = Yii::$app->request->post('produse');
        $update = Yii::$app->request->post('update');
        if ($update == 0) {
            $model = new Comenzi();
            if ($model->salveazaComandaFaraSesiune($mentiuni, $adresa, $telefon, $produse)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render('create', [
                        'model' => $model,
            ]);
        } else {
            $model = Comenzi::findOne(['id'=>$update]);
            if ($model->actualizeazaComanda($mentiuni, $adresa, $telefon, $produse)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Comenzi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comenzi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAnuleazaComanda($id) {
        if ($this->findModel($id)->anuleazaComanda()) {
            //   Yii::$app->session->setFlash('success', 'Comanda a fost anulată cu succes.');
            return $this->render('view', ['model' => $this->findModel($id)]);
        }
    }

    /**
     * Finds the Comenzi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Comenzi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Comenzi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
