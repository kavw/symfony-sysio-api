<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Payment;
use App\Entity\PaymentGatewayType;
use App\Service\Calculator\CalculateRequestResult;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

final readonly class Processor
{
    public function __construct(
        private SerializerInterface $serializer,
        private UuidFactory $uuidFactory,
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
        /** @var PaymentGatewayInterface[] $gatewayProcessors */
        private iterable $gatewayProcessors
    ) {
    }

    public function pay(PaymentGatewayType $gateway, CalculateRequestResult $calcResult): bool
    {
        $processor = $this->resolveGatewayProcessor($gateway);

        $payment = new Payment(
            $this->uuidFactory->create(),
            $calcResult->amount,
            $processor->getGatewayType(),
            $this->clock->now(),
            (array) json_decode(
                $this->serializer->serialize($calcResult, 'json'),
                associative: true
            )
        );

        $this->entityManager->persist($payment);
        $this->entityManager->flush();
        $result = $processor->process($payment);
        if ($result) {
            $payment->markAsCompleted($this->clock->now());
            $this->entityManager->flush();
        }

        return $result;
    }

    private function resolveGatewayProcessor(PaymentGatewayType $gateway): PaymentGatewayInterface
    {
        foreach ($this->gatewayProcessors as $processor) {
            if ($processor->getGatewayType() === $gateway) {
                return $processor;
            }
        }

        throw new \DomainException("Unsupported payment gateway '{$gateway->value}'");
    }
}
