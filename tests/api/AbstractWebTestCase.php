<?php

declare(strict_types=1);

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    /**
     * @param string $path
     * @param array<string, mixed> $data
     * @param string $delimiter
     * @return mixed
     */
    protected function getArrayValue(string $path, array $data, string $delimiter = '.'): mixed
    {
        $path = trim($path);
        if ($path === '') {
            throw new \InvalidArgumentException("Path is empty");
        }

        $delimiter = trim($delimiter);
        if ($delimiter === '') {
            throw new \InvalidArgumentException("Delimiter is empty");
        }

        $parts = explode($delimiter, $path);
        $current = $data;
        for ($i = 0; $i < count($parts); $i++) {
            $k = $parts[$i];

            if (preg_match('/^\[(\d+)]$/', $k, $matches)) {
                $k = (int) $matches[1];
            }

            /** @var array<string, mixed> $current */
            if (!array_key_exists($k, $current)) {
                return null;
            }

            $current = $current[$k];
            if (is_array($current)) {
                continue;
            }

            if ($i < count($parts) - 2) {
                return null;
            }
        }

        return $current;
    }
}
