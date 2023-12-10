<?php

namespace App\Service;

use App\Entity\Tax;
use App\Repository\TaxRepository;

final readonly class TaxResolver
{
    public function __construct(
        private TaxRepository $repository
    ) {
    }

    /**
     * @param string $taxNumber
     * @return Tax
     * @throws \InvalidArgumentException
     */
    public function resolve(string $taxNumber): Tax
    {
        if ($taxNumber < 3) {
            throw new \InvalidArgumentException("The given tax number '{$taxNumber}' too small");
        }

        $code = substr($taxNumber, 0, 2);
        $tax = $this->repository->findByCode($code);
        if (!$tax) {
            throw new \InvalidArgumentException("Can't find entity for the given tax number '{$taxNumber}'");
        }

        if (!preg_match($this->makeRegexPattern($tax), $taxNumber)) {
            throw new \InvalidArgumentException("The given tax number '{$taxNumber}' has invalid format");
        }

        return $tax;
    }

    private function makeRegexPattern(Tax $tax): string
    {
        $pattern = trim($tax->getPattern());
        if (!preg_match('/^Y*X+$/', $pattern)) {
            throw new \RuntimeException("The given tax pattern '{$pattern}' is invalid");
        }

        return '/^' . $tax->getCode() . str_replace(['Y', 'X'], ['[A-Za-z]', '\d'], $pattern) . '$/';
    }
}
