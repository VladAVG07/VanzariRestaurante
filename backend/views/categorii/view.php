<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Categorii */

$this->title = $model->nume;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categorii'), 'url' => ['index']];
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
                            'nume',
                            'descriere',
                            [
                              'attribute' => 'categorie',
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
                        ],
                    ])
                    ?>
                    <p>
                        <?= Html::a(Yii::t('app', 'Actualizare'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?=
                        Html::a(Yii::t('app', 'Stergere'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Esti sigur ca vrei sa stergi aceasta categorie?'),
                                'method' => 'post',
                            ],
                        ])
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