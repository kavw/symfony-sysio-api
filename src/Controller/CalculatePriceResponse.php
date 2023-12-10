<?php

namespace App\Controller;

final readonly class CalculatePriceResponse
{
    public function __construct(
        public int $amount
    ) {
    }
}
