<?php

declare(strict_types=1);

namespace App\Entity;

enum PaymentGatewayType: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
}
