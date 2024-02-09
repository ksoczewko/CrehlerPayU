<?php
/**
 * @copyright 2019 Crehler Sp. z o. o.
 *
 * https://crehler.com/
 * support@crehler.com
 *
 * This file is part of the PayU plugin for Shopware 6.
 * All rights reserved.
 */

namespace Crehler\PayU\Util\PluginLifecycle;

final class Uninstall extends AbstractLifecycle
{
    public function uninstall()
    {
        $this->paymentMethodUtil->setPaymentMethodIsActive(false);
    }
}
