<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ComenziSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comenzi');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a(Yii::t('app', 'Adauga Comanda'), ['produse/proceseaza-comanda'], ['class' => 'btn btn-success']) ?>
                            <?= Html::a(Yii::t('app', 'Editeaza interfata'), ['produse/editeaza-interfata'], ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>


                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'numar_comanda',
                            [
                                'attribute' => 'utilizator',
                                'value' => function ($model) {
                                    return $model->utilizator0->username;
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    return $model->status0->status0->nume;
                                }
                            ],
                            [
                                'attribute' => 'data_ora_creare',
                                'value' => function($model) {
                                    return date('d-m-Y H:i:s', strtotime($model->data_ora_creare));
                                }
                            ],
                            //'data_ora_finalizare',
                            [
                                'attribute' => 'pret',
                                'value' => function($model) {
                                    return $model->pret . " RON";
                                }
                            ],
                            //'tva',
                            //'mentiuni',
                            //'canal',
                            //'mod_plata',
                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]);
                    ?>


                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
