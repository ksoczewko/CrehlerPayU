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

namespace Crehler\PayU\Util\PluginLifecycle;

use Crehler\PayU\Struct\DefaultSettings;
use Crehler\PayU\Util\PaymentMethodUtil;
use Crehler\PayU\Util\PayuMethodFinder;
use Crehler\PayU\Util\RuleUtil;
use Crehler\PayU\Util\TransactionFieldsUtil;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Util\PluginIdProvider;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractLifecycle
 */
abstract class AbstractLifecycle
{
    /**
     * @var PaymentMethodUtil
     */
    protected $paymentMethodUtil;

    /**
     * @var InstallContext
     */
    protected $lifecycleContext;

    /**
     * @var SystemConfigService
     */
    protected $systemConfigService;

    /** @var EntityRepository */
    protected $systemConfigRepository;

    /** @var TransactionFieldsUtil */
    protected $transactionFieldsUtil;

    /**
     * AbstractLifecycle constructor.
     */
    public function __construct(ContainerInterface $container, InstallContext $lifecycleContext)
    {
        $this->lifecycleContext = $lifecycleContext;

        $this->systemConfigService = $container->get(SystemConfigService::class);

        $this->systemConfigRepository = $container->get('system_config.repository');

        /** @var EntityRepository $paymentRepository */
        $paymentRepository = $container->get('payment_method.repository');

        /** @var EntityRepository $ruleRepository */
        $ruleRepository = $container->get('rule.repository');

        /** @var EntityRepository $currencyRepository */
        $currencyRepository = $container->get('currency.repository');

        $ruleUtil = new RuleUtil(
            $ruleRepository,
            $currencyRepository,
            $lifecycleContext->getContext()
        );

        /** @var PluginIdProvider $pluginIdProvider */
        $pluginIdProvider = $container->get(PluginIdProvider::class);

        $methodFinder = new PayuMethodFinder($paymentRepository);

        $this->paymentMethodUtil = new PaymentMethodUtil(
            $paymentRepository,
            $lifecycleContext->getContext(),
            $ruleUtil,
            $pluginIdProvider,
            $methodFinder
        );

        /** @var EntityRepository $customFieldRepository */
        $customFieldRepository = $container->get('custom_field.repository');

        $this->transactionFieldsUtil = new TransactionFieldsUtil(
            $customFieldRepository,
            $lifecycleContext->getContext()
        );
    }

    protected function addDefaultConfiguration(): void
    {
        $data = [];
        foreach ((new DefaultSettings())->jsonSerialize() as $key => $value) {
            if ($value === null || $value === []) {
                continue;
            }

            $key = 'CrehlerPayU.config.' . $key;
            $data[] = [
                'id' => Uuid::randomHex(),
                'configurationKey' => $key,
                'configurationValue' => $value,
            ];
        }
        $this->systemConfigRepository->upsert($data, Context::createDefaultContext());
    }
}
