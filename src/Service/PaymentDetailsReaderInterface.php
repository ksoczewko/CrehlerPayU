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

namespace Crehler\PayU\Service;

use Shopware\Core\Checkout\Order\Aggregate\OrderAddress\OrderAddressEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

/**
 * Interface PaymentDetailsReaderInterface
 */
interface PaymentDetailsReaderInterface
{
    /**
     * @param SalesChannelContext $salesChannelContext
     *
     * @return string
     */
    public function getLanguageCode(SalesChannelContext $salesChannelContext): string;

    /**
     * @param string $orderAddressID
     *
     * @return OrderAddressEntity
     */
    public function getOrderAddressEntity(string $orderAddressID): OrderAddressEntity;

    /**
     * @param string $countryID
     *
     * @return string
     */
    public function getCountryCode(string $countryID): string;

    /**
     * @param $orderNumber
     *
     * @return string
     */
    public function generateShortDescription($orderNumber): string;

    /**
     * @param $orderNumber
     *
     * @return string
     */
    public function generateLongDescription($orderNumber): string;
}
