<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\Produse */

$this->title = $model->nume;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Produse'), 'url' => ['index']];
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
                            ['attribute' => 'categorie', 'format' => 'raw', 'label' => 'Denumire categorie', 'value' => sprintf('<b>%s</b>', $model->categorie0->nume)],
                            'cod_produs',
                            'nume',
                            'descriere',
                            [
                                'attribute' => 'data_productie',
                                'value' => function($model) {
                                    return Yii::$app->formatter->asDate($model->data_productie);
                                }
                            ],
                            [
                                'attribute' => 'pret curent',
                                'value' => function ($model) {
                                    return $model->pret_curent . ' RON';
                                }
                            ],
                            [
                                'attribute' => 'stoc',
                                'value' => function ($model) {
                                    if ($model->stocabil)
                                        if (!is_null(backend\models\Stocuri::find()->where(['produs' => $model->id])->sum('cantitate_ramasa')))
                                            return backend\models\Stocuri::find()->where(['produs' => $model->id])->sum('cantitate_ramasa');
                                        else
                                            return 0;
                                    else
                                        return '-';
                                }
                            ],
                            [
                                'attribute' => 'alerta_stoc',
                                'value' => function ($model) {
                                    if ($model->stocabil)
                                        return $model->alerta_stoc;
                                    else
                                        return '-';
                                }
                            ],
                            [
                                'attribute' => 'imageFile',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    \yii\helpers\VarDumper::dump($model->imageFile);
                                    return Html::img(Yii::$app->request->baseUrl . '/' . $model->image_file, ['class' => 'img-thumbnail', 'style' => 'max-width:300px;']);
                                },
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
                                'confirm' => Yii::t('app', 'Esti sigur ca vrei sa stergi acest produs?'),
                                'method' => 'post',
                            ],
                        ])
                        ?>
                    </p>

                    <h3>Istoric Preturi</h3>

                    <?=
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getPreturiProduses()
                                ]),
                        'columns' => [
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
                            'pret',
//                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summary' => '',
//                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]);
                    ?>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>