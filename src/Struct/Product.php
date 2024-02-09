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

/**
 * Class Product
 */
class Product extends PayUStruct
{
    /**
     * Name of the product
     *
     * @var string
     */
    protected $name;

    /**
     * Unit price
     *
     * @var int
     */
    protected $unitPrice;

    /**
     * Quantity
     *
     * @var int
     */
    protected $quantity;

    /**
     * 	Product type, which can be virtual or material.
     *
     * @var bool
     */
    protected $virtual;

    /**
     * Marketplace date from which the product (or offer) is available, for example: "2016-01-26T17:35:37+01:00"
     *
     * @var \DateTime
     */
    protected $listingDate;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    /**
     * @param int $unitPrice
     *
     * @return Product
     */
    public function setUnitPrice(int $unitPrice): Product
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Product
     */
    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return bool
     */
    public function getVirtual(): bool
    {
        return $this->virtual;
    }

    /**
     * @param bool $virtual
     *
     * @return Product
     */
    public function setVirtual(bool $virtual): Product
    {
        $this->virtual = $virtual;

        return $this;
    }

    /**
     * @return string
     */
    public function getListingDate(): string
    {
        return $this->listingDate->format(\DateTimeInterface::RFC3339);
    }

    /**
     * @param \DateTimeInterface $listingDate
     *
     * @return Product
     */
    public function setListingDate(\DateTimeInterface $listingDate): Product
    {
        $this->listingDate = $listingDate;

        return $this;
    }
}
