<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use App\Service\TaxResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class TaxNumberValidator extends ConstraintValidator
{
    public function __construct(
        readonly private TaxResolver $taxResolver
    ) {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof TaxNumber) {
            throw new UnexpectedTypeException($constraint, TaxNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            $this->taxResolver->resolve($value);
        } catch (\InvalidArgumentException $exception) {
            $this->context->buildViolation($exception->getMessage())
                ->addViolation();
        }
    }
}
