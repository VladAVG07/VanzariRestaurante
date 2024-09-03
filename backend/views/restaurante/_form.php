<?php

use backend\models\TipDeRestaurant;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


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
        <div class="col-md-3">
            <?=
                $form->field($model, 'tip_restaurant')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(TipDeRestaurant::find()->all(), 'id', 'nume'),
                    'hideSearch' => true,
                    'options' => ['placeholder' => 'Selectati tip restaurant ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'cui')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
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
        <div class="col-md-4">
            <?= $form->field($model, 'adresa')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
        <?=
               // $form->field($model, 'imageFile')->fileInput(['id' => 'imageFile']);
                 $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                        //overwriteInitial: true,
                        'showClose'=> false,
                        //browseLabel: '',
                        //removeLabel: '',
                        //browseIcon: '<i class="bi-folder2-open"></i>',
                        //removeIcon: '<i class="bi-x-lg"></i>',
                        //removeTitle: 'Cancel or reset changes',
                        //elErrorContainer: '#kv-avatar-errors-1',
                        //msgErrorClass: 'alert alert-block alert-danger',
                        //defaultPreviewContent: '<img src="/samples/default-avatar-male.png" alt="Your Avatar">',
                        //layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
                        //allowedFileExtensions: ["jpg", "png", "gif"]
                        'showCaption' => false,
                        'showUpload' => false,
                        'showCancel'=>false,
                        'showRemove'=>true,
                        'browseClass' => 'btn btn-primary',
                        'browseIcon' => '<i class="fas fa-camera"></i> ',
                        'browseLabel' =>  'Selectati imaginea'
                    ],
                ]);

            // $this->registerJs("
            //         $('#imageFile').change(function(){
            //             console.log('intrat');
            //             var file = this.files[0];
            //             if (file) {
            //                 var reader = new FileReader();
            //                 reader.onload = function (e) {
            //                     $('#imagePreview').html('<img src=\"' + e.target.result + '\" class=\"img-preview img-thumbnail\" />');
            //                 }
            //                 reader.readAsDataURL(file);
            //             } else {
            //                 $('#imagePreview').html('');
            //             }
            //         });
            //     ");

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