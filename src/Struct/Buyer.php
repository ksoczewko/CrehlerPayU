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

    /**
     * @return string
     */
    public function getCustomerIp(): string
    {
        return $this->customerIp;
    }

    /**
     * @param string $customerIp
     *
     * @return Buyer
     */
    public function setCustomerIp(string $customerIp): Buyer
    {
        $this->customerIp = $customerIp;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtCustomerId(): string
    {
        return $this->extCustomerId;
    }

    /**
     * @param string $extCustomerId
     *
     * @return Buyer
     */
    public function setExtCustomerId(string $extCustomerId): Buyer
    {
        $this->extCustomerId = $extCustomerId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Buyer
     */
    public function setEmail(string $email): Buyer
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Buyer
     */
    public function setPhone(string $phone): Buyer
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return Buyer
     */
    public function setFirstName(string $firstName): Buyer
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Buyer
     */
    public function setLastName(string $lastName): Buyer
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getNin(): string
    {
        return $this->nin;
    }

    /**
     * @param string $nin
     *
     * @return Buyer
     */
    public function setNin(string $nin): Buyer
    {
        $this->nin = $nin;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        if (!in_array($this->language, ['en', 'de', 'pl'])) {
            return 'en';
        }

        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return Buyer
     */
    public function setLanguage(string $language): Buyer
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return BuyerDelivery
     */
    public function getDelivery(): BuyerDelivery
    {
        return $this->delivery;
    }

    /**
     * @param BuyerDelivery $delivery
     *
     * @return Buyer
     */
    public function setDelivery(BuyerDelivery $delivery): Buyer
    {
        $this->delivery = $delivery;

        return $this;
    }
}
