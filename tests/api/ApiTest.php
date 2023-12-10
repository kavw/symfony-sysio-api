<?php

declare(strict_types=1);

namespace App\Tests\Api;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group("api")]
class ApiTest extends AbstractWebTestCase
{
    /**
     * @return array<int, array<string, mixed>|mixed>
     */
    public static function endpointsDataProvider(): array
    {
        return [
            [
                'POST', '/calculate-price', [
                    'product' => 1001,
                    'taxNumber' => 'DE123456789'
                ],
                200, [
                    'request.product.price' => 10000,
                    'productAmount' => 10000,
                    'request.tax.value' => 19,
                    'taxes.DE123456789' => 1900,
                    'amount' => 11900,
                ]
            ],
            [
                'POST', '/calculate-price', [
                    'product' => 1001,
                    'taxNumber' => 'GR123456789',
                    'couponCode' => 'DP6',
                ],
                200, [
                    'request.product.price' => 10000,
                    'request.coupon.value' => 6,
                    'discounts.DP6' => 600,
                    'productAmount' => 9400,
                    'request.tax.value' => 24,
                    'taxes.GR123456789' => 2256,
                    'amount' => 11656,
                ],
            ],
            [

                'POST', '/calculate-price', [
                    'product_' => 1001,
                    'taxNumber_' => 'DE123456789'
                ],
                400, [
                    'error' => 'Validation failed.',
                    'details.product.[0]' => 'This value should not be null.',
                    'details.taxNumber.[0]' => 'This value should not be null.',
                ],
            ],
            [

                'POST', '/calculate-price', [
                    'product' => 1,
                    'taxNumber' => 'US123456789'
                ],
                400, [
                    'error' => 'Validation failed.',
                    'details.product.[0]' => 'The product "1" not found.',
                    'details.taxNumber.[0]' => "Can't find entity for the given tax number 'US123456789'",
                ],
            ],
            [
                'POST', '/calculate-price', [
                    'product' => 1001,
                    'taxNumber' => 'DE123456789_'
                ],
                400, [
                    'error' => 'Validation failed.',
                    'details.taxNumber.[0]' => "The given tax number 'DE123456789_' has invalid format",
                ]
            ],

            [
                'POST', '/purchase', [
                    'product' => 1001,
                    'taxNumber' => 'GR123456789',
                    'couponCode' => 'DP6',
                    'paymentProcessor' => 'paypal'
                ],
                200, [],
            ],
            [
                'POST', '/purchase', [
                    'product' => 1001,
                    'taxNumber' => 'GR123456789',
                    'couponCode' => 'DP6',
                    'paymentProcessor' => 'stripe'
                ],
                200, [],
            ],
            [
                'POST', '/purchase', [
                    'product' => 1001,
                    'taxNumber' => 'GR123456789',
                    'couponCode' => 'DP6',
                    'paymentProcessor_' => 'stripe'
                ],
                400, [
                    'error' => 'Validation failed.',
                    'details.paymentProcessor.[0]' => "This value should not be null.",
                ]
            ],
            [
                'POST', '/purchase', [
                    'product' => 1001,
                    'taxNumber' => 'GR123456789',
                    'couponCode' => 'DP6',
                    'paymentProcessor' => 'stripe_'
                ],
                400, [
                    'error' => 'Invalid format: ' .
                        'The data must belong to a backed enumeration of type App\\Entity\\PaymentGatewayType',
                ],
            ],
            [
                'POST', '/purchase', [
                    'product' => 1004,
                    'taxNumber' => 'GR123456789',
                    'paymentProcessor' => 'paypal'
                ],
                400, [
                    'error' => 'Payment has been failed'
                ],
            ],             [
            'POST', '/purchase', [
                    'product' => 1003,
                    'taxNumber' => 'DE123456789',
                    'couponCode' => 'DF8',
                    'paymentProcessor' => 'stripe'
                ],
                400, [
                    'error' => 'Payment has been failed'
                ],
            ],
        ];
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array<string, mixed> $data
     * @param int $statusCode
     * @param array<string, mixed> $assertions
     * @return void
     */
    #[DataProvider('endpointsDataProvider')]
    public function testEndpoints(
        string $method,
        string $uri,
        array $data,
        int $statusCode,
        array $assertions
    ): void {
        $client = static::createClient();
        $client->jsonRequest($method, $uri, $data);
        $response = $client->getResponse();
        $this->assertEquals($statusCode, $response->getStatusCode(), "Checking status code: {$method} {$uri}");
        $content = $response->getContent();
        if (!$content) {
            return;
        }

        /** @var array<string|mixed> $responseData */
        $responseData = json_decode($content, associative: true);
        foreach ($assertions as $k => $v) {
            $this->assertEquals($v, $this->getArrayValue($k, $responseData), "Checking path: {$k}");
        }
    }
}
