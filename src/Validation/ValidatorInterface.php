<?php

declare(strict_types=1);

namespace App\Validation;

interface ValidatorInterface
{
    /**
     * @param object $object
     * @return void
     * @throws ValidationException
     */
    public function validate(object $object): void;
}
