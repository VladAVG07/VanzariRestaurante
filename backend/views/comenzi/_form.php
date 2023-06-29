<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Comenzi */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="comenzi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mentiuni')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'canal')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'mod_plata')->dropdownList(\yii\helpers\ArrayHelper::map(\backend\models\ModuriPlata::find()->all(), 'id', 'nume'), ['prompt' => '--Selecteaza modul de plata--'])
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
