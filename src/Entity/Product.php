<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Product
{
    #[Id]
    #[GeneratedValue(strategy: "SEQUENCE")]
    #[Column(type: Types::INTEGER, options: ["unsigned" => true])]
    private ?int $id = null;

    public function __construct(
        #[Column(type: Types::STRING, length: 255)]
        private string $name,

        #[Column(type: Types::INTEGER, options: ["unsigned" => true])]
        private int $price,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
