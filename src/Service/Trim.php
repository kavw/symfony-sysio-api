<?php

declare(strict_types=1);

namespace App\Service;

class Trim
{
    public function __invoke(string $value): string
    {
        return trim($value);
    }
}
