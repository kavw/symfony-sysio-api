<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[Entity]
class Payment
{
    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $completedAt;

    public function __construct(
        #[Id]
        #[Column(type: UuidType::NAME, unique: true)]
        readonly private Uuid $id,

        #[Column(type: Types::INTEGER)]
        readonly private int $amount,

        #[Column(type: Types::STRING, length: 20, enumType: PaymentGatewayType::class)]
        readonly private PaymentGatewayType $gateway,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly private \DateTimeImmutable $createdAt,

        /** @var array<string, mixed> $paymentCalcResult */
        #[Column(type: Types::JSON, options: ['jsonb' => true])]
        readonly private array $paymentCalcResult,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getGateway(): PaymentGatewayType
    {
        return $this->gateway;
    }

    public function getCreateAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function markAsCompleted(\DateTimeImmutable $val): void
    {
        $this->completedAt = $val;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPaymentCalcResult(): array
    {
        return $this->paymentCalcResult;
    }
}
