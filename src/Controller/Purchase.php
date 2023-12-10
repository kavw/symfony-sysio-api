<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\CalculateRequestFactory;
use App\Service\Calculator\Calculator;
use App\Service\Payment\Processor;
use App\Validation\ValidationException;
use App\ValueResolver\RequestResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

final class Purchase extends AbstractController
{
    public function __construct(
        readonly private CalculateRequestFactory $factory,
        readonly private Calculator $calculator,
        readonly private Processor $processor,
    ) {
    }

    #[Route("/purchase", methods: ["POST"])]
    public function __invoke(
        #[ValueResolver(RequestResolver::class)]
        PurchaseRequest $request,
    ): Response {
        $dto = $this->factory->create($request);
        $calcResult = $this->calculator->calculate($dto);

        if (!$request->paymentProcessor) {
            throw new \LogicException("The request must be validated");
        }

        $result = $this->processor->pay($request->paymentProcessor, $calcResult);
        if (!$result) {
            throw new \DomainException("Payment has been failed");
        }

        return new Response();
    }
}
