<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\IntervaleLivrareSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Intervale Livrare';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Adauga intervale livrare', ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'ora_inceput',
                            'ora_sfarsit',
                            [
                                'attribute' => 'ziua_saptamanii',
                                'value' => function ($model) {
                                    if ($model->ziua_saptamanii==1){
                                        return 'Luni';
                                    }
                                    if ($model->ziua_saptamanii==2){
                                        return 'Marti';
                                    }
                                    if ($model->ziua_saptamanii==3){
                                        return 'Miercuri';
                                    }
                                    if ($model->ziua_saptamanii==4){
                                        return 'Joi';
                                    }
                                    if ($model->ziua_saptamanii==5){
                                        return 'Vineri';
                                    }
                                    if ($model->ziua_saptamanii==6){
                                        return 'Sambata';
                                    }
                                    if ($model->ziua_saptamanii==7){
                                        return 'Duminica';
                                    }
                                }
                            ],
                            [
                                'attribute' => 'program',
                                'value' => function ($model) {
                                    if ($model->program==1){
                                        return 'Program livrari';
                                    }else
                                        return 'Program restaurant';
                                }
                            ],

                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                'template'=>'{view} {delete}'],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
