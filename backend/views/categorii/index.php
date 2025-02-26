<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategoriiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categorii');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a(Yii::t('app', 'Adaugă categorie'), ['create'], ['class' => 'btn btn-success add-categorie', 'id'=>'add-categorie']) ?>
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
                            'descriere',
                            [
                                'attribute' => 'parinte',
                                'value' => function($model) {
                                    return is_null($model->parinte0) ? "Nu are parinte" : $model->parinte0->nume;
                                }
                            ],
                            [
                                'attribute' => 'valid',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return Html::tag('span', $model->valid ? 'Da' : 'Nu', ['style' => 'font-weight:bold;' . sprintf('color:%s', $model->valid ? '#000' : '#ff0000')]);
                                }
                            ],
                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                      //  'summary' => '<b>{begin}-{end}</b> din <b>{totalCount}</b> categorii',
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
