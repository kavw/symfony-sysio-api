<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\DTO\CalculateRequest;
use App\Entity\CouponType;

class DiscountCalculator implements PaymentCalculatorInterface
{
    public function calculate(CalculateRequest $dto, CalculateRequestResult $prev): CalculateRequestResult
    {
        if (!$dto->coupon) {
            return $prev;
        }

        if ($dto->coupon->getType() === CouponType::FIXED) {
            return $prev->withDiscount($dto->coupon->getCode(), $dto->coupon->getValue());
        }

        $couponValue = $dto->coupon->getValue();
        if ($couponValue >= 100) {
            throw new \RuntimeException("Invalid coupon value");
        }

        $discountValue = (int) round($dto->product->getPrice() * $dto->coupon->getValue() / 100);
        return $prev->withDiscount($dto->coupon->getCode(), $discountValue);
    }
}
