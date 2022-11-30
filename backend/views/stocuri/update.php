<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Stocuri */

$this->title = 'Actualizeaza Stoc: ' . $model->produs0->nume;
$this->params['breadcrumbs'][] = ['label' => 'Stocuri', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nume, 'url' => ['view', 'id' => $model->id]];
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