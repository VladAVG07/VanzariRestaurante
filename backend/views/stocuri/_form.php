<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Produse;

/* @var $this yii\web\View */
/* @var $model backend\models\Stocuri */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="stocuri-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'produs')->dropdownList(
        ArrayHelper::map(Produse::find()->all(), 'id', 'nume'),
        ['prompt' => '--Selecteaza produsul--']
    ) ?>

    <?= $form->field($model, 'cantitate')->textInput(
        [
            'type' => 'number',
            'value' => 0,
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Adauga Stoc', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
