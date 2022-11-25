<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Categorii;


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
                    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                              'nume',
                            [
                                'attribute' => 'categorie',
                                'value' => 'categorie0.nume', //relation name with their attribute
                              //  'filter'=> yii\helpers\ArrayHelper::map(Categorii::find()->asArray()->all(), 'id', 'nume'),
                                    'filter' => Html::activeDropDownList($searchModel, 'categorie',yii\helpers\ArrayHelper::map(Categorii::find()->asArray()->all(), 'id', 'nume'),
                                            ['class'=>'form-control','prompt' => '--Toate categoriile--']),
                            ],
                            'cod_produs',
                            'descriere',
                            [
                                    'attribute' => 'pret',
                                    'value' => function($model) {
                                        return $model->getPretCurent()->pret.' RON';
                                    }
                            ],
                            //'data_productie',
                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'layout'=>"{items}\n{pager}\n{summary}",
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
