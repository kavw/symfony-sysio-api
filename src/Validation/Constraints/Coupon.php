<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Coupon extends Constraint
{
    public string $message = 'The coupon "{{ coupon }}" not found.';

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}
