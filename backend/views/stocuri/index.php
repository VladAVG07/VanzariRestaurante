<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\StocuriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stocuri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Adauga Stoc', ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

//                            'id',
//                            'produs',
                        [
                                'attribute' => 'produs',
                                'value' => function($model) {
                                    return $model->produs0->nume;
                                }
                        ],
//                            [
//                                    'attribute' => 'cantitate',
//                                    'class' => 'kartik\grid\EditableColumn'
//                            ],
                        [
                          'attribute' => 'cantitate_ramasa',
//                            'value' => function($model){
//                                $stocuri = backend\models\Stocuri::findAll(['produs' => $model->produs0->id]);
//                                $cantitate = 0;
//                                foreach ($stocuri as $stoc){
//                                    $cantitate += $stoc->cantitate;
//                                }
//                                return $cantitate;
//                            }
                        ],
//                            'cantitate',
//                            [
//                                    'attribute' => 'cantitate',
//                                    'class' => '\kartik\grid\EditableColumn',
//                                    'editableOptions' => ['asPopover' => false],
//                            ],
                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
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
