<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Produse */
/* @var $modelPret \backend\models\PreturiProduse */

$this->title = Yii::t('app', 'Actualizare produs: {name}', [
    'name' => $model->nume,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Produse'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nume, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizare');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_formn', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>