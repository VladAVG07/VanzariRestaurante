<?php

use frontend\themes\pizzagh\assets\PizzGhAsset;
use kartik\form\ActiveForm;
//use yii\bootstrap4\Modal;
//use yii\helpers\Html;
use kartik\touchspin\TouchSpin;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $form yii\bootstrap4\ActiveForm */
/* @var $basketIems[] models\BasketItem */

$assetDir = Yii::$app->assetManager->getBundle('frontend\themes\pizzagh\assets\PizzGhAsset');

$urlCategorie = \yii\helpers\Url::toRoute('site/schimba-categorie');
$urlProdus = Url::toRoute('site/afiseaza-produs');
$urlAdaugaProdus = Url::toRoute('site/adauga-in-cos');
$idProdus=Html::getInputId($model,'idProdus');
$csrlf = sprintf('\'%s\':\'%s\'', \Yii::$app->request->csrfParam, \Yii::$app->request->getCsrfToken());

$produs = backend\models\Produse::findOne(['id'=>$model->idProdus]);
$categoriiAsociate = backend\models\CategoriiAsociate::find()->where(['categorie'=>$produs->categorie])->all();

$formatJs = <<< SCRIPT
       
$(document).ready(function() {
    // Change class of selected radio button group
    $('.modal-body input[type="radio"]:checked').parent().removeClass('btn-outline-secondary').addClass('btn-warning');
    $('#btn-adauga-in-cos').attr('data-id',$('.modal-body input[type="radio"]:checked').val());
});
       
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);
$this->registerJs("
    function updateRadioButtonClass(element) {
        var radioButtons = $(element).closest('.btn-group').find('input[type=\"radio\"]');
        radioButtons.each(function() {
            var radioButton = $(this);
            if (radioButton.prop('checked')) {
                radioButton.parent().removeClass('btn-outline-secondary').addClass('btn-warning');
                var detaliiProduse = $('.detalii-produse-hidden');
                var selectedValue=$(this).val();
                detaliiProduse.each(function() {
                    if ($(this).val() == selectedValue) {
                        var dPret = $(this).data('d-pret');
                        // Now you can use the value of dPret as needed
                        console.log(dPret);
                        $('span.price').first().text(dPret);
                        $('#$idProdus').val(selectedValue);
                        $('#btn-adauga-in-cos').attr('data-id',selectedValue);
                        return false; // Exit the loop once a match is found
                    }
                });
            } else {
                radioButton.parent().removeClass('btn-warning').addClass('btn-outline-secondary');
            }
        });
    }
");
$form = ActiveForm::begin([
    'id' => 'produs-modal-form',
    // Add any necessary options here
]);

?>

<div class="cart-items item ">
    <ul class="list-unstyled">
        <li class="item">
            <div class="d-flex ftco-animate fadeInUp ftco-animated text align-items-center" data-id="7">
                <!-- <div class="desc col-12"> -->
                <!-- <div class="d-flex col-12 text align-items-center"> -->
                <div class="col-5">
                    <h5><span><?= $model->denumire ?></span></h5>
                </div>
                <div class="col-4 cos-produs align-items-center">
                    <?php
                    if($generare)
                        $model->idProdus=$model->produseDetalii[0]['id'];
                        echo $form->field($model, "idProdus", ['options' => ['class' => 'd-none']])->hiddenInput()->label(false);
                    if($generare && count($model->produseDetalii)>1){
                        $i=0;
                        foreach($model->produseDetalii as $pDetaliu){
                            echo Html::hiddenInput("detalii_produse[$i]",$pDetaliu['id'],['class'=>'detalii-produse-hidden','data-d-pret'=>sprintf('%s RON',$pDetaliu['pret'])]);
                            $i++;
                        }
                        $model->pretFinal=$model->produseDetalii[0]['id'];
                        echo $form->field($model,'pretFinal')->radioButtonGroup(ArrayHelper::map($model->produseDetalii,'id','descriere'),
                        ['onchange'=>'updateRadioButtonClass(this);'])->label(false);//'Tip baie',['style' => 'display:block;']);
                    }
                    echo $form->field($model, "cantitate")->widget(TouchSpin::classname(), [
                        'options' => ['class' => 'cos-produs-input', 'data-price' => $model->pret, 'data-produs' => $model->idProdus],
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
                <div class="col-3 text-left"><span class="price"><?= $generare?$model->produseDetalii[0]['pret']:$model->pret ?> RON</span></div>
            </div>
            <!-- </div> -->
            <!-- </div> -->
        </li>
    </ul>
</div>

<div class="p-3">
    <div>
        <h5>Alte produse sugerate</h5>
        
    </div>
    <div class="form-row">

    </div>
</div>