<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;

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
            if (!is_null($model->id)) {
                $modelIdToRemove = $model->id;
                    $categorii = array_filter($categorii, function($categorie) use ($modelIdToRemove) {
                    return $categorie->id !== $modelIdToRemove;
                });
            }
            ?>

            <?=
            $form->field($model, 'parinte')->widget(Select2::class, [
                    'data'=>Categorii::formatItemsArray($categorii),  'options' => ['placeholder' => 'Selecteaza categoria...']
            ])//->label('Categorie')
            ?>
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
            <?php
            $value = Categorii::find()
                    ->innerJoin('categorii_asociate ca','categorii.id = ca.categorie_asociata')
                    ->where(['ca.categorie'=>$model->id])->all();
            $currentCategoryIds = ArrayHelper::getColumn($value, 'id');
            $data = ArrayHelper::map($categorii, 'id', 'nume');
            $model->categorii_asociate = $currentCategoryIds;
            echo $form->field($model, 'categorii_asociate')->widget(Select2::class, [
                'data' => $data,
                'options' => ['placeholder' => 'Categorii asociate...'],
                'pluginOptions' => [
                    'multiple' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6 mh-100">
            <?= $form->field($model, 'descriere')->textarea(['maxlength' => true, 'rows' => 7]) ?>
        </div>
    </div>
    <div class="form-group float-right">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>