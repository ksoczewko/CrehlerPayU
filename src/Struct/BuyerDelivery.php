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

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return BuyerDelivery
     */
    public function setStreet(string $street): BuyerDelivery
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostalBox(): string
    {
        return $this->postalBox;
    }

    /**
     * @param string $postalBox
     *
     * @return BuyerDelivery
     */
    public function setPostalBox(string $postalBox): BuyerDelivery
    {
        $this->postalBox = $postalBox;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     *
     * @return BuyerDelivery
     */
    public function setPostalCode(string $postalCode): BuyerDelivery
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return BuyerDelivery
     */
    public function setCity(string $city): BuyerDelivery
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return BuyerDelivery
     */
    public function setState(string $state): BuyerDelivery
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     *
     * @return BuyerDelivery
     */
    public function setCountryCode(string $countryCode): BuyerDelivery
    {
        $this->countryCode = $countryCode;

        return $this;
    }

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
     * @return BuyerDelivery
     */
    public function setName(string $name): BuyerDelivery
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    /**
     * @param string $recipientName
     *
     * @return BuyerDelivery
     */
    public function setRecipientName(string $recipientName): BuyerDelivery
    {
        $this->recipientName = $recipientName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    /**
     * @param string $recipientEmail
     *
     * @return BuyerDelivery
     */
    public function setRecipientEmail(string $recipientEmail): BuyerDelivery
    {
        $this->recipientEmail = $recipientEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientPhone(): string
    {
        return $this->recipientPhone;
    }

    /**
     * @param string $recipientPhone
     *
     * @return BuyerDelivery
     */
    public function setRecipientPhone(string $recipientPhone): BuyerDelivery
    {
        $this->recipientPhone = $recipientPhone;

        return $this;
    }
}
