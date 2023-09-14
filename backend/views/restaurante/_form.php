<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Restaurante */
/* @var $form yii\bootstrap4\ActiveForm */
$veriicareCuiAction = Yii::$app->urlManager->createUrl('restaurante/verifica-cui');
$fieldNameId = Html::getInputId($model, 'cui');
$fieldNume = Html::getInputId($model, 'nume');
$fieldAdresa = Html::getInputId($model, 'adresa');
$fieldNumarTelefon = Html::getInputId($model, 'numar_telefon');
$js = <<< SCRIPT
    $('#$fieldNameId').keyup(function() {
//        var thisVal = $(this).val();
//        if (thisVal !== '') {
//            var checker = function(v) {
//                if (thisVal !== v) {
//                    setTimeout('checker(thisVal)', 1500);
//                }
//                else {
//                    $.ajax({
//                        type: "GET",
//                        url: "$veriicareCuiAction&cui="+thisVal,
//                        success: function (data) {
//                            console.log(data);
//                        },
//                        error: function (error) {
//                            console.log(error);
//                        }
//                    });
//                }
//            }
//
//            setTimeout(function() {
//            checker(thisVal)
//        }, 3000);
//    }
        var thisVal = $(this).val();
        if (thisVal[0] === 'R' && thisVal[1] === 'O'){
            if (thisVal.length === 10) {
                newVal = thisVal.slice(2);
                console.log(newVal);
                $.ajax({
                    type: "GET",
                    url: "$veriicareCuiAction&cui="+newVal,
                    success: function (data) {
                        console.log(data);
                        var json = JSON.parse(data);
                        document.getElementById('$fieldNume').value = json.denumire;
                        document.getElementById('$fieldAdresa').value = json.adresa;
                        document.getElementById('$fieldNumarTelefon').value = json.telefon;
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        }else
            if (thisVal.length === 8) {
                $.ajax({
                    type: "GET",
                    url: "$veriicareCuiAction&cui="+thisVal,
                    success: function (data) {
                        console.log(data);
                        var json = JSON.parse(data);
                        document.getElementById('$fieldNume').value = json.denumire;
                        document.getElementById('$fieldAdresa').value = json.adresa;
                        document.getElementById('$fieldNumarTelefon').value = json.telefon;
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
    });
SCRIPT;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="restaurante-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'cui')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'adresa')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'numar_telefon')->widget(PhoneInput::className(), [
                'jsOptions' => [
                    'preferredCountries' => ['ro', 'bg', 'md'],
                ],
            ])->label('Telefon', [
                'style' => 'display:block;'
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'parola')->passwordInput(['autocomplete' => 'new-password', 'maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'confirmareParola')->passwordInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
