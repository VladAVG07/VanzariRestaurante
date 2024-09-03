<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\IntervaleLivrare */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="intervale-livrare-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'ora_inceput')->widget(TimePicker::classname(), ['pluginOptions' => [
                    'showSeconds' => false,
                    'showMeridian' => false,
                    'minuteStep' => 5,
        ]])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'ora_sfarsit')->widget(TimePicker::classname(), ['pluginOptions' => [
                    'showSeconds' => false,
                    'showMeridian' => false,
                    'minuteStep' => 5,
        ]])
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($model, 'zileSaptamana')->widget(Select2::class, [
                'data' => [
                    '1' => 'Luni',
                    '2' => 'Marti',
                    '3' => 'Miercuri',
                    '4' => 'Joi',
                    '5' => 'Vineri',
                    '6' => 'Sambata',
                    '7' => 'Duminica'
                ],
                'options' => ['placeholder' => 'ZIle ale saptamanii...'],
                'pluginOptions' => [
                    'multiple' => true
                ],
            ])
            ?>
        </div>
        <div class="col-md-2">
            <?=
            $form->field($model, 'program')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'offText' => 'Program restaurant',
                    'onText' => 'Program livrari',
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
    <?= Html::submitButton('Salveaza', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
