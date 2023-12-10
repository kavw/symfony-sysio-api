<?php

namespace App\DTO\Response;

readonly class Error
{
    public function __construct(
        public string $error,
        /** @var array<string, string[]> $details */
        public ?array $details
    ) {
    }
}
