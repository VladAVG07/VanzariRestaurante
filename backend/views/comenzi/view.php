<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\Comenzi */

$this->title = 'Comanda numarul #' . $model->numar_comanda;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Comenzi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$printezaAction = Yii::$app->getUrlManager()->createAbsoluteUrl('comenzi/printeaza');
$js = <<< SCRIPT
    $( document ).ready(function() {
    console.log( "ready!" );
    $(function () {
        $('#modalButton').click(function () {
            $('#modal').modal('show')
        });
    });
    $(function () {
        // changed id to class
        $('.cashButton').click(function (){
            $.get($(this).attr('href'), function(data) {
                $('#modalCash').modal('show').find('#modalContent').html(data)
            });
            return false;
        });
    });
    $(function () {
        // changed id to class
        $('.bonButton').click(function (){
            $.get($(this).attr('href'), function(data) {
                $('#modalBon').modal('show').find('#modalContent').html(data)
            });
            return false;
        });
    });
    $(function () {
        $('.btnPrinteaza').click(function(){
            $.ajax({
                type: "POST",
                url: "$printezaAction",
                data: {id: $model->id},
                success: function (data) {
                    $('#modalCash').modal('hide')
                    $('#modal').modal('hide')
                    location.reload(true);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });
});
SCRIPT;
$this->registerJs($js, \yii\web\View::POS_READY);

\yii\web\YiiAsset::register($this);
?>


<?php
Modal::begin([
    'title' => '<h4>Incaseaza banii pe comanda</h4>',
    'id' => 'modal',
    'size' => 'modal-lg'
]);
?>
<div>
    <center>
        <a class="btn btn-app bg-success cashButton" style="width:150px;height:150px;" href="<?= Url::to(['comenzi/display-bon', 'id' => $model->id]); ?>">
            <i class="fas fa-money-bill" style="font-size:55px"></i><span style="font-size:30px">Cash</span>
        </a>
        <a class="btn btn-app bg-primary" id="cardButton" style="width:150px;height:150px;">
            <i class="fas fa-credit-card" style="font-size:55px"></i><span style="font-size:30px">Card</span>
        </a>
    </center>
</div>

<?php Modal::end();
?>

<?php
Modal::begin([
    'title' => '<h4>Bonul fiscal</h4>',
    'id' => 'modalCash',
    'size' => 'modal-lg'
]);

echo "<div id='modalContent'></div>";
?>
<br>
<span class="float-right">
    <a class="btn btn-app bg-success btnPrinteaza" style="width:130px;height:60px;text-align: center; align-items: center; display: flex; justify-content: center;">
        <span style="font-size:25px;">Printeaza</span>
    </a>
</span>

<?php
Modal::end();
?>

<?php
Modal::begin([
    'title' => '<h4>Bonul fiscal</h4>',
    'id' => 'modalBon',
    'size' => 'modal-lg'
]);

echo "<div id='modalContent'></div>";
?>

<?php
Modal::end();
?>



<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">

                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'numar_comanda',
                            [
                                'attribute' => 'utilizator',
                                'value' => function($model) {
                                    return $model->utilizator0->username;
                                }
                            ],
//                            'status',
                            [
                                'attribute' => 'status',
                                'value' => function($model) {
                                    return $model->status0->status0->nume;
                                }
                            ],
                            'data_ora_creare',
                            //   'data_ora_finalizare',
                            [
                                'attribute' => 'data_ora_finalizare',
                                'value' => function($model) {
                                    if (is_null($model->data_ora_finalizare))
                                        return 'Nedefinit';
                                    else
                                        return $model->data_ora_finalizare;
                                }
                            ],
                            'pret',
                            //  'tva',
                            [
                                'attribute' => 'mentiuni',
                                'value' => function ($model) {
                                    if (is_null($model->mentiuni) || empty($model->mentiuni))
                                        return 'Fara mentiuni';
                                    else
                                        return $model->mentiuni;
                                }
                            ],
                            'canal',
                            //  'mod_plata',
                            [
                                'attribute' => 'mod_plata',
                                'value' => function($model) {
                                    return $model->modPlata0->nume;
                                }
                            ],
                        ],
                    ])
                    ?>
                    <p>
                        <?php if ($model->status0->status != 7) { ?>
                            <?= Html::button('Incaseaza', ['class' => 'btn btn-success', 'id' => 'modalButton']) ?>
                        <?php } else { ?>
                             <?= Html::a('Vizualizare bon', ['comenzi/display-bon', 'id' => $model->id], ['class' => 'btn btn-success bonButton']) ?>
                        <?php } ?>
                        <?= Html::a(Yii::t('app', 'Actualizeaza'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?=
                        Html::a(Yii::t('app', 'Sterge'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Esti sigur ca doresti sa stergi aceasta comanda?'),
                                'method' => 'post',
                            ],
                        ])
                        ?>


                    <h3>Produse comanda</h3>
                    <?=
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getComenziLiniis()
                                ]),
                        'columns' => [
                            [
                                'attribute' => 'produs',
                                'value' => function($model) {
                                    return $model->produs0->nume;
                                }
                            ],
                            [
                                'attribute' => 'cantitate',
                                'value' => function($model) {
                                    return $model->cantitate;
                                }
                            ],
                            [
                                'attribute' => 'pret',
                                'value' => function($model) {
                                    return $model->pret;
                                }
                            ],
//                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summary' => '',
//                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]);
                    ?>
                    <p>
                    <h3>Istoric statusuri</h3>
                    <?=
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider([
                            'query' => backend\models\ComenziDetalii::find()->where(['comanda' => $model->id])
                                ]),
                        'columns' => [
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    return $model->status0->nume;
                                }
                            ],
                            [
                                'attribute' => 'data_ora_inceput',
                                'value' => function ($model) {
                                    return $model->data_ora_inceput;
                                }
                            ],
                            [
                                'attribute' => 'data_ora_sfarsit',
                                'value' => function ($model) {
                                    if (is_null($model->data_ora_sfarsit))
                                        return 'Nedefinit';
                                    else
                                        return $model->data_ora_sfarsit;
                                }
                            ],
                            [
                                'attribute' => 'detalii',
                                'value' => function ($model) {
                                    if (is_null($model->detalii))
                                        return 'Nu sunt detalii';
                                    else
                                        return $model->detalii;
                                }
                            ],
//                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summary' => '',
//                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]);
                    ?>
                    </p>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>