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

namespace Crehler\PayU\Struct;

use Shopware\Core\Framework\Struct\Struct;

/**
 * Class DefaultSettings
 */
class DefaultSettings extends Struct
{
    /**
     * @var string
     */
    protected $orderDescriptionShort;

    /**
     * @var string
     */
    protected $orderDescriptionLong;

    /**
     * DefaultSettings constructor.
     */
    public function __construct()
    {
        $this->orderDescriptionShort = 'Order fee in the online store: {number}';
        $this->orderDescriptionLong = 'Order fee in the best online store.';
    }

    public function getOrderDescriptionShort(): string
    {
        return $this->orderDescriptionShort;
    }

    public function setOrderDescriptionShort(string $orderDescriptionShort): DefaultSettings
    {
        $this->orderDescriptionShort = $orderDescriptionShort;

        return $this;
    }

    public function getOrderDescriptionLong(): string
    {
        return $this->orderDescriptionLong;
    }

    public function setOrderDescriptionLong(string $orderDescriptionLong): DefaultSettings
    {
        $this->orderDescriptionLong = $orderDescriptionLong;

        return $this;
    }
}
