<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Categorii $model */

$this->title = Yii::t('app', 'Adauga categorie');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categorii'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorii-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
