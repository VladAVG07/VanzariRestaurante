<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Categorii;
use rmrevin\yii\fontawesome\FA;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProduseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Produse');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a(Yii::t('app', 'Adauga Produse'), ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php
                    // echo $this->render('_search', ['model' => $searchModel]);
                    //
                    $categorii = \backend\models\Categorii::find()->select(['id', 'nume', 'parinte'])
                                    ->orderBy(['parinte' => SORT_ASC])->all();
                    //  
                    ?>

                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'nume',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if (!$model->stocabil) {
                                        return $model->nume;
                                    }
                                    $cantitate = backend\models\Stocuri::find()->where(['produs' => $model->id])->sum('cantitate_ramasa');
                                   // yii\helpers\VarDumper::dump($cantitate);
                                    if ($cantitate == 0) {
                                        //<li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: Demo Street 123, Demo City 04312, NJ</li>
                                        return Html::tag('span', $model->nume . '' . Html::tag('i', '', ['class' => 'fas fa-exclamation-triangle', 'style' => 'color: #ff0000;'])
                                                        , ['data-toggle' => "tooltip",
                                                    'data-placement' => "right",
                                                    'title' => "Stoc inexistent"]
                                        );
                                    }else if ($cantitate < $model->alerta_stoc){
                                        return Html::tag('span', $model->nume . '' . Html::tag('i', '', ['class' => 'fas fa-exclamation-triangle', 'style' => 'color: #ff9500;'])
                                                        , ['data-toggle' => "tooltip",
                                                    'data-placement' => "right",
                                                    'title' => "Stoc limitat"]
                                        );
                                    }else
                                        return $model->nume;
                                        
                                        
                                }
                            ],
                            //  'nume',
                            [
                                'attribute' => 'categorie',
                                'value' => 'categorie0.nume', //relation name with their attribute
                                //  'filter'=> yii\helpers\ArrayHelper::map(Categorii::find()->asArray()->all(), 'id', 'nume'),
                                'filter' => Html::activeDropDownList($searchModel, 'categorie', Categorii::formatItemsArray($categorii), ['class' => 'form-control', 'prompt' => '--Toate categoriile--']),
                            ],
                            'cod_produs',
                            'descriere',
                            [
                                'attribute' => 'pretLivrare',
                                'value' => function ($model) {
                                    return $model->pret_curent . ' RON';
                                }
                            ],
                            //'data_productie',
                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'layout' => "{items}\n{pager}\n{summary}",
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]);
                    ?>

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
