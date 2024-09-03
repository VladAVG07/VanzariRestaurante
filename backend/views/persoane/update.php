<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Persoane */

$this->title = Yii::t('app', 'Actualizeaza Persoana: {name}', [
    'name' => $model->nume . ' ' . $model->prenume,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Persoane'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nume . ' ' . $model->prenume, 'url' => ['view', 'id' => $model->id]];
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