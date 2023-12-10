<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Payment;
use App\Entity\PaymentGatewayType;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

final readonly class StripeProcessor implements PaymentGatewayInterface
{
    public function __construct(
        private StripePaymentProcessor $client,
        private LoggerInterface $logger
    ) {
    }

    public function getGatewayType(): PaymentGatewayType
    {
        return PaymentGatewayType::STRIPE;
    }

    public function process(Payment $payment): bool
    {
        $result = $this->client->processPayment($payment->getAmount() / 100);
        if (!$result) {
            $this->logger->emergency("Stripe payment has been failed", ['payment' => $payment]);
        }
        return $result;
    }
}
