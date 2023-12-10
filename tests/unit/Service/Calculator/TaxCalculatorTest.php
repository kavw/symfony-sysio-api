<?php

namespace unit\Service\Calculator;

use App\DTO\CalculateRequest;
use App\Entity\Coupon;
use App\Entity\CouponType;
use App\Entity\Product;
use App\Entity\Tax;
use App\Service\Calculator\DiscountCalculator;
use App\Service\Calculator\Exception\OrderException;
use App\Service\Calculator\CalculateRequestResult;
use App\Service\Calculator\TaxCalculator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group("unit")]
class TaxCalculatorTest extends TestCase
{
    public function testTaxWithoutDiscount(): void
    {
        $taxNumber = 'GR123456789';
        $dto = new CalculateRequest(
            new Product('Product', 10000),
            $taxNumber,
            new Tax('GR', 'XXXXXXXXX', 24),
            null
        );

        $prev = CalculateRequestResult::create($dto);
        $calculator = new TaxCalculator();
        $res = $calculator->calculate($dto, $prev);
        $this->assertEquals(12400, $res->amount);
        $this->assertEquals(10000, $res->productAmount);
        $this->assertEquals([$taxNumber => 2400], $res->taxes);
        $this->assertEquals([], $res->discounts);
        $this->assertEquals($dto, $res->request);
    }

    public function testTaxAfterDiscount(): void
    {
        $taxNumber = 'GR123456789';
        $couponName = 'TEST_PERCENT';
        $dto = new CalculateRequest(
            new Product('Product', 10000),
            $taxNumber,
            new Tax('GR', 'XXXXXXXXX', 24),
            new Coupon($couponName, CouponType::PERCENT, 6),
        );

        $prev = CalculateRequestResult::create($dto);
        $prev = (new DiscountCalculator())->calculate($dto, $prev);
        $res = (new TaxCalculator())->calculate($dto, $prev);
        $this->assertEquals(11656, $res->amount);
        $this->assertEquals(9400, $res->productAmount);
        $this->assertEquals([$taxNumber => 2256], $res->taxes);
        $this->assertEquals([$couponName => 600], $res->discounts);
        $this->assertEquals($dto, $res->request);
    }

    public function testCalculationOrder(): void
    {
        $dto = new CalculateRequest(
            new Product('Product', 10000),
            'GR123456789',
            new Tax('GR', 'XXXXXXXXX', 24),
            new Coupon('TEST_PERCENT', CouponType::PERCENT, 6),
        );

        $prev = CalculateRequestResult::create($dto);
        $prev = (new TaxCalculator())->calculate($dto, $prev);
        $this->expectException(OrderException::class);
        (new DiscountCalculator())->calculate($dto, $prev);
    }
}
