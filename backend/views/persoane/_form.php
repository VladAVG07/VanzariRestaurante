<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\datetime\DateTimePicker;
use \kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model backend\models\Persoane */
/* @var $functiePersoana backend\models\FunctiiPersoane */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="persoane-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-2">
            <?= $form->field($model, 'numar_identificare')->textInput() ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'prenume')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($model, 'functie')->dropdownList(\yii\helpers\ArrayHelper::map(\backend\models\Functii::find()->all(), 'id', 'nume'),['prompt' => '--Selecteaza functia--'])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            
            $form->field($model, 'dataInceputFunctie')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:d.m.Y',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                    ]
                ],
                'language' => 'ro'
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'dataSfarsitFunctie')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:d.m.Y',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ],
                'language' => 'ro'
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
