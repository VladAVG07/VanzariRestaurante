<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Produse */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="produse-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($model, 'categorie')->dropDownList(
                    ArrayHelper::map(Categorii::find()->all(), 'id', 'nume'), ['prompt' => 'Selecteaza Categoria']
            )
            ?>
        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cod_produs')->textInput() ?>
        </div>
    </div>


    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'descriere')->textArea(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?=
            $form->field($model, 'data_productie')->widget(DateTimePicker::className(), [
                'model' => $model,
                'attribute' => 'dataProductie',
                'options' => ['placeholder' => 'Selectati data'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy hh:ii:ss'
                ]
            ])
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'pret')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?=
            $form->field($model, 'dataInceput')->widget(DateTimePicker::className(), [
                'model' => $model,
                'attribute' => 'dataInceput',
                'options' => ['placeholder' => 'Selectati data'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy hh:ii:ss'
                ]
            ])
            ?>
        </div>

        <div class="col-md-5">
            <?=
            $form->field($model, 'dataSfarsit')->widget(DateTimePicker::className(), [
                'model' => $model,
                'attribute' => 'dataInceput',
                'options' => ['placeholder' => 'Selectati data'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy hh:ii:ss'
                ]
            ])
            ?>
        </div>
    </div>


    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success right float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
