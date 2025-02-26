<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\Persoane */

$this->title = $model->nume . ' ' . $model->prenume;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Persoane'), 'url' => ['index']];
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
                            'numar_identificare',
                            'nume',
                            'prenume',
                            [
                                'attribute' => 'functie_curenta',
                                'value' => function ($model) {
                                    if (!is_null($model->functie_curenta))
                                        return backend\models\Functii::findOne(['id' => $model->functie_curenta])->nume;
                                    else
                                        return 'Nedefinit';
                                }
                            ],
                        ],
                    ])
                    ?>
                    <p>
                        <?= Html::a(Yii::t('app', 'Actualizeaza'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?=
                        Html::a(Yii::t('app', 'Sterge'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Esti sigur ca vrei sa stergi aceasta persoana?'),
                                'method' => 'post',
                            ],
                        ])
                        ?>

                    <h3>Istoric Functii</h3>

                    <?=
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getFunctiiPersoanes()
                                ]),
                        'columns' => [
                            [
                                'attribute' => 'functie',
                                'value' => function($model) {
                                    return $model->functie0->nume;
                                }
                            ],
                            [
                                'attribute' => 'data_inceput',
                                'value' => function($model) {
                                    return Yii::$app->formatter->asDate($model->data_inceput);
                                }
                            ],
                            [
                                'attribute' => 'data_sfarsit',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return Html::tag('span', ($model->data_sfarsit == null) ? '&infin;' : Yii::$app->formatter->asDate($model->data_sfarsit), ['style' => sprintf('color:%s; font-weight:%s', $model->data_sfarsit ? '#000' : '#ff0000', $model->data_sfarsit ? 'normal' : 'bold')]);
                                }
                            ],
//                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summary' => '',
//                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]);
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