<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Payment';

$stripePublicKey = 'pk_test_51PWJWpAsMoeIDeIqhvk4PqqzQX1X7elsIrjxo2Ai3QfSe18y1gIlnp2TRVNuU5YHEzrmXrZwVSzvLPG9cQsMGu6600NiM5fm01';

$this->registerJsFile('https://js.stripe.com/v3/');
$this->registerJs(<<<JS
var stripe = Stripe('$stripePublicKey');
var elements = stripe.elements();
var cardElement = elements.create('card');
cardElement.mount('#card-element');

document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const { paymentMethod, error } = await stripe.createPaymentMethod('card', cardElement);
    if (error) {
        // Display error.message in your UI
    } else {
        document.getElementById('payment-method-id').value = paymentMethod.id;
        document.getElementById('payment-form').submit();
    }
});
JS
);

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="site-payment">
    <?php $form = ActiveForm::begin(['id' => 'payment-form']); ?>

    <div id="card-element"></div>
    <input type="hidden" id="payment-method-id" name="paymentMethodId">
    <input type="hidden" id="restaurant-id" name="restaurantId" value="<?= Html::encode($restaurant->id) ?>">

    <div class="form-group">
        <?= Html::label('Product Name', 'productName') ?>
        <?= Html::textInput('productName', '', ['class' => 'form-control']) ?>
    </div>
    
    <div class="form-group">
        <?= Html::label('Product Description', 'productDescription') ?>
        <?= Html::textInput('productDescription', '', ['class' => 'form-control']) ?>
    </div>
    
    <div class="form-group">
        <?= Html::label('Product Quantity', 'productQuantity') ?>
        <?= Html::textInput('productQuantity', '', ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Pay', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>