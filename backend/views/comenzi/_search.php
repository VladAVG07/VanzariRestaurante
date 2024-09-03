<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ComenziSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row mt-2">
    <div class="col-md-12">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'numar_comanda') ?>

    <?= $form->field($model, 'utilizator') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'data_ora_creare') ?>

    <?php // echo $form->field($model, 'data_ora_finalizare') ?>

    <?php // echo $form->field($model, 'pret') ?>

    <?php // echo $form->field($model, 'tva') ?>

    <?php // echo $form->field($model, 'mentiuni') ?>

    <?php // echo $form->field($model, 'canal') ?>

    <?php // echo $form->field($model, 'mod_plata') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
    <!--.col-md-12-->
</div>
