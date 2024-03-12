<?php

use frontend\themes\pizzagh\assets\PizzGhAsset;
//use yii\bootstrap4\Modal;
//use yii\helpers\Html;
use kartik\touchspin\TouchSpin;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $form yii\bootstrap4\ActiveForm */
/* @var $basketIems[] models\BasketItem */


$urlCategorie = \yii\helpers\Url::toRoute('site/schimba-categorie');
$urlProdus = Url::toRoute('site/afiseaza-produs');
$urlAdaugaProdus = Url::toRoute('site/adauga-in-cos');
$csrlf = sprintf('\'%s\':\'%s\'', \Yii::$app->request->csrfParam, \Yii::$app->request->getCsrfToken());
$formatJs = <<< SCRIPT
       

       
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);
$form = ActiveForm::begin([
    'id' => 'cos-modal-form',
    // Add any necessary options here
]);

?>

<div class="cart-items item ">
    <ul class="list-unstyled">
        <?php
        $i = 0;
        foreach ($model->basketItems as $basketItem) {
        ?>
            <li class="item">
                <div class="d-flex ftco-animate fadeInUp ftco-animated text align-items-center" data-id="7">
                    <!-- <div class="desc col-12"> -->
                    <!-- <div class="d-flex col-12 text align-items-center"> -->
                    <div class="col-5">
                        <h5><span><?= $basketItem->denumire ?></span></h5>
                    </div>
                    <div class="col-4 cos-produs align-items-center">
                        <?php
                        echo $form->field($basketItem, "[$i]idProdus", ['options' => ['class' => 'd-none']])->hiddenInput()->label(false);
                        echo $form->field($basketItem, "[$i]cantitate")->widget(TouchSpin::classname(), [
                            'options' => ['class' => 'cos-produs-input', 'data-price' => $basketItem->pret, 'data-produs' => $basketItem->idProdus],
                            'pluginOptions' => [
                                'initval' => 1,
                                'min' => 1,
                                'max' => 100,
                                'buttonup_class' => 'h-50 btn btn-block btn-sm btn-primary',
                                'buttondown_class' => 'h-50 btn btn-sm btn-info',
                                'buttonup_txt' => '+',
                                'buttondown_txt' => '-',
                                // 'verticalbuttons' => true
                            ],
                            // 'pluginEvents' => [
                            //     "touchspin.on.startspin " => "function() { getTotal(); }",
                            //     "touchspin.on.startupspin" => "function() { getTotal(); }",
                            // ]
                        ])->label(false);
                        ?>
                    </div>
                    <div class="col-3"><p class="price"><span><?= $basketItem->pret ?></span></p></div>
                </div>
                <!-- </div> -->
                <!-- </div> -->
            </li>
        <?php
            $i++;
        }
        ?>
    </ul>
</div>

<div class="selecteaza-varianta p-3">
    <div>
        <h5>Metoda de plată a comenzii</h5>
    </div>
    <div class="form-row">
        <?php
        echo $form->field($model, 'metodaPlata')->radioList(
            [0 => 'Plata online cu cardul', 1 => 'Plata la livrare', 2 => 'Ridicare personală'],
            [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checked = $checked ? 'checked' : '';
                    return "<div class='form-check'><input class='form-check-input' type='radio' name='$name' value='$value' $checked><label for='$name'> $label</label></div>";
                },
                'class' => 'radio-list' // Optional, add a class to the radio list container
            ]
        )->label((false));

        ?>

    </div>
</div>