<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'Produs',
                                'value' => function ($model) {
                                    return $model->produs0->nume;
                                },

                            ],
                            'cantitate',
                        ],
                    ]) ?>
                    <p>
                        <?= Html::a('Actualizeaza', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Sterge', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Esti sigur ca vrei sa stergi toate datele despre acest stoc?',
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