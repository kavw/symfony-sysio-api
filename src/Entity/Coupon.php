<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Coupon
{
    #[Id]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[Column(type: Types::INTEGER, options: ["unsigned" => true])]
    private ?int $id = null;

    public function __construct(
        #[Column(type: Types::STRING, length: 20, unique: true)]
        readonly private string $code,

        #[Column(type: Types::INTEGER, enumType: CouponType::class)]
        readonly private CouponType $type,

        #[Column(type: Types::INTEGER)]
        readonly private int $value,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): CouponType
    {
        return $this->type;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
