<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\DTO\CalculateRequest;

class TaxCalculator implements PaymentCalculatorInterface
{
    public function calculate(CalculateRequest $dto, CalculateRequestResult $prev): CalculateRequestResult
    {
        $taxValue = $dto->tax->getValue();
        if ($taxValue <= 0 || $taxValue >= 100) {
            throw new \LogicException("Invalid tax value");
        }

        $taxValue =  (int) round($prev->productAmount * $taxValue / 100);
        return $prev->withTax($dto->taxNumber, $taxValue);
    }
}
