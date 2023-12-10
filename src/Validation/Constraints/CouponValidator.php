<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use App\Repository\CouponRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class CouponValidator extends ConstraintValidator
{
    public function __construct(
        readonly private CouponRepository $couponRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Coupon) {
            throw new UnexpectedTypeException($constraint, Coupon::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = trim($value);
        if ('' === $value) {
            return;
        }

        $entity = $this->couponRepository->findByCode($value);
        if (!$entity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ coupon }}', $value)
                ->addViolation();
        }
    }
}
