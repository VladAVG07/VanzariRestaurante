<?php

/** @var yii\web\View $this */
/** @var kartik\form\ActiveForm $form */
/** @var \frontend\models\FormularComanda $model */
/** @var \frontend\models\Basket $basket */

use kartik\form\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;
use yii\helpers\Html as HelpersHtml;

$this->title = 'Procesare comandă';
//$this->params['breadcrumbs'][] = $this->title;

$dataProviderCos = new \yii\data\ArrayDataProvider([
    'allModels' => $basket->basketItems,
]);

$idNumarStrada=Html::getInputId($model,'numar');

$total = 0.00;
foreach ($basket->basketItems as $bi) {
    $total = $total + $bi->pret * $bi->cantitate;
}


// Get current hour and minutes
// Set the timezone to Bucharest, Romania
$timeZone = new \DateTimeZone(Yii::$app->formatter->timeZone);

// Get the current time in the specified timezone
$currentTime = new \DateTime('now', $timeZone);

// Round up to the next half hour
$currentTime->modify('+1 hour');
$currentTime->setTime($currentTime->format('H'), $currentTime->format('i') < 30 ? 30 : 0); // Round up to next half or full hour

// Get the current hour and minute
$currentHour = $currentTime->format('H');
$currentMinute = $currentTime->format('i');

// Calculate the next hour

// Initialize an array to store the time slots
$timeSlots = [];
$timeSlots['0'] = 'Cât mai repede';
$dif = 22;
// Generate time slots
for ($hour = $currentHour; $hour < 22; $hour++) {
    for ($minute = 0; $minute < 60; $minute += 30) {
        $formattedHour = str_pad($hour % 24, 2, '0', STR_PAD_LEFT); // Ensure 2-digit format
        $formattedMinute = str_pad($minute, 2, '0', STR_PAD_LEFT); // Ensure 2-digit format
        $timeSlots["$formattedHour:$formattedMinute"] = "$formattedHour:$formattedMinute";
    }
}
$formatJs = <<< SCRIPT
var form = $('form');
var itemNumar=undefined;

$(document).ready(function() {
    // Change class of selected radio button group
    $('.ftco-appointment input[type="radio"]:checked').parent().removeClass('btn-outline-secondary').addClass('btn-warning');
    //$('#btn-adauga-in-cos').attr('data-id',$('.modal-body input[type="radio"]:checked').val());
    $('.cart-button').hide();
    if('$model->tipLocuinta'==1){
        $('.field-$idNumarStrada').addClass('required');
    }
    $('.form-check').each((index,el)=>{
      //  console.log(el);
        $(el).removeClass('btn-warning');
    });

});

function updateRadioButtonClass(element) {
    var radioButtons = $(element).closest('.btn-group').find('input[type=\"radio\"]');
    radioButtons.each(function() {
        var radioButton = $(this);
        if (radioButton.prop('checked')) {
            radioButton.parent().removeClass('btn-outline-secondary').addClass('btn-warning');
            var selectedValue=$(this).val();
            if(selectedValue==1){
                $('.field-$idNumarStrada').show();
                $('.bloc-details').slideUp({
                    duration: 400, // Adjust animation duration as needed
                    complete: function () {
                      $(this).attr('style','display: none !important');
                    },
                  });
                  $('.field-$idNumarStrada').addClass('required');
                  if(itemNumar){
                    var exista=false;
                    var settings=form.yiiActiveForm('data').attributes;
                    settings.forEach(function(item) {
                        if(item.id==='$idNumarStrada' && item.id.indexOf('$idNumarStrada')!==-1){
                            exista=true;
                        }
                    });
                    if(!exista){
                        form.yiiActiveForm('add',itemNumar);
                    }
                  }

            }
            else{
                $('.field-$idNumarStrada').hide();
                var settings=form.yiiActiveForm('data').attributes;
                settings.forEach(function(item) {
                    if(item.id==='$idNumarStrada' && item.id.indexOf('$idNumarStrada')!==-1){
                        itemNumar=item;
                    }
                });
                if(itemNumar){
                    form.yiiActiveForm('remove',itemNumar.id);
                }
                $('.field-$idNumarStrada').removeClass('required');
                $('.bloc-details').find('[class*="field-formularcomanda-bloc"]').addClass('required');
                $('.bloc-details').find('[class*="field-formularcomanda-scara"]').addClass('required');
                $('.bloc-details').find('[class*="field-formularcomanda-apartament"]').addClass('required');
                $('.bloc-details').slideDown();
            }
        } else {
            radioButton.parent().removeClass('btn-warning').addClass('btn-outline-secondary');
        }
    });
}       
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);
?>



