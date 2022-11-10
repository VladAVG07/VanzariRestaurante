<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Categorii $model */
$this->title = $model->nume;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categorii'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categorii-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nume',
            'descriere',
            'parinte0.nume',
            [
                'attribute' => 'valid',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('span', $model->valid ? 'Da' : 'Nu', ['style' => 'font-weight:bold;' . sprintf('color:%s', $model->valid ? '#000' : '#ff0000')]);
                }
            ],
        ],
    ])
    ?>

    <p>
        <?= Html::a(Yii::t('app', 'Actualizare'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Stergere'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

</div>
