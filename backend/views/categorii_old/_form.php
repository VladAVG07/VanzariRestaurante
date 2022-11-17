<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Categorii $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categorii-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descriere')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parinte')->textInput() ?>

    <?= $form->field($model, 'valid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
