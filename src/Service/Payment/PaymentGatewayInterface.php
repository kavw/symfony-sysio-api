<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Payment;
use App\Entity\PaymentGatewayType;

interface PaymentGatewayInterface
{
    public function getGatewayType(): PaymentGatewayType;

    public function process(Payment $payment): bool;
}
