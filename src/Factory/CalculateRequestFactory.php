<?php

namespace App\Factory;

use App\Controller\CalculatePriceRequest;
use App\DTO\CalculateRequest;
use App\Entity\Product;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\TaxResolver;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class CalculateRequestFactory
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private TaxResolver $taxResolver
    ) {
    }

    public function create(CalculatePriceRequest $request): CalculateRequest
    {
        /** @var Product|null $product */
        $product = $this->productRepository->find($request->product);
        if (!$product) {
            throw new \InvalidArgumentException(
                "Product '{$request->product}' not found"
            );
        }

        if (!$request->taxNumber) {
            throw new \LogicException("The request must be validated");
        }

        $tax = $this->taxResolver->resolve($request->taxNumber);


        if (!$request->couponCode) {
            return new CalculateRequest($product, $request->taxNumber, $tax, null);
        }

        $coupon = $this->couponRepository->findByCode($request->couponCode);
        if (!$coupon) {
            throw new \InvalidArgumentException(
                "Coupon '{$request->couponCode}' not found"
            );
        }

        return new CalculateRequest($product, $request->taxNumber, $tax, $coupon);
    }
}
