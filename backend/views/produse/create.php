<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Produse $model */

$this->title = Yii::t('app', 'Adauga produs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Produse'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produse-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
