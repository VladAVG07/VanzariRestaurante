<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Categorii $model */

$this->title = Yii::t('app', 'Actualizare categorie: {name}', [
    'name' => $model->nume,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categorii'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nume, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizare');
?>
<div class="categorii-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
