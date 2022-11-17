<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
                            'data_productie',
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
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>