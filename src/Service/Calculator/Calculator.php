<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\DTO\CalculateRequest;

readonly class Calculator
{
    public function __construct(
        /** @var PaymentCalculatorInterface[] */
        private iterable $calculators = [
            new DiscountCalculator(),
            new TaxCalculator()
        ]
    ) {
    }

    public function calculate(CalculateRequest $dto): CalculateRequestResult
    {
        $result = CalculateRequestResult::create($dto);
        foreach ($this->calculators as $calculator) {
            $result = $calculator->calculate($dto, $result);
        }

        return $result;
    }
}
