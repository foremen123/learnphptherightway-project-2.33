<?php

namespace App\Services;

class PaddlePayment implements PaymentGatewayServiceInterface
{

    public function charge(array $customer, float $amount, float $tax): bool
    {
        echo'Processing payment through Paddle...';

        return true;
    }
}