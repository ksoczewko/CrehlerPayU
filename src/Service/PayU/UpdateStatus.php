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

namespace Crehler\PayU\Service\PayU;

use OpenPayU_Order;
use OpenPayuOrderStatus;

/**
 * Class UpdateStatus
 */
class UpdateStatus
{
    /**
     * UpdateStatus constructor.
     *
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
        OpenPayU_Order::statusUpdate([
            'orderId' => $orderID,
            'orderStatus' => OpenPayuOrderStatus::STATUS_COMPLETED,
        ]);
    }
}
