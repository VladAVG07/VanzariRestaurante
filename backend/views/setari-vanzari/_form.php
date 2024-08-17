<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\SetariVanzari */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="setari-vanzari-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?=
        $form->field($model, 'vanzari_oprite')->widget(SwitchInput::class, [
            'pluginOptions' => [
                'onText' => 'Da',
                'offText' => 'Nu',
            ]
        ]);
        ?>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'mesaj_oprit')->textarea(['maxlength' => true, 'rows' => 7]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'mesaj_generic')->textArea(['maxlength' => true, 'rows' => 7]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Salveaza', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
