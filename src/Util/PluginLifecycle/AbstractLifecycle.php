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

namespace Crehler\PayU\Util\PluginLifecycle;

use Crehler\PayU\Struct\DefaultSettings;
use Crehler\PayU\Util\PaymentMethodUtil;
use Crehler\PayU\Util\PayuMethodFinder;
use Crehler\PayU\Util\RuleUtil;
use Crehler\PayU\Util\TransactionFieldsUtil;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
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

    /** @var EntityRepositoryInterface */
    protected $systemConfigRepository;

    /** @var TransactionFieldsUtil */
    protected $transactionFieldsUtil;

    /**
     * AbstractLifecycle constructor.
     *
     * @param ContainerInterface $container
     * @param InstallContext     $lifecycleContext
     */
    public function __construct(ContainerInterface $container, InstallContext $lifecycleContext)
    {
        $this->lifecycleContext = $lifecycleContext;

        $this->systemConfigService = $container->get(SystemConfigService::class);

        $this->systemConfigRepository = $container->get('system_config.repository');

        /** @var EntityRepositoryInterface $paymentRepository */
        $paymentRepository = $container->get('payment_method.repository');

        /** @var EntityRepositoryInterface $ruleRepository */
        $ruleRepository = $container->get('rule.repository');

        /** @var EntityRepositoryInterface $currencyRepository */
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

        /** @var EntityRepositoryInterface $customFieldRepository */
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
