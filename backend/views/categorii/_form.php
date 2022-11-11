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

    <?=
//            $form->field($model, 'parinte')
//            ->dropDownList(
//                    ArrayHelper::map(Categorii::find()->all(), 'id', 'nume'), ['prompt' => 'Selecteaza parintele']
//            )
    $form->field($model, 'parinte')->widget(\kartik\tree\TreeViewInput::widget([
                'name' => 'kvTreeInput',
                'value' => 'true', // preselected values
                'query' => Categorii::find()->addOrderBy('parinte'),
                'headingOptions' => ['label' => 'Store'],
                'rootOptions' => ['label' => '<i class="fas fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => true,
                'options' => ['disabled' => false]
    ]));
    ?>

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

</div>
