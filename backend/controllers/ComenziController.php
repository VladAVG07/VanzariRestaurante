<?php

namespace backend\controllers;

require __DIR__ . '\..\..\vendor\autoload.php';

use Yii;
use backend\models\Comenzi;
use backend\models\ComenziSearch;
use backend\models\ProduseDetalii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

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

    public function actionPrinteazaBon() {
        $id = Yii::$app->request->post('id');
        $comanda = Comenzi::findOne($id);
        $linie = str_repeat("\xc4", 30);

        //$logo = EscposImage::load("../web/uploads/diobistro.png", false);
       // $logo = EscposImage::load(, false);
        
        $connector = new WindowsPrintConnector("pos-58");
        $printer = new Printer($connector);

        
        $printer->setJustification(Printer::JUSTIFY_CENTER);
      //  $printer->graphics($logo);
        $printer->setEmphasis(true);
        $printer->text("Comanda\n");

        $printer->text($comanda->numar_comanda . "\n");
        $printer->setEmphasis(false);
        $printer->text($linie);
        $printer->feed();
        $printer->text("Telefon\n");
        $printer->setTextSize(2, 2);
        $printer->text($comanda->numar_telefon . "\n");
        $printer->setTextSize(1, 1);
        $printer->text($linie);
        $printer->feed();
        $printer->text($comanda->adresa);
        $printer->feed();
        $printer->text($linie);
        $printer->feed();

        $comenziLinii = \backend\models\ComenziLinii::findAll(['comanda' => $id]);
        foreach ($comenziLinii as $comandaLinie) {
            $produs = \backend\models\Produse::findOne(['id' => $comandaLinie->produs]);
            if (!is_null($comandaLinie->produs_detaliu)) {
                $detaliu = \backend\models\ProduseDetalii::find()->where(['id' => $comandaLinie->produs_detaliu])->one();
            } else {
                $detaliu = new ProduseDetalii();
                $detaliu->pret = $produs->pret_curent;
            }
            if (empty($detaliu->descriere))
                $linie1 = '[ ] ' . $comandaLinie->cantitate . ' x ' . $produs->nume;
            else
                $linie1 = '[ ] ' . $comandaLinie->cantitate . ' x ' . $produs->nume . ' - ' . $detaliu->descriere;


            
            if ($produs->picant){
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setFont(Printer::FONT_A);
                $printer->setEmphasis(true);
                $printer->setTextSize(2, 1);
                $printer->text("**PICANT**\n");
               // $printer->feed();
                $printer->setTextSize(1, 1);
                $printer->setEmphasis(false);
                $printer->setFont(Printer::FONT_A);
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                
//                $printer->setJustification(Printer::JUSTIFY_RIGHT);
//                $printer->setEmphasis(true);
//                $printer->text("**PICANT**");
//                $printer->setEmphasis(false);
//                $printer->setJustification(Printer::JUSTIFY_LEFT);
            }
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($linie1);
            
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setEmphasis(true);
            $printer->text($comandaLinie->pret . ' RON');
            $printer->setEmphasis(false);
            $printer->feed(2);
        }

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(true);
        $printer->setTextSize(2, 2);
        $printer->text("Total: " . number_format($comanda->pret, 2) . " RON\n");
        $printer->setTextSize(1, 1);
        $printer->setEmphasis(false);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->feed();
        $printer->text($linie);
        $printer->feed();
        $printer->setEmphasis(true);
        $printer->text("Mentiunile clientului: ");
        $printer->setEmphasis(false);
        $printer->text($comanda->mentiuni . "\n");
        $printer->feed(3);
        $printer->cut();


        $printer->close();
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

    public function actionDisplayBonProduse($id) {
        return $this->renderPartial('_bon_produse', ['id' => $id]);
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
            $model = Comenzi::findOne(['id' => $update]);
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
