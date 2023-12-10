<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\DTO\CalculateRequest;
use App\Service\Calculator\Exception\OrderException;
use App\Service\Calculator\Exception\ZeroAmountException;

final readonly class CalculateRequestResult
{
    private function __construct(
        public CalculateRequest $request,
        public int $productAmount,
        public int $amount,
        /** @var array{name: string, value: int}|array{} $discounts */
        public array $discounts,
        /** @var array{name: string, value: int}|array{} $taxes */
        public array $taxes,
    ) {
    }

    public static function create(CalculateRequest $request): self
    {
        $price = $request->product->getPrice();
        return new self($request, $price, $price, [], []);
    }

    public function withDiscount(string $name, int $value): CalculateRequestResult
    {
        if ($this->taxes) {
            throw new OrderException("It's not correct to apply discounts after taxation");
        }

        $productAmount = $this->productAmount - $value;
        if ($productAmount < 1) {
            throw new ZeroAmountException(
                "The amount became 0 when the discount is applied"
            );
        }

        return new self(
            $this->request,
            $productAmount,
            $productAmount,
            [...$this->discounts, ...[$name => $value]],
            $this->taxes
        );
    }

    public function withTax(string $name, int $value): CalculateRequestResult
    {
        return new self(
            $this->request,
            $this->productAmount,
            $this->amount + $value,
            $this->discounts,
            [...$this->taxes, ...[$name => $value]]
        );
    }

    public function getFormattedAmount(): float
    {
        return $this->amount / 100;
    }
}
