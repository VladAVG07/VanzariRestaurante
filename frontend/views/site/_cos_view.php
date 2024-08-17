<?php

use frontend\themes\pizzagh\assets\PizzGhAsset;
//use yii\bootstrap4\Modal;
//use yii\helpers\Html;
use kartik\touchspin\TouchSpin;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $form yii\bootstrap4\ActiveForm */
/* @var $basketIems[] models\BasketItem */


$urlCategorie = \yii\helpers\Url::toRoute('site/schimba-categorie');
$urlProdus = Url::toRoute('site/afiseaza-produs');
$urlAdaugaProdus = Url::toRoute('site/adauga-in-cos');
$csrlf = sprintf('\'%s\':\'%s\'', \Yii::$app->request->csrfParam, \Yii::$app->request->getCsrfToken());
$empty = false;
if (!is_null($model))
    $empty = count($model->basketItems) > 0;
$formatJs = <<< SCRIPT

       
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);
$form = ActiveForm::begin([
    'id' => 'cos-modal-form',
    // Add any necessary options here
]);


if ($empty) {
    ?>
        <!-- <div class="d-flex justify-content-center mb-3 position-relative">
            <div class="spinner-border text-primary" id="loadingSpinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="position-absolute top-50 start-50 translate-middle text-warning">Loading...</span>
        </div> -->
        
    <?php
    } else {
    ?>
        <h4 class="text-warning text-center pt-3 pb-3">Coșul dumneavoastră este gol!</h4>
    <?php }
    ?>
<div class="cart-items item ">
    <ul class="list-unstyled">
        <?php
        $i = 0;
        foreach ($model->basketItems as $basketItem) {
            if ($basketItem->denumire != 'Cost livrare'){
        ?>
            
            <li class="item <?= $i > 0 ? 'cos-entry pt-2' : '' ?>">
                <div class="d-flex ftco-animate fadeInUp ftco-animated text" data-id="7">
                    <!-- <div class="desc col-12"> -->
                    <!-- <div class="d-flex col-12 text align-items-center"> -->
                    <div class="col-8">
                        <h5><span><?= $basketItem->denumire ?></span></h5>
                    </div>

                    <div class="col-4 cos-produs">
                        <div class="float-right text-right">
                            <span class="price"><?= $basketItem->pret ?> RON</span>
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
                    </div>
                    <!-- <div class="col-3"></div> -->
                </div>
                <!-- </div> -->
                <!-- </div> -->
            </li>
        <?php
            $i++;
            }
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
            [0 => 'Plata la livrare', 1 => 'Ridicare personală'],
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

<div class="butoane p-3" style="border-top: 1px solid #e9ecef; display: flex; justify-content: flex-end;">
<?php
echo Html::button('Continuă cumpărăturile', ['class' => ['btn', 'btn-white btn-outline-white p-3 px-xl-4 py-xl-3'], 'data-dismiss' => 'modal']);
if ($empty) {
    echo Html::button('La casă 0.00 RON', ['class' => ['btn', 'btn-primary p-3 px-xl-4 py-xl-3 btn-casa'], 'data-dismiss' => 'modal', 'data-total' => '0.00']);
}
?>
</div>