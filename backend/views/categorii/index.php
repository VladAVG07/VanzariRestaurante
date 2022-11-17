<?php

use backend\models\Categorii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\CategoriiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = Yii::t('app', 'Categorii');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorii-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Adauga categorie'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //  'id',
            'nume',
            'descriere',
            [
                'attribute' => 'parinte',
                'value' => 'parinte0.nume', //relation name with their attribute
            ],
            [
                'attribute' => 'valid',
                'format'=>'raw',
                'value' => function($model){
                    return Html::tag('span',$model->valid?'Da':'Nu',['style'=>'font-weight:bold;'.sprintf('color:%s',$model->valid?'#000':'#ff0000')]);
                }
            ],
//            'valid:boolean',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Categorii $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>
