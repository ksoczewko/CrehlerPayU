<?php
/**
 * @copyright 2024 Crehler Sp. z o. o.
 *
 * https://crehler.com/
 * support@crehler.com
 *
 * This file is part of the PayU plugin for Shopware 6.
 * License CC BY-NC-ND 4.0 (https://creativecommons.org/licenses/by-nc-nd/4.0/deed.pl) see LICENSE file.
 *
 */

namespace Crehler\PayU\Util;

class TestPaymentConfig
{
    public static function getConfiguration(int $merchantPosId, string $clientIp)
    {
        return [
            'customerIp' => $clientIp,
            'merchantPosId' => $merchantPosId,
            'description' => 'Check credentials from Shopware',
            'currencyCode' => 'PLN',
            'totalAmount' => '100',
            'products' => [
                [
                    'name' => 'PayU integration for Shopware 6',
                    'unitPrice' => '100',
                    'quantity' => '1',
                ],
            ],
        ];
    }
}
