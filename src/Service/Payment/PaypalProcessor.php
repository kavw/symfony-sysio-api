<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Entity\Payment;
use App\Entity\PaymentGatewayType;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

final readonly class PaypalProcessor implements PaymentGatewayInterface
{
    public function __construct(
        private PaypalPaymentProcessor $client,
        private LoggerInterface $logger
    ) {
    }

    public function getGatewayType(): PaymentGatewayType
    {
        return PaymentGatewayType::PAYPAL;
    }

    public function process(Payment $payment): bool
    {
        try {
            $this->client->pay($payment->getAmount());
            return true;
        } catch (\Exception $exception) {
            $this->logger->emergency("Paypal payment has been failed", [
                'payment' => $payment,
                'exception' => $exception->getMessage()
            ]);
            return false;
        }
    }
}
