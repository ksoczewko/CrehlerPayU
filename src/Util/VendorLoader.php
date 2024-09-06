<?php
/**
 * @copyright 2024 Crehler Sp. z o. o.
 *
 * https://crehler.com/
 * support@crehler.com
 *
 * This file is part of the PayU plugin for Shopware 6.
 * License CC BY-ND 4.0 (https://creativecommons.org/licenses/by-nd/4.0/legalcode.pl) see LICENSE file.
 *
 */

namespace Crehler\PayU\Util;

class VendorLoader
{
    final public const AUTOLOAD_PATCH = __DIR__ . '/../../vendor/autoload.php';

    public function loadOpenPayU()
    {
        if (!class_exists('\OpenPayU_Configuration') && is_file(self::AUTOLOAD_PATCH)) {
            require_once self::AUTOLOAD_PATCH;
        }
    }
}
