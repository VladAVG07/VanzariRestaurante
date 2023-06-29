<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Produse;
use kartik\datetime\DateTimePicker;
use \kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model backend\models\Stocuri */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="stocuri-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'produs')->dropdownList(
                    ArrayHelper::map(Produse::find()->where(['stocabil' => 1])->all(), 'id', 'nume'), ['prompt' => '--Selecteaza produsul--']
            )
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'cantitate')->textInput(
//        [
//            'type' => 'number',
//            'value' => function($model) {
//                return strval($model->cantitate);
//            },
//        ]F
            )
            ?>
        </div>
    </div>
    <div>
        <?= $form->field($model, 'pret_per_bucata')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Adauga Stoc', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
