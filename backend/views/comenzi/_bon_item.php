<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$produsDetaliu = \backend\models\ProduseDetalii::findOne(['id'=>$model->produs_detaliu]);
$pret = $produsDetaliu->pret;

// Html::a(Html::encode($model->title), Url::toRoute(['post/show', 'id' => $model->id]), ['title' => $model->title])
?>

<article class="bon-item" data-key="<?= $model->id; ?>">
    <h5 class="title">
        <?= strtoupper($model->produs0->nume) ?>
    </h5>

    <div class="row">
        <div class="col-md-8">
            <?= '   ' . $model->cantitate . ' x ' . $pret ?>
        </div>
        <div class="col-md-4">
            <span class="pret-total-produs float-right"><?= $model->pret ?></span>
        </div>
    </div>

</article>