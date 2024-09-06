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
 * Class BuyerDelivery
 */
class BuyerDelivery extends PayUStruct
{
    /**
     * Street
     *
     * @var string
     */
    protected $street;

    /**
     * Postal box
     *
     * @var string
     */
    protected $postalBox;

    /**
     * Postal code
     *
     * @var string
     */
    protected $postalCode;

    /**
     * City
     *
     * @var string
     */
    protected $city;

    /**
     * State
     *
     * @var string
     */
    protected $state;

    /**
     * Two-letter country code compliant with ISO-3166.
     *
     * @var string
     */
    protected $countryCode;

    /**
     * Address description
     *
     * @var string
     */
    protected $name;

    /**
     * Recipient name
     *
     * @var string
     */
    protected $recipientName;

    /**
     * Recipient email
     *
     * @var string
     */
    protected $recipientEmail;

    /**
     * Recipient phone number
     *
     * @var string
     */
    protected $recipientPhone;

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): BuyerDelivery
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalBox(): string
    {
        return $this->postalBox;
    }

    public function setPostalBox(string $postalBox): BuyerDelivery
    {
        $this->postalBox = $postalBox;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): BuyerDelivery
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): BuyerDelivery
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): BuyerDelivery
    {
        $this->state = $state;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): BuyerDelivery
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): BuyerDelivery
    {
        $this->name = $name;

        return $this;
    }

    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    public function setRecipientName(string $recipientName): BuyerDelivery
    {
        $this->recipientName = $recipientName;

        return $this;
    }

    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    public function setRecipientEmail(string $recipientEmail): BuyerDelivery
    {
        $this->recipientEmail = $recipientEmail;

        return $this;
    }

    public function getRecipientPhone(): string
    {
        return $this->recipientPhone;
    }

    public function setRecipientPhone(string $recipientPhone): BuyerDelivery
    {
        $this->recipientPhone = $recipientPhone;

        return $this;
    }
}
