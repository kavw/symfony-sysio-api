<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;

final readonly class CalculateRequest
{
    public function __construct(
        public Product $product,
        public string $taxNumber,
        public Tax $tax,
        public ?Coupon $coupon
    ) {
    }
}
