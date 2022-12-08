<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Categorii */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="categorii-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descriere')->textInput(['maxlength' => true]) ?>

<!--    --><?php //echo \kartik\bs5dropdown\ButtonDropdown::widget([
//        'label' => 'Selecteaza parintele',
//        'dropdown' => [
//            'items' => [
//                ['label' => 'Action', 'url' => '#'],
//                ['label' => 'Submenu 1', 'items' => [
//                    ['label' => 'Action', 'url' => '#'],
//                    ['label' => 'Another action', 'url' => '#'],
//                    ['label' => 'Something else here', 'url' => '#'],
////                    '<div class="dropdown-divider"></div>',
//                    ['label' => 'Submenu 2', 'items' => [
//                        ['label' => 'Action', 'url' => '#'],
//                        ['label' => 'Another action', 'url' => '#'],
//                        ['label' => 'Something else here', 'url' => '#'],
////                        '<div class="dropdown-divider"></div>',
//                        ['label' => 'Separated link', 'url' => '#'],
//                    ]],
//                ]],
//                ['label' => 'Something else here', 'url' => '#'],
////                '<div class="dropdown-divider"></div>',
//                ['label' => 'Separated link', 'url' => '#'],
//            ],
//        ],
//        'buttonOptions' => ['class' => 'btn-outline-secondary']
//    ]
//    ) ?>

    <?= $form->field($model , 'parinte')->dropdownList(
            Categorii::formatItemsArray(),
            ['prompt' => 'Selecteaza parintele']
    )?>

    <?=
    $form->field($model, 'valid')->widget(SwitchInput::class, [
        'pluginOptions' => [
            'onText' => 'Da',
            'offText' => 'Nu',
        ]
    ]);
    ?>

    <?php
        print_r(Categorii::getDropDownItems());
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
