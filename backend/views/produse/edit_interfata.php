<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$this->title = Yii::t('app', 'Administreaza ordine categorii');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Produse'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$salveazaConfig = \yii\helpers\Url::toRoute('produse/modifica-ordine');

$jsCode = <<<SCRIPT
  $('#draggable-area').sortable({
            items: '.draggable-item',
            // Add other sortable options as needed
            update: function (event, ui) {
                // Update the hidden input with the new order
                updateSortableList();
            }
        });
      function updateSortableList() {
            var items = $('#draggable-area .draggable-item').map(function() {
                return $(this).data('id');
            }).get();
            $('#ordinecategoriiform-ordinecategorii').val(items.join(','));
            console.log(items.join(','));
        }
//    $('.btn-success').on('click',function(){
//        var elements = document.getElementsByClassName("btn-app");
//        var dataIds = [];
//        for (var i = 0; i < elements.length; i++) {
//            var dataId = elements[i].getAttribute("data-id");
//            dataIds.push(dataId);
//        }
//       // console.log(dataIds);
//        $.ajax({// create an AJAX call...
//                data: {'ordine':dataIds[0]}, // get the form data
//                type: 'GET', // GET or POST
//                url: '$salveazaConfig', // the file to call
//                success: function (data) { // on success..
//                    console.log('succes');
//                }
//        });
//    });
SCRIPT;
$this->registerJs($jsCode, yii\web\View::POS_END);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="produse-form">

                        <?php
                        $form = ActiveForm::begin([
                                    'options' => ['name' => 'produse-form']
                                        ]
                        );
                        echo $form->field($model, 'ordineCategorii')->hiddenInput()->label(false);
                        ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="draggable-area" style="border-radius: 5px;padding-top: 10px; border:1px solid #cecece;">
                                    <?php
                                    $categorii = \backend\models\Categorii::find()
                                            ->innerJoin('produse p', 'p.categorie = categorii.id')
                                            //->innerJoin('categorii c', 'c.parinte = categorii.id')
                                            ->innerJoin('restaurante_categorii rc', 'rc.categorie=categorii.id')
                                            ->innerJoin('restaurante r', 'rc.restaurant=r.id')
                                            ->innerJoin('restaurante_user ru', 'ru.restaurant=r.id')
                                            ->innerJoin('user u', 'ru.user=u.id')
                                            ->where(['u.id' => \Yii::$app->user->id])->andWhere(['<>', 'categorii.nume', 'Servicii'])->andWhere(['categorii.parinte' => null])
                                            ->all();
                                    $categorii1 = \backend\models\Categorii::find()
                                            //  ->innerJoin('produse p', 'p.categorie = categorii.id')
                                            ->innerJoin('categorii c', 'c.parinte = categorii.id')
                                            ->innerJoin('restaurante_categorii rc', 'rc.categorie=categorii.id')
                                            ->innerJoin('restaurante r', 'rc.restaurant=r.id')
                                            ->innerJoin('restaurante_user ru', 'ru.restaurant=r.id')
                                            ->innerJoin('user u', 'ru.user=u.id')
                                            ->where(['u.id' => \Yii::$app->user->id])->andWhere(['<>', 'categorii.nume', 'Servicii'])->andWhere(['categorii.parinte' => null])
                                            ->all();
                                    $categorii = array_merge($categorii, $categorii1);

                                    function customSort($a, $b) {
                                        // Compare by 'ordine' property first
                                        $ordineComparison = $a['ordine'] - $b['ordine'];

                                        // If 'ordine' values are equal, compare by 'id' ascending
                                        return ($ordineComparison !== 0) ? $ordineComparison : ($a['id'] - $b['id']);
                                    }

// Use usort to sort the merged array using the custom comparison function
                                    usort($categorii, 'customSort');
                                    foreach ($categorii as $categorie) {
                                        //    echo $categorie->nume;
                                        echo Html::a($categorie->nume, sprintf('#%s', yii\helpers\Inflector::slug($categorie->nume)), ['data-id' => $categorie->id, 'class' => 'btn btn-app draggable-item', 'style' => 'width:200px;height:100px;
    line-height:70px;display: inline-block;
    margin-right: 10px;']);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success right float-right']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>