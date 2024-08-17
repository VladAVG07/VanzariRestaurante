<?php

/** @var yii\web\View $this */
/** @var kartik\form\ActiveForm $form */
/** @var \frontend\models\FormularComanda $model */
/** @var \frontend\models\Basket $basket */

use kartik\form\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$telefon='+40722 885 551';
$numarComanda='1234444';
$dataOraComanda='17.03.2024 15:45';

$dataProviderCos = new \yii\data\ArrayDataProvider([
    'allModels' => $basket->basketItems,
]);

$total = 0.00;
foreach ($basket->basketItems as $bi) {
    $total = $total + $bi->pret;
}
?>

<div class="container">
    <div style="border-radius:10px; border: 1px solid #fff;padding:10px;background-color:#000">
        <h5 style="color:red">Acesta este un mail generat automat. Vă rugăm nu răspundeți.</h5>
        <h5>Dacă doriți să vă modificați sau să vă anulați comanda, vă rugăm să ne contactați telefonic. <?=$telefon?></h5>
    </div>
    
        <p>Comanda numărul #<?=$numarComanda?> a fost primită! Mulțumim!</p>
    <div class="row">
        <p>Mâncarea va fi la tine la <?=$dataOraComanda?></p>
    </div>
    <div class="row">
        <p>Datele tale de contact : <span>Radu Marian, radumrn@gmail.com, 0726213098</span></p>
    </div>
    <div class="row">
        <p>Comanda se va livra la : <span></span></p>
    </div>
    <div class="row">
        <p>Plată: <span>La livrare</span></p>
    </div>
    <div class="row">
        <!-- Summary cart section -->
        <div class="col-md-12 col-12 pb-5">
            <!-- Summary cart content goes here -->
            <div class="summary-cart border border-warning rounded">
                <div class="d-flex border-bottom border-dashed border-warning">
                    <h4 class="pl-3 pt-3">Continut coș</h4>
                </div>
                <div class="d-flex pt-3">
                    <div class="col-md-6 col-6"><span class="font-weight-bold">Produs</span></div>
                    <div class="col-md-3 col-3 text-right"><span class="font-weight-bold">Cantitate</span></div>
                    <div class="col-md-3 col-3 text-right"><span class="font-weight-bold">Pret</span></div>
                </div>
                <?= $this->render('_list_view_cos', ['liniiDataProvider' => $dataProviderCos]) ?>
                <div class="d-flex border-top border-warning mt-2 pb-3 pt-3">
                    <div class="col-md-5 col-5"><span class="price font-weight-bold">Total:</span></div>
                    <div class="col-md-7 col-7 text-right"><span class="price font-weight-bold"><?= number_format($total, 2) ?> RON</span></div>
                </div>
            </div>
        </div>

    </div>
</div>