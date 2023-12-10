<?php

namespace App\Controller;

use App\Validation\Constraints\Coupon;
use App\Validation\Constraints\Product;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validation\Constraints\TaxNumber;

readonly class CalculatePriceRequest
{
    #[Assert\NotNull]
    #[Assert\Range(min: 1)]
    #[Product]
    public ?int $product;

    #[Assert\NotNull]
    #[TaxNumber]
    public ?string $taxNumber;
    #[Coupon]
    public ?string $couponCode;

    public function __construct(
        ?int $product = null,
        ?string $taxNumber = null,
        ?string $couponCode = null,
    ) {
        $this->product = $product;
        $this->taxNumber = $taxNumber ? trim($taxNumber) : null;
        $this->couponCode = $couponCode ? trim($couponCode) : null;
    }
}