<div class="container ftco-appointment pt-5 pb-5">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <!-- Summary cart section -->
        <div class="col-lg-4 order-lg-2 order-1  pb-5">
            <!-- Summary cart content goes here -->
            <div class="summary-cart border border-warning rounded">
                <div class="d-flex border-bottom border-dashed border-warning">
                    <h4 class="pl-3 pt-3">Continut coș</h4>
                </div>
                <div class="d-flex pt-3">
                    <div class="col-md-6 col-6"><span class="font-weight-bold">Produs</span></div>
                    <div class="col-md-3 col-3 text-right"><span class="font-weight-bold">Cantitate</span></div>
                    <div class="col-md-3 col-3 text-right"><span class="font-weight-bold">Pret</span></div>
                </div>
                <?= $this->render('_list_view_cos', ['liniiDataProvider' => $dataProviderCos]) ?>
                <div class="d-flex border-top border-warning mt-2 pb-3 pt-3">
                    <div class="col-md-5 col-5"><span class="price font-weight-bold">Total:</span></div>
                    <div class="col-md-7 col-7 text-right"><span class="price font-weight-bold"><?= number_format($total, 2) ?> RON</span></div>
                </div>
            </div>
        </div>

        <!-- Form section -->
        <div class="col-lg-8 order-lg-1 order-2">
            <!-- Form content goes here -->
            <div class="form-container">
                <?php $form = ActiveForm::begin(['id' => 'comanda-form', 'enableClientValidation' => true]); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'nume')->input('text') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'prenume')->input('text') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->input('text') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'telefon')->input('text') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $tipuriLocuinta = [1 => 'La casă', 2 => 'La bloc'];
                        echo $form->field($model, 'tipLocuinta')->radioButtonGroup($tipuriLocuinta, ['onchange' => 'updateRadioButtonClass(this);'])->label(null, ['style' => 'display:block;']);
                        ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($model, 'oras')->input('text') ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'strada')->input('text') ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'numar')->input('text') ?>
                    </div>
                </div>
                
                <div class="row bloc-details" style="display:  <?=$model->tipLocuinta==1?'none':'auto'?> !important;">
                    <div class="col-md-3">
                        <?= $form->field($model, 'bloc')->input('text') ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'scara')->input('text') ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'apartament')->input('text') ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'interfon')->input('text') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'oraLivrare')->dropDownList($timeSlots) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo $form->field($model, 'metodaPlata')->radioList(
                            [0 => 'Plata online cu cardul', 1 => 'Plata la livrare', 2 => 'Ridicare personală'],
                            [
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $checked = $checked ? 'checked' : '';
                                    return "<div class='form-check'><input class='form-check-input' type='radio' name='$name' value='$value' $checked><label for='$name'> $label</label></div>";
                                },
                                'class' => 'radio-list' // Optional, add a class to the radio list container
                            ]
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'alteDetaliiReper')->textarea(['rows' => 5]) ?>
                    </div>
                </div>
                <div class="form-group float-right">
                    <?= Html::submitButton('Trimite comandă', ['class' => 'btn btn-primary', 'name' => 'comanda-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>