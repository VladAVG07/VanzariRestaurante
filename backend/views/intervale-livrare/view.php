<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\IntervaleLivrare */

if ($model->ziua_saptamanii == 1) {
    $this->title = 'Luni';
}
if ($model->ziua_saptamanii == 2) {
    $this->title = 'Marti';
}
if ($model->ziua_saptamanii == 3) {
   $this->title = 'Miercuri';
}
if ($model->ziua_saptamanii == 4) {
   $this->title = 'Joi';
}
if ($model->ziua_saptamanii == 5) {
    $this->title = 'Vineri';
}
if ($model->ziua_saptamanii == 6) {
    $this->title = 'Sambata';
}
if ($model->ziua_saptamanii == 7) {
    $this->title = 'Duminica';
}

$this->params['breadcrumbs'][] = ['label' => 'Intervale Livrare', 'url' => ['index']];
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
                            'ora_inceput',
                            'ora_sfarsit',
                            [
                                'attribute' => 'ziua_saptamanii',
                                'value' => function ($model) {
                                    if ($model->ziua_saptamanii == 1) {
                                        return 'Luni';
                                    }
                                    if ($model->ziua_saptamanii == 2) {
                                        return 'Marti';
                                    }
                                    if ($model->ziua_saptamanii == 3) {
                                        return 'Miercuri';
                                    }
                                    if ($model->ziua_saptamanii == 4) {
                                        return 'Joi';
                                    }
                                    if ($model->ziua_saptamanii == 5) {
                                        return 'Vineri';
                                    }
                                    if ($model->ziua_saptamanii == 6) {
                                        return 'Sambata';
                                    }
                                    if ($model->ziua_saptamanii == 7) {
                                        return 'Duminica';
                                    }
                                }
                            ],
                        ],
                    ])
                    ?>
                    <p>

                        <?=
                        Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
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