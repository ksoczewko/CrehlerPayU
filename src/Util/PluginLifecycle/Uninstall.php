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

namespace Crehler\PayU\Util\PluginLifecycle;

final class Uninstall extends AbstractLifecycle
{
    public function uninstall()
    {
        $this->paymentMethodUtil->setPaymentMethodIsActive(false);
    }
}
