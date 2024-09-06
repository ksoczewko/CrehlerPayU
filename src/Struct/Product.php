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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $unitPrice): Product
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getVirtual(): bool
    {
        return $this->virtual;
    }

    public function setVirtual(bool $virtual): Product
    {
        $this->virtual = $virtual;

        return $this;
    }

    public function getListingDate(): string
    {
        return $this->listingDate->format(\DateTimeInterface::RFC3339);
    }

    public function setListingDate(\DateTimeInterface $listingDate): Product
    {
        $this->listingDate = $listingDate;

        return $this;
    }
}
