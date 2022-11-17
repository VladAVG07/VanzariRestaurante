<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\switchinput\SwitchInput;
use kartik\bs5dropdown\ButtonDropdown;
use kartik\bs5dropdown\Dropdown;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;

/* @var $this yii\web\View */
/* @var $model backend\models\Categorii */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="categorii-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descriere')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'valid')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => 'Da',
            'offText' => 'Nu',
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="dropdown">
        <?php
//        echo ButtonDropdown::widget([
//            'label' => 'Parinte',
//            'dropdown' => [
//                'items' => [
//                
//                ],
//            ],
//            'buttonOptions' => ['class' => 'btn-outline-secondary']
//        ]);
        print_r(Categorii::getParents());
        ?>
    </div>

</div>
