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

namespace Crehler\PayU\Service;

use Shopware\Core\Checkout\Order\Aggregate\OrderAddress\OrderAddressEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

/**
 * Interface PaymentDetailsReaderInterface
 */
interface PaymentDetailsReaderInterface
{
    public function getLanguageCode(SalesChannelContext $salesChannelContext): string;

    public function getOrderAddressEntity(string $orderAddressID): OrderAddressEntity;

    public function getCountryCode(string $countryID): string;

    public function generateShortDescription($orderNumber): string;

    public function generateLongDescription($orderNumber): string;
}
