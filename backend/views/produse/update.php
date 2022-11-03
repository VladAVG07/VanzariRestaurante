<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Produse $model */

$this->title = Yii::t('app', 'Actualizeaza produs: {name}', [
    'name' => $model->nume,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Produses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="produse-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
