<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SetariLivrare */
/* @var $form yii\bootstrap4\ActiveForm */

$setariLivrare = backend\models\SetariLivrare::find()
                ->innerJoin('restaurante r', 'r.id = setari_livrare.restaurant')
                ->innerJoin('restaurante_user ru', 'ru.restaurant = r.id')
                ->innerJoin('user u', 'ru.user = u.id')
                ->where(['u.id' => \Yii::$app->user->id])->orderBy(['id'=>SORT_DESC])->all();


if (!is_null($setariLivrare) && !empty($setariLivrare)){
    $ultimaSetare = $setariLivrare[0];
    $pret = $ultimaSetare->produs0->pret_curent;
}
$fieldPret = Html::getInputId($model, 'pret');
$fieldComandaMinima = Html::getInputId($model, 'comanda_minima');
if (!is_null($setariLivrare) && !empty($setariLivrare)){
$js = <<< SCRIPT
            document.getElementById('$fieldPret').value = '$pret';
            document.getElementById('$fieldComandaMinima').value = '$ultimaSetare->comanda_minima';
SCRIPT;
$this->registerJs($js, \yii\web\View::POS_READY);
}
?>

<div class="setari-livrare-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'pret')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'comanda_minima')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
