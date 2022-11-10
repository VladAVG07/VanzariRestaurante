<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Categorii $model */

$this->title = Yii::t('app', 'Create Categorii');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categoriis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorii-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
