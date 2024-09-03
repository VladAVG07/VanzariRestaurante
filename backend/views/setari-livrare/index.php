<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SetariLivrareSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Setari Livrare');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a(Yii::t('app', 'Modifica Setari Livrare'), ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'attribute' => 'restaurant',
                                'value' => function($model){
                                    $restaurant = \backend\models\Restaurante::findOne(['id' => $model->restaurant]);
                                    return $restaurant->nume;
                                }   
                            ],
                            [
                                'attribute' => 'produs',
                                'value' => function($model){
                                    $produs = backend\models\Produse::findOne(['id' => $model->produs]);
                                    return $produs->nume;
                                }
                            ],
                            [
                                'attribute' => 'pret',
                                'value' => function($model){
                                    $produs = backend\models\Produse::findOne(['id' => $model->produs]);
                                    return $produs->pret_curent;
                                }
                            ],
                            'comanda_minima',

                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]); ?>


                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
