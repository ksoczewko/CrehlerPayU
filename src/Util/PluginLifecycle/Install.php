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

namespace Crehler\PayU\Util\PluginLifecycle;

use Crehler\PayU\Service\PayU\ConfigurationService;

final class Install extends AbstractLifecycle
{
    public function install()
    {
        $paymentMethodId = $this->paymentMethodUtil->createPaymentMethod();
        $this->savePaymentMethodId($paymentMethodId);
        $this->addDefaultConfiguration();
    }

    private function savePaymentMethodId(string $paymentMethodId): void
    {
        $this->systemConfigService->set(
            ConfigurationService::CONFIG_PLUGIN_PREFIX . ConfigurationService::CONFIG_PAYMENT_METHOD_ID,
            $paymentMethodId
        );
    }
}
