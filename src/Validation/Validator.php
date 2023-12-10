<?php

namespace App\Validation;

use Symfony\Component\Validator\Validator\ValidatorInterface as VendorValidator;

final readonly class Validator implements ValidatorInterface
{
    public function __construct(
        private VendorValidator $validation
    ) {
    }

    public function validate(object $object): void
    {
        $errors = $this->validation->validate($object);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
