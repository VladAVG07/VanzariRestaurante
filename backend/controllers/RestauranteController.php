<?php

namespace backend\controllers;

use Yii;
use backend\models\Restaurante;
use backend\models\RestauranteSearch;
use backend\services\FileUploadService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RestauranteController implements the CRUD actions for Restaurante model.
 */
class RestauranteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Lists all Restaurante models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RestauranteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionVerificaCui($cui)
    {
        $jsonContent['success'] = false;
        $ch = curl_init();
        $headers = [
            'Content-Type: application/json'
        ];

        $postData = [[
            'cui' => $cui,
            'data' => date('Y-m-d')
        ]];
        curl_setopt($ch, CURLOPT_URL, 'https://webservicesp.anaf.ro/PlatitorTvaRest/api/v8/ws/tva');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

        curl_close($ch);
        $decodedJson = json_decode($result);
        if ($decodedJson->message === 'SUCCESS') {
            $jsonContent['success'] = true;
            if (count($decodedJson->found) > 0) {
                $jsonContent['denumire'] = $decodedJson->found[0]->date_generale->denumire;
                $jsonContent['adresa'] = $decodedJson->found[0]->date_generale->adresa;
                $jsonContent['telefon'] = $decodedJson->found[0]->date_generale->telefon;
            } else {
                $jsonContent['success'] = false;
            }
        }
        return \yii\helpers\Json::encode($jsonContent);
    }

    /**
     * Displays a single Restaurante model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Restaurante model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Restaurante();

        if ($model->load(Yii::$app->request->post()) && $model->salveazaRestaurant()) {
                return $this->redirect(['view', 'id' => $model->id]);
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Restaurante model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Restaurante model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Restaurante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Restaurante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Restaurante::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
