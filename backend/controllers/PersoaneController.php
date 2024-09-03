<?php

namespace backend\controllers;

use Yii;
use backend\models\Persoane;
use backend\models\PersoaneSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PersoaneController implements the CRUD actions for Persoane model.
 */
class PersoaneController extends Controller {

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
     * Lists all Persoane models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PersoaneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Persoane model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Persoane model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Persoane();

        if ($model->load(Yii::$app->request->post()) && $model->savePersoana()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Persoane model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be foun
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $query = \backend\models\FunctiiPersoane::find()->where(['and', ['persoana' => $id],
                        ['or', ['IS', 'data_sfarsit', NULL], ['>=', 'data_sfarsit', new \yii\db\Expression('now()')]]]);
        $query1 = \backend\models\FunctiiPersoane::find()->where(['persoana' => $this->id]);
        $functiiViitoare = count($query->all());
        if ($functiiViitoare == 0){
            if ($model->load(Yii::$app->request->post()) && $model->savePersoana()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }else{
            if ($model->load(Yii::$app->request->post()) && $model->updatePersoana()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
        
//        $query = \backend\models\FunctiiPersoane::find()->where(['and', ['persoana' => (int) $id], ['or', ['IS', 'data_sfarsit', NULL], ['>=', 'data_sfarsit', new \yii\db\Expression('now()')]]]);
//        // \yii\helpers\VarDumper::dump($query->createCommand()->getRawSql());
//        $functieNoua = $query->one();
//
//        $model->functie = $functieNoua->functie;
//        \yii\helpers\VarDumper::dump($functieNoua);
//        // exit();
//        //$model->dataInceputFunctie = $functieNoua->data_inceput;
//        //$model->dataSfarsitFunctie = $functieNoua->data_sfarsit;
//        if ($model->load(Yii::$app->request->post()) && $model->saveOrUpdateWithFunctie($functieNoua->id)) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//        return $this->render('update', [
//                    'model' => $model,
//        ]);
    }

    /**
     * Deletes an existing Persoane model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Persoane model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Persoane the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Persoane::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
