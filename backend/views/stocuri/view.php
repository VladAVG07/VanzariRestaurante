<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\Stocuri */

$this->title = "Stoc " . $model->produs0->nume;
$this->params['breadcrumbs'][] = ['label' => 'Stocuri', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'Produs',
                                'value' => function ($model) {
                                    return $model->produs0->nume;
                                },
                            ],
                            [
                                'attribute' => 'cantitate',
                                'value' => function ($model) {
//                                    $stocuri = backend\models\Stocuri::findAll(['produs' => $model->produs0->id]);
//                                    $cantitate = 0;
//                                    foreach ($stocuri as $stoc) {
//                                        $cantitate += $stoc->cantitate;
//                                    }
//                                    return $cantitate;
                                    return backend\models\Stocuri::find()->where(['produs' => $model->produs0->id])->sum('cantitate_ramasa');
                                }
                            ],
                        // 'cantitate',
                        ],
                    ])
                    ?>
                    <p>
                        <?= Html::a('Actualizeaza', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?=
                        Html::a('Sterge', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Esti sigur ca vrei sa stergi toate datele despre acest stoc?',
                                'method' => 'post',
                            ],
                        ])
                        ?>
                    <h3>Istoric Achizitionari</h3>
                    <?=
                 //   $stocuri =  backend\models\Stocuri::findAll(['produs' => $model->produs]);
                 //  yii\helpers\VarDumper::dump($model->produs);
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider([
                            //  'query' => 
                            'query' => backend\models\Stocuri::find()->where(['produs' => $model->produs])
                             
                                ]),
                        'columns' => [
                            [
                                'attribute' => 'cantitate',
                                'value' => function($model) {
                                    return $model->cantitate;
                                }
                            ],
                            [
                                'attribute' => 'pret_per_bucata',
                                'value' => function($model) {
                                    return $model->pret_per_bucata;
                                }
                            ],
                            [
                                'attribute' => 'data_cumparare',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return $model->data_cumparare;
                                }
                            ],
                            [
                                'attribute' => 'cantitate_ramasa',
                                'value' => function($model) {
                                    return $model->cantitate_ramasa;
                                }
                            ],
//                            [
//                                'attribute' => 'data_cumparare',
//                                'format' => 'raw',
//                                'value' => function($model) {
//                                    return Html::tag('span', ($model->data_sfarsit == null) ? '&infin;' : Yii::$app->formatter->asDate($model->data_sfarsit), ['style' => sprintf('color:%s; font-weight:%s', $model->data_sfarsit ? '#000' : '#ff0000', $model->data_sfarsit ? 'normal' : 'bold')]);
//                                }
//                            ],
//                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summary' => '',
//                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]);
                    ;
                    ?>
                    </p>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>