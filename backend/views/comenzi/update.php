<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Comenzi */

$this->title = Yii::t('app', 'Actualizeaza comanda: {name}', [
    'name' => $model->numar_comanda,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Comenzi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->numar_comanda, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizeaza');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>