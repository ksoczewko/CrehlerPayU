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

    /**
     * @return string
     */
    public function getOrderDescriptionShort(): string
    {
        return $this->orderDescriptionShort;
    }

    /**
     * @param string $orderDescriptionShort
     *
     * @return DefaultSettings
     */
    public function setOrderDescriptionShort(string $orderDescriptionShort): DefaultSettings
    {
        $this->orderDescriptionShort = $orderDescriptionShort;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderDescriptionLong(): string
    {
        return $this->orderDescriptionLong;
    }

    /**
     * @param string $orderDescriptionLong
     *
     * @return DefaultSettings
     */
    public function setOrderDescriptionLong(string $orderDescriptionLong): DefaultSettings
    {
        $this->orderDescriptionLong = $orderDescriptionLong;

        return $this;
    }
}
