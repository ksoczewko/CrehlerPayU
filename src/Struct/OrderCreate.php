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
 * Class OrderCreate
 */
class OrderCreate extends PayUStruct
{
    /**
     * ID of an order used in merchant system
     *
     * @var string
     */
    protected $extOrderId;

    /**
     * 	The address for sending notifications
     *
     * @var string
     */
    protected $notifyUrl;

    /**
     * 	Payer’s IP address, e.g. 123.123.123.123. Note: 0.0.0.0 is not accepted.
     *
     * @var string
     */
    protected $customerIp;

    /**
     * Point of sale ID
     *
     * @var int
     */
    protected $merchantPosId;

    /**
     * Duration for the validity of an order (in seconds), during which time payment must be made
     *
     * @var int
     */
    protected $validityTime;

    /**
     * Description of the an order
     *
     * @var string
     */
    protected $description;

    /**
     * Additional description of the order
     *
     * @var string
     */
    protected $additionalDescription;

    /**
     * Currency code compliant with ISO 4217 (e.g EUR).
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * 	Total price of the order in pennies (e.g. 1000 is 10.00 EUR). Applies also to currencies without subunits (e.g. 1000 is 10 HUF).
     *
     * @var int
     */
    protected $totalAmount;

    /**
     * Information about party initializing order:
     * STANDARD_CARDHOLDER - payment is initialized by the card owner;
     * STANDARD_MERCHANT - payment is initialized by the shop, without card owner participation.
     *
     * @var string
     */
    protected $cardOnFile;

    /**
     * Address for redirecting the customer after payment is commenced.
     * If the payment has not been authorized, error=501 parameter will be added.
     * Please note that no decision regarding payment status should be made depending
     * on the presence or lack of this parameter
     * (to get payment status, wait for notification or retrieve order details).
     *
     * @var string
     */
    protected $continueUrl;

    /**
     * Section containing buyer data. This information is not required, but it is strongly recommended to include it.
     * Otherwise the buyer will be prompted to provide missing data on PayU page and payment
     * via Installments or Pay later will not be possible.
     *
     * @var Buyer
     */
    protected $buyer;

    /**
     * Section containing data of the ordered products. Section products is an array of objects of type Product
     *
     * @var array|Product[]
     */
    protected $products;

    /**
     * Section allows to directly invoke payment method.
     *
     * @var string
     */
    protected $payMethods;

    /**
     * 	Section allows to pass currency conversion details.
     */
    protected $mcpData;

    /**
     * @return string
     */
    public function getExtOrderId(): string
    {
        return $this->extOrderId;
    }

    /**
     * @param string $extOrderId
     *
     * @return OrderCreate
     */
    public function setExtOrderId(string $extOrderId): OrderCreate
    {
        $this->extOrderId = $extOrderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotifyUrl(): string
    {
        return $this->notifyUrl;
    }

    /**
     * @param string $notifyUrl
     *
     * @return OrderCreate
     */
    public function setNotifyUrl(string $notifyUrl): OrderCreate
    {
        $this->notifyUrl = $notifyUrl;

        return $this;
    }

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
     * @return OrderCreate
     */
    public function setCustomerIp(string $customerIp): OrderCreate
    {
        $this->customerIp = $customerIp;

        return $this;
    }

    /**
     * @return int
     */
    public function getMerchantPosId(): int
    {
        return $this->merchantPosId;
    }

    /**
     * @param int $merchantPosId
     *
     * @return OrderCreate
     */
    public function setMerchantPosId(int $merchantPosId): OrderCreate
    {
        $this->merchantPosId = $merchantPosId;

        return $this;
    }

    /**
     * @return int
     */
    public function getValidityTime(): int
    {
        return $this->validityTime;
    }

    /**
     * @param int $validityTime
     *
     * @return OrderCreate
     */
    public function setValidityTime(int $validityTime): OrderCreate
    {
        $this->validityTime = $validityTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return OrderCreate
     */
    public function setDescription(string $description): OrderCreate
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalDescription(): string
    {
        return $this->additionalDescription;
    }

    /**
     * @param string $additionalDescription
     *
     * @return OrderCreate
     */
    public function setAdditionalDescription(string $additionalDescription): OrderCreate
    {
        $this->additionalDescription = $additionalDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     *
     * @return OrderCreate
     */
    public function setCurrencyCode(string $currencyCode): OrderCreate
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    /**
     * @param int $totalAmount
     *
     * @return OrderCreate
     */
    public function setTotalAmount(int $totalAmount): OrderCreate
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardOnFile(): string
    {
        return $this->cardOnFile;
    }

    /**
     * @param string $cardOnFile
     *
     * @return OrderCreate
     */
    public function setCardOnFile(string $cardOnFile): OrderCreate
    {
        $this->cardOnFile = $cardOnFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getContinueUrl(): string
    {
        return $this->continueUrl;
    }

    /**
     * @param string $continueUrl
     *
     * @return OrderCreate
     */
    public function setContinueUrl(string $continueUrl): OrderCreate
    {
        $this->continueUrl = $continueUrl;

        return $this;
    }

    /**
     * @return Buyer
     */
    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    /**
     * @param Buyer $buyer
     *
     * @return OrderCreate
     */
    public function setBuyer(Buyer $buyer): OrderCreate
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return OrderCreate
     */
    public function addProduct(Product $product): OrderCreate
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @return array|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param array|Product[] $products
     *
     * @return OrderCreate
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @return string
     */
    public function getPayMethods(): string
    {
        return $this->payMethods;
    }

    /**
     * @param string $payMethods
     *
     * @return OrderCreate
     */
    public function setPayMethods(string $payMethods): OrderCreate
    {
        $this->payMethods = $payMethods;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMcpData()
    {
        return $this->mcpData;
    }

    /**
     * @param mixed $mcpData
     *
     * @return OrderCreate
     */
    public function setMcpData($mcpData)
    {
        $this->mcpData = $mcpData;

        return $this;
    }
}
