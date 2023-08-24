<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Restaurante */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="restaurante-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cui')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'adresa')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'numar_telefon')->widget(PhoneInput::className(), [
                'defaultOptions' => ['style' => 'display:block'],
                'jsOptions' => [
                    'preferredCountries' => ['ro', 'bg', 'md'],
                ],
                
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'parola')->passwordInput(['maxlength' => true]) ?>
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
