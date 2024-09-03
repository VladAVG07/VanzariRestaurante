<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Restaurante */

$this->title = $model->nume;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'nume',
                            'cui',
                            'adresa',
                            'numar_telefon',
                            [
                                'attribute' => 'imageFile',
                                'format' => 'raw',
                                'value' => function ($model) {
                                   /// \yii\helpers\VarDumper::dump($model->imageFile);
                                    return Html::img(Yii::$app->request->baseUrl . '/' . $model->poza_prezentare, ['class' => 'img-thumbnail', 'style' => 'max-width:400px;']);
                                },
                            ],
                        ],
                    ]) ?>
                    <p>
                        <?= Html::a(Yii::t('app', 'Actualizeaza'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a(Yii::t('app', 'Sterge'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
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