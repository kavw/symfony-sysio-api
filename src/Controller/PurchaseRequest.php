<?php

namespace App\Controller;

use App\Entity\PaymentGatewayType;
use Symfony\Component\Validator\Constraints as Assert;

readonly class PurchaseRequest extends CalculatePriceRequest
{
    #[Assert\NotNull]
    public ?PaymentGatewayType $paymentProcessor;

    public function __construct(
        ?int $product = null,
        ?string $taxNumber = null,
        ?string $couponCode = null,
        ?PaymentGatewayType $paymentProcessor = null,
    ) {
        parent::__construct($product, $taxNumber, $couponCode);
        $this->paymentProcessor = $paymentProcessor;
    }
}
