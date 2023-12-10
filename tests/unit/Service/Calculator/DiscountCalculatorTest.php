<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Calculator;

use App\DTO\CalculateRequest;
use App\Entity\Coupon;
use App\Entity\CouponType;
use App\Entity\Product;
use App\Entity\Tax;
use App\Service\Calculator\DiscountCalculator;
use App\Service\Calculator\Exception\ZeroAmountException;
use App\Service\Calculator\CalculateRequestResult;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group("unit")]
class DiscountCalculatorTest extends TestCase
{
    public function testFixedDiscount(): void
    {
        $couponName = 'TEST_FIX';
        $dto = new CalculateRequest(
            new Product('Product', 10000),
            'DE123456789',
            new Tax('DE', 'XXXXXXXXX', 19),
            new Coupon($couponName, CouponType::FIXED, 1000),
        );

        $prev = CalculateRequestResult::create($dto);

        $calculator = new DiscountCalculator();
        $result = $calculator->calculate($dto, $prev);

        $this->assertEquals(9000, $result->amount);
        $this->assertEquals(9000, $result->productAmount);
        $this->assertEquals([$couponName => 1000], $result->discounts);
        $this->assertEquals([], $result->taxes);
        $this->assertEquals($dto, $result->request);
    }

    public function testFixedDiscountTooLarge(): void
    {
        $dto = new CalculateRequest(
            new Product('Product', 10000),
            'DE123456789',
            new Tax('DE', 'XXXXXXXXX', 19),
            new Coupon('TEST_FIX', CouponType::FIXED, 10000),
        );

        $prev = CalculateRequestResult::create($dto);

        $calculator = new DiscountCalculator();

        $this->expectException(ZeroAmountException::class);
        $calculator->calculate($dto, $prev);
    }

    public function testPercentDiscount(): void
    {
        $couponName = 'TEST_PERCENT';
        $dto = new CalculateRequest(
            new Product('Product', 10000),
            'DE123456789',
            new Tax('DE', 'XXXXXXXXX', 19),
            new Coupon($couponName, CouponType::PERCENT, 6),
        );

        $prev = CalculateRequestResult::create($dto);

        $calculator = new DiscountCalculator();
        $result = $calculator->calculate($dto, $prev);

        $this->assertEquals(9400, $result->amount);
        $this->assertEquals(9400, $result->productAmount);
        $this->assertEquals([$couponName => 600], $result->discounts);
        $this->assertEquals([], $result->taxes);
        $this->assertEquals($dto, $result->request);
    }

    public function testPercentDiscountGivenInvalidCoupon(): void
    {
        $dto = new CalculateRequest(
            new Product('Product', 10000),
            'DE123456789',
            new Tax('DE', 'XXXXXXXXX', 19),
            new Coupon('TEST_PERCENT', CouponType::PERCENT, 100),
        );

        $prev = CalculateRequestResult::create($dto);

        $calculator = new DiscountCalculator();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid coupon value');
        $calculator->calculate($dto, $prev);
    }
}
