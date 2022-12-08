<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\datetime\DateTimePicker;
use \kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model backend\models\Produse */
/* @var $modelPret backend\models\PreturiProduse */
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
            $form->field($model, 'data_productie')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATETIME,
                'displayFormat' => 'php:d.m.Y H:i',
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

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($modelPret, 'pret')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?=
            $form->field($modelPret, 'data_inceput')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATETIME,
                'displayFormat' => 'php:d.m.Y H:i',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ],
                'language' => 'ro'
            ])
            ?>
        </div>

        <div class="col-md-5">
            <?=
            $form->field($modelPret, 'data_sfarsit')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATETIME,
                'displayFormat' => 'php:d.m.Y H:i',
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
