<?php

namespace backend\components;

use Yii;
use yii\base\Component;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeComponent extends Component
{
    public function setApiKey($apiKey)
    {
        Stripe::setApiKey($apiKey);
    }

    public function createPaymentIntent($amount, $productDetails, $currency = 'ron')
    {
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => [
                'product_name' => $productDetails['name'],
                'product_description' => $productDetails['description'],
                'product_quantity' => $productDetails['quantity'],
            ],
        ]);
    }
    public function confirmPaymentIntent($paymentIntentId, $paymentMethodId)
    {
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        $paymentIntent->confirm([
            'payment_method' => $paymentMethodId,
        ]);

        return $paymentIntent;
    }
}
