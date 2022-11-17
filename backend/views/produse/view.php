<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Produse $model */
$this->title = $model->nume;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Produse'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="produse-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            ['attribute'=>'categorie','format'=>'raw','label'=>'Denumire categorie','value'=>sprintf('<b>%s</b>',$model->categorie0->nume)],//'categorie0.nume',
            'cod_produs',
            'nume',
            'descriere',
            'data_productie',
        ],
    ])
    ?>

    <p>
        <?= Html::a(Yii::t('app', 'Actualizeaza'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Sterge'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Esti sigur ca vrei sa stergi acest produs?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

</div>
