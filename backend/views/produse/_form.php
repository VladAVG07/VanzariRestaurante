<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;

/* @var $this yii\web\View */
/* @var $model backend\models\Produse */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="produse-form">

  <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'categorie')->dropDownList(
            ArrayHelper::map(Categorii::find()->where('parinte is null')->all(), 'id','nume'), 
                    ['prompt' => 'Selecteaza Categoria']
            ) ?>

    <?= $form->field($model, 'cod_produs')->textInput() ?>

    <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descriere')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_productie')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
