<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Product extends Constraint
{
    public string $message = 'The product "{{ product }}" not found.';


    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}
