<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ProductValidator extends ConstraintValidator
{
    public function __construct(
        readonly private ProductRepository $productRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Product) {
            throw new UnexpectedTypeException($constraint, Product::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        $entity = $this->productRepository->find($value);
        if (!$entity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ product }}', (string) $value)
                ->addViolation();
        }
    }
}
