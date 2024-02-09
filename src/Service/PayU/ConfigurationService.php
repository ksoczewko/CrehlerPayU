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

namespace Crehler\PayU\Service\PayU;

use Crehler\PayU\Util\TestPaymentConfig;
use Crehler\PayU\Util\VendorLoader;
use OpenPayU_Configuration;
use OpenPayU_Order;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfigurationFactor
 */
class ConfigurationService
{
    public const CONFIG_PLUGIN_PREFIX = 'CrehlerPayU.config.';
    public const CONFIG_PAYMENT_METHOD_ID = 'paymentMethodId';
    public const CONFIG_ORDER_DESCRIPTION_SHORT = 'orderDescriptionShort';
    public const CONFIG_ORDER_DESCRIPTION_LONG = 'orderDescriptionLong';
    public const CONFIG_SECURE = 'secure';
    public const CONFIG_POS_ID = 'posId';
    public const CONFIG_MD5_KEY = 'md5Key';
    public const CONFIG_CLIENT_ID = 'clientId';
    public const CONFIG_CLIENT_SECRET = 'clientSecret';
    public const CONFIG_SANDBOX = 'sandbox';
    public const CONFIG_SANDBOX_POS_ID = 'sandboxPosId';
    public const CONFIG_SANDBOX__MD5_KEY = 'sandboxMd5Key';
    public const CONFIG_SANDBOX_CLIENT_ID = 'sandboxClientId';
    public const CONFIG_SANDBOX_CLIENT_SECRET = 'sandboxClientSecret';

    /** @var SystemConfigService */
    private $configurationService;

    /**
     * ConfigurationFactor constructor.
     *
     * @param SystemConfigService $configurationService
     * @param VendorLoader        $vendorLoader
     */
    public function __construct(SystemConfigService $configurationService, VendorLoader $vendorLoader)
    {
        $vendorLoader->loadOpenPayU();
        $this->configurationService = $configurationService;
    }

    /**
     * @throws \OpenPayU_Exception_Configuration
     */
    public function initialize(?bool $sandbox = null)
    {
        if ($sandbox === null) {
            $sandbox = (int) $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX);
        }
        if ($sandbox) {
            OpenPayU_Configuration::setEnvironment(self::CONFIG_SANDBOX);
            OpenPayU_Configuration::setMerchantPosId($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_POS_ID));
            OpenPayU_Configuration::setSignatureKey($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX__MD5_KEY));
            OpenPayU_Configuration::setOauthClientId($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_ID));
            OpenPayU_Configuration::setOauthClientSecret($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_SECRET));
        } else {
            OpenPayU_Configuration::setEnvironment(self::CONFIG_SECURE);
            OpenPayU_Configuration::setMerchantPosId($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_POS_ID));
            OpenPayU_Configuration::setSignatureKey($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_MD5_KEY));
            OpenPayU_Configuration::setOauthClientId($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_ID));
            OpenPayU_Configuration::setOauthClientSecret($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_SECRET));
        }
    }

    /**
     * @return bool
     */
    public function isCompleteConfiguration()
    {
        if ($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX)) {
            if (strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_POS_ID)) == 0 ||
                strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX__MD5_KEY)) == 0 ||
                strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_ID)) == 0 ||
                strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_SECRET)) == 0
            ) {
                return false;
            }
        } else {
            if (strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_POS_ID)) == 0 ||
                strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_MD5_KEY)) == 0 ||
                strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_ID)) == 0 ||
                strlen($this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_SECRET)) == 0
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isSadBox()
    {
        return (bool) $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX);
    }

    /**
     * Checks the credentials set in the plugin configuration.
     * Useful when verifying payments when adding to sales channel.
     *
     * @param Request|null $request
     *
     * @return bool
     */
    public function checkSavedCredentials(?Request $request = null)
    {
        $request = $request ?? Request::createFromGlobals();

        try {
            $this->initialize();
        } catch (\Exception $e) {
            return false;
        }

        return $this->checkCredentials($request);
    }

    /**
     * Checks the credentials provided in the request.
     * Used in the "Check Credentials" button in the plugin configuration.
     *
     * @param Request|null $request
     *
     * @return bool
     */
    public function checkRequestCredentials(?Request $request = null)
    {
        $request = $request ?? Request::createFromGlobals();

        try {
            if ($request->get('checkSandbox')) {
                OpenPayU_Configuration::setEnvironment(self::CONFIG_SANDBOX);
                OpenPayU_Configuration::setMerchantPosId($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_POS_ID));
                OpenPayU_Configuration::setSignatureKey($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX__MD5_KEY));
                OpenPayU_Configuration::setOauthClientId($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_ID));
                OpenPayU_Configuration::setOauthClientSecret($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_SECRET));
            } else {
                OpenPayU_Configuration::setEnvironment(self::CONFIG_SECURE);
                OpenPayU_Configuration::setMerchantPosId($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_POS_ID));
                OpenPayU_Configuration::setSignatureKey($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_MD5_KEY));
                OpenPayU_Configuration::setOauthClientId($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_ID));
                OpenPayU_Configuration::setOauthClientSecret($request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_SECRET));
            }
        } catch (\Exception $e) {
            return false;
        }

        return $this->checkCredentials($request);
    }

    /**
     * PayU does not provide a test method.
     * So we create and cancel the order to test the credentials.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function checkCredentials(Request $request)
    {
        $merchantId = intval(\OpenPayU_Configuration::getOauthClientId() ? \OpenPayU_Configuration::getOauthClientId() : \OpenPayU_Configuration::getMerchantPosId());
        try {
            $response = OpenPayU_Order::create(TestPaymentConfig::getConfiguration($merchantId, $request->getClientIp()));
        } catch (\OpenPayU_Exception $e) {
            return false;
        }

        if ($response->getStatus() !== 'SUCCESS') {
            return false;
        }

        try {
            $cancelResponse = OpenPayU_Order::cancel($response->getResponse()->orderId);
        } catch (\OpenPayU_Exception $e) {
            return false;
        }

        if ($cancelResponse->getStatus() !== 'SUCCESS') {
            return false;
        }

        return true;
    }
}
