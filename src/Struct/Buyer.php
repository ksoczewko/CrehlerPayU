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

/**
 * Class Buyer
 */
class Buyer extends PayUStruct
{
    /**
     * Payer’s IP address, e.g. 123.123.123.123. Note: 0.0.0.0 is not accepted
     *
     * @var string
     */
    protected $customerIp;

    /**
     * 	ID of the customer used in merchant system
     *
     * @var string
     */
    protected $extCustomerId;

    /**
     * Buyer's email address
     *
     * @var string
     */
    protected $email;

    /**
     * Buyer's telephone number
     *
     * @var string
     */
    protected $phone;

    /**
     * Buyer's first name
     *
     * @var string
     */
    protected $firstName;

    /**
     * Buyer's last name
     *
     * @var string
     */
    protected $lastName;

    /**
     * National Identification Number
     *
     * @var string
     */
    protected $nin;

    /**
     * Denotes the language version of PayU hosted payment page and of e-mail messages sent from PayU to the payer
     *
     * @var string
     */
    protected $language;

    /**
     * @var BuyerDelivery
     */
    protected $delivery;

    public function getCustomerIp(): string
    {
        return $this->customerIp;
    }

    public function setCustomerIp(string $customerIp): Buyer
    {
        $this->customerIp = $customerIp;

        return $this;
    }

    public function getExtCustomerId(): string
    {
        return $this->extCustomerId;
    }

    public function setExtCustomerId(string $extCustomerId): Buyer
    {
        $this->extCustomerId = $extCustomerId;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Buyer
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): Buyer
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Buyer
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Buyer
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNin(): string
    {
        return $this->nin;
    }

    public function setNin(string $nin): Buyer
    {
        $this->nin = $nin;

        return $this;
    }

    public function getLanguage(): string
    {
        if (!in_array($this->language, ['en', 'de', 'pl'])) {
            return 'en';
        }

        return $this->language;
    }

    public function setLanguage(string $language): Buyer
    {
        $this->language = $language;

        return $this;
    }

    public function getDelivery(): BuyerDelivery
    {
        return $this->delivery;
    }

    public function setDelivery(BuyerDelivery $delivery): Buyer
    {
        $this->delivery = $delivery;

        return $this;
    }
}
