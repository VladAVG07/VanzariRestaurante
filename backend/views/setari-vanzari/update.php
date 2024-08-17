<?php

/* @var $this yii\web\View */
/* @var $model backend\models\SetariVanzari */

$this->title = 'Actualizeaza Setari Vanzari: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Setari Vanzari', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
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