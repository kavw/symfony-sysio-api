<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Tax
{
    #[Id]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[Column(type: Types::INTEGER, options: ["unsigned" => true])]
    private ?int $id = null;

    public function __construct(
        #[Column(type: Types::STRING, length: 2, unique: true)]
        private string $code,

        #[Column(type: Types::STRING, length: 20)]
        private string $pattern,

        #[Column(type: Types::INTEGER, options: ["unsigned" => true])]
        private int $value,
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

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
