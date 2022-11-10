<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\switchinput\SwitchInput;

/** @var yii\web\View $this */
/** @var backend\models\Categorii $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categorii-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descriere')->textInput(['maxlength' => true]) ?>

    <?=
            $form->field($model, 'parinte')
            ->dropDownList(
                    ArrayHelper::map(Categorii::find()->all(), 'id', 'nume'), ['prompt' => 'Selecteaza parintele']
            )
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
