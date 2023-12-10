<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\DTO\CalculateRequest;

interface PaymentCalculatorInterface
{
    public function calculate(CalculateRequest $dto, CalculateRequestResult $prev): CalculateRequestResult;
}
