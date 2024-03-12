<?php

/** @var yii\web\View $this */
/** @var kartik\form\ActiveForm $form */
/** @var \frontend\models\FormularComanda $model */
/** @var \frontend\models\Basket $basket */

use kartik\form\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Procesare comandă';
$model->tipLocuinta=1;
//$this->params['breadcrumbs'][] = $this->title;

$dataProviderCos = new \yii\data\ArrayDataProvider([
    'allModels' => $basket->basketItems,
]);

$total=0.00;
foreach($basket->basketItems as $bi){
    $total=$total+$bi->pret*$bi->cantitate;
}

$formatJs = <<< SCRIPT
       
$(document).ready(function() {
    // Change class of selected radio button group
    $('.ftco-appointment input[type="radio"]:checked').parent().removeClass('btn-outline-secondary').addClass('btn-warning');
    //$('#btn-adauga-in-cos').attr('data-id',$('.modal-body input[type="radio"]:checked').val());
});
function updateRadioButtonClass(element) {
    var radioButtons = $(element).closest('.btn-group').find('input[type=\"radio\"]');
    radioButtons.each(function() {
        var radioButton = $(this);
        if (radioButton.prop('checked')) {
            radioButton.parent().removeClass('btn-outline-secondary').addClass('btn-warning');
            var selectedValue=$(this).val();
            console.log(selectedValue);
            if(selectedValue==1){
                $('.bloc-details').slideUp();
            }
            else{
                $('.bloc-details').slideDown();
            }
            
        } else {
            radioButton.parent().removeClass('btn-warning').addClass('btn-outline-secondary');
        }
    });
}       
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);
?>



<div class="container ftco-appointment pt-5 pb-5">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <!-- Summary cart section -->
        <div class="col-lg-4 order-lg-2 order-1  pb-5">
            <!-- Summary cart content goes here -->
            <div class="summary-cart border border-warning rounded">
                <div class="d-flex border-bottom border-dashed border-warning">
                    <h4 class="pl-3 pt-3">Continut coș</h4>
                </div>
                <div class="d-flex pt-3">
                    <div class="col-md-4"><span class="font-weight-bold">Produs</span></div>
                    <div class="col-md-4"><span class="font-weight-bold">Cantitate</span></div>
                    <div class="col-md-4"><span class="font-weight-bold">Pret</span></div>
                </div>
                <?= $this->render('_list_view_cos', ['liniiDataProvider' => $dataProviderCos]) ?>
                <div class="d-flex border-top border-warning mt-2 pb-3 pt-3">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-right"><span class="price">Total:</span></div>
                    <div class="col-md-4"><span class="price"><?=number_format($total,2)?> RON</span></div>
                </div>
            </div>
        </div>

        <!-- Form section -->
        <div class="col-lg-8 order-lg-1 order-2">
            <!-- Form content goes here -->
            <div class="form-container">
                <?php $form = ActiveForm::begin(['id' => 'comanda-form','enableClientValidation'=>true]); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model,'nume')->input('text') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model,'prenume')->input('text') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model,'email')->input('text') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model,'telefon')->input('text') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($model,'oras')->input('text') ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model,'strada')->input('text') ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model,'numar')->input('text') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        $tipuriLocuinta=[1=>'La casă',2=>'La bloc'];
                        echo $form->field($model,'tipLocuinta')->radioButtonGroup($tipuriLocuinta,['onchange'=>'updateRadioButtonClass(this);'])->label(null,['style'=>'display:block;']);
                        ?>

                    </div>
                </div>
                <div class="d-flex bloc-details"  style="display: none !important;">
                    <div class="col-md-3">
                        <?= $form->field($model,'bloc')->input('text')?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model,'scara')->input('text')?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model,'apartament')->input('text')?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model,'interfon')->input('text')?>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Trimite comandă', ['class' => 'btn btn-primary', 'name' => 'comanda-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>