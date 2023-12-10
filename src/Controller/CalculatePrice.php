<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\CalculateRequestFactory;
use App\Service\Calculator\Calculator;
use App\ValueResolver\RequestResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

final class CalculatePrice extends AbstractController
{
    public function __construct(
        readonly private CalculateRequestFactory $factory,
        readonly private Calculator $calculator
    ) {
    }

    #[Route("/calculate-price", methods: ["POST"])]
    public function __invoke(
        #[ValueResolver(RequestResolver::class)]
        CalculatePriceRequest $request
    ): JsonResponse {
        $request = $this->factory->create($request);
        $result = $this->calculator->calculate($request);
        return $this->json(
            $result
        );
    }
}
