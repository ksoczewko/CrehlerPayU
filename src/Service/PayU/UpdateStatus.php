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

namespace Crehler\PayU\Service\PayU;

/**
 * Class UpdateStatus
 */
class UpdateStatus
{
    /**
     * UpdateStatus constructor.
     *
     * @throws \OpenPayU_Exception_Configuration
     */
    public function __construct(ConfigurationService $configurationFactor)
    {
        $configurationFactor->initialize();
    }

    /**
     * @throws \OpenPayU_Exception
     */
    public function complete(string $orderID)
    {
        \OpenPayU_Order::statusUpdate([
            'orderId' => $orderID,
            'orderStatus' => \OpenPayuOrderStatus::STATUS_COMPLETED,
        ]);
    }
}
