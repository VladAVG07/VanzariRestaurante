<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\datetime\DateTimePicker;
use \kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model backend\models\Functii */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="functii-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'data_inceput')->widget(DateControl::class, [
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

    <?=
    $form->field($model, 'data_sfarsit')->widget(DateControl::class, [
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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
