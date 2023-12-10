<?php

namespace App\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    /** @var ConstraintViolationListInterface|array<string, string>|array<string, string[]> $violations */
    private ConstraintViolationListInterface|array $violations;

    /**
     * @param ConstraintViolationListInterface|array<string, string>|array<string, string[]> $violations
     */
    public function __construct(ConstraintViolationListInterface|array $violations)
    {
        parent::__construct('Validation failed.');
        $this->violations = $violations;
    }

    /**
     * @return array<string, string[]>
     */
    public function getMessages(): array
    {
        $messages = [];
        if (is_array($this->violations)) {
            foreach ($this->violations as $k => $v) {
                $messages[$k] = is_array($v) ? $v : [$v];
            }
            return $messages;
        }

        foreach ($this->violations as $violation) {
            $path = $violation->getPropertyPath();
            if (!isset($messages[$path])) {
                $messages[$path] = [];
            }

            $messages[$path][] = $violation->getMessage();
        }

        return $messages;
    }
}
