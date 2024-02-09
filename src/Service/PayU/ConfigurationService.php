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
use Monolog\Logger;
use OauthCacheFile;
use OpenPayU_Configuration;
use OpenPayU_Order;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfigurationFactor
 */
class ConfigurationService
{
    final public const CONFIG_PLUGIN_PREFIX = 'CrehlerPayU.config.';
    final public const CONFIG_PAYMENT_METHOD_ID = 'paymentMethodId';
    final public const CONFIG_ORDER_DESCRIPTION_SHORT = 'orderDescriptionShort';
    final public const CONFIG_ORDER_DESCRIPTION_LONG = 'orderDescriptionLong';
    final public const CONFIG_SECURE = 'secure';
    final public const CONFIG_POS_ID = 'posId';
    final public const CONFIG_MD5_KEY = 'md5Key';
    final public const CONFIG_CLIENT_ID = 'clientId';
    final public const CONFIG_CLIENT_SECRET = 'clientSecret';
    final public const CONFIG_SANDBOX = 'sandbox';
    final public const CONFIG_SANDBOX_POS_ID = 'sandboxPosId';
    final public const CONFIG_SANDBOX__MD5_KEY = 'sandboxMd5Key';
    final public const CONFIG_SANDBOX_CLIENT_ID = 'sandboxClientId';
    final public const CONFIG_SANDBOX_CLIENT_SECRET = 'sandboxClientSecret';

    /**
     * ConfigurationFactor constructor.
     */
    public function __construct(private readonly SystemConfigService $configurationService, VendorLoader $vendorLoader, private readonly ParameterBagInterface $parameterBag, private readonly Logger $logger)
    {
        $vendorLoader->loadOpenPayU();
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
            $this->initializePayUStaticConfiguration(
                true,
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_POS_ID),
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX__MD5_KEY),
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_ID),
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_SECRET)
            );
        } else {
            $this->initializePayUStaticConfiguration(
                false,
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_POS_ID),
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_MD5_KEY),
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_ID),
                $this->configurationService->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_SECRET)
            );
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
        $request ??= Request::createFromGlobals();

        try {
            $this->initialize();
        } catch (\Exception) {
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
        $request ??= Request::createFromGlobals();

        try {
            if ($request->get('checkSandbox')) {
                $this->initializePayUStaticConfiguration(
                    true,
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_POS_ID),
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX__MD5_KEY),
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_ID),
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_SANDBOX_CLIENT_SECRET)
                );
            } else {
                $this->initializePayUStaticConfiguration(
                    false,
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_POS_ID),
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_MD5_KEY),
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_ID),
                    $request->get(self::CONFIG_PLUGIN_PREFIX . self::CONFIG_CLIENT_SECRET)
                );
            }
        } catch (\Exception) {
            return false;
        }

        return $this->checkCredentials($request);
    }

    /**
     * PayU does not provide a test method.
     * So we create and cancel the order to test the credentials.
     *
     *
     * @return bool
     */
    protected function checkCredentials(Request $request)
    {
        $merchantId = intval(\OpenPayU_Configuration::getOauthClientId() ? \OpenPayU_Configuration::getOauthClientId() : \OpenPayU_Configuration::getMerchantPosId());
        try {
            $response = OpenPayU_Order::create(TestPaymentConfig::getConfiguration($merchantId, $request->getClientIp()));
        } catch (\OpenPayU_Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        if ($response->getStatus() !== 'SUCCESS') {
            $this->logger->error("Error while checking the status of the test transaction, returned status: " . $response->getStatus());
            return false;
        }

        try {
            $cancelResponse = OpenPayU_Order::cancel($response->getResponse()->orderId);
        } catch (\OpenPayU_Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        if ($cancelResponse->getStatus() !== 'SUCCESS') {
            $this->logger->error("Error while canceling the test transaction, returned status: " . $cancelResponse->getStatus());
            return false;
        }

        return true;
    }

    private function initializePayUStaticConfiguration(bool $sandbox, ?string $merchantPosId, ?string $signatureKey, ?string $oauthClientId, ?string $oauthClientSecret)
    {
        try {
            OpenPayU_Configuration::setOauthTokenCache(new OauthCacheFile($this->getCacheDir()));
            if ($sandbox) {
                OpenPayU_Configuration::setEnvironment(self::CONFIG_SANDBOX);
            } else {
                OpenPayU_Configuration::setEnvironment(self::CONFIG_SECURE);
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }
        if ($merchantPosId !== null) OpenPayU_Configuration::setMerchantPosId($merchantPosId);
        if ($signatureKey !== null)OpenPayU_Configuration::setSignatureKey($signatureKey);
        if ($oauthClientId !== null)OpenPayU_Configuration::setOauthClientId($oauthClientId);
        if ($oauthClientSecret !== null)OpenPayU_Configuration::setOauthClientSecret($oauthClientSecret);
    }

    private function getCacheDir()
    {
        $dir = $this->parameterBag->get('kernel.cache_dir') . DIRECTORY_SEPARATOR . 'PayU';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }
}
