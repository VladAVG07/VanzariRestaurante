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
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>


            <?php
            $categorii = Categorii::find()
                ->innerJoin('restaurante_categorii rc', 'rc.categorie = categorii.id')
                ->innerJoin('restaurante r', 'rc.restaurant = r.id')
                ->innerJoin('restaurante_user ru', 'ru.restaurant = r.id')
                ->innerJoin('user u', 'ru.user = u.id')
                ->where(['u.id' => \Yii::$app->user->id])
                ->orderBy(['parinte' => SORT_ASC])->all();
            ?>

            <?= $form->field($model, 'parinte')->dropdownList(
                Categorii::formatItemsArray($categorii),
                ['prompt' => 'Selecteaza Categoria']
            )->label('Categorie') ?>
            <div class="row">
                <div class="col-md-6">
                    <?=
                        $form->field($model, 'ordine')->textInput(['type' => 'number', 'min' => 0, 'max' => 1000, 'step' => 1]);
                    ?>
                </div>
                <div class="col-md-6">
                    <?=
                        $form->field($model, 'valid')->widget(SwitchInput::class, [
                            'pluginOptions' => [
                                'onText' => 'Da',
                                'offText' => 'Nu',
                            ]
                        ]);
                    ?>
                </div>

            </div>
        </div>
        <div class="col-md-6 mh-100">
            <?= $form->field($model, 'descriere')->textarea(['maxlength' => true,'rows'=>7]) ?>
        </div>
    </div>
    <div class="form-group float-right">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>