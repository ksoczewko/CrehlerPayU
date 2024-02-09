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

namespace Crehler\PayU\Util;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Crehler\PayU\Core\Checkout\Payment\PayUPayment;
use Crehler\PayU\CrehlerPayU;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Plugin\Util\PluginIdProvider;
use Shopware\Core\Framework\Uuid\Uuid;

class PaymentMethodUtil
{
    /**
     * @var EntityRepository
     */
    private $paymentRepository;

    public function __construct(EntityRepository $paymentRepository, private readonly Context $context, private readonly RuleUtil $ruleUtil, private readonly PluginIdProvider $pluginIdProvider, private readonly PayuMethodFinder $methodFinder)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function createPaymentMethod(): ?string
    {
        try {
            $ruleId = $this->ruleUtil->getRuleId();
        } catch (\Exception) {
            $ruleId = null;
        }

        $payUPaymentId = $this->methodFinder->getPayUPaymentMethodId($this->context);

        if ($payUPaymentId) {
            return $payUPaymentId;
        }

        $payUPaymentId = Uuid::randomHex();

        $pluginId = $this->pluginIdProvider->getPluginIdByBaseClass(CrehlerPayU::class, $this->context);

        $payuData = [
            'handlerIdentifier' => PayUPayment::class,
            'name' => 'PayU',
            'position' => -100,
            'pluginId' => $pluginId,
            'translations' => [
                'de-DE' => [
                    'description' => 'Bezahlung per PayU - einfach, schnell und sicher.',
                ],
                'en-GB' => [
                    'description' => 'Payment via PayU - easy, fast and secure.',
                ],
            ],
        ];

        if (strlen($ruleId) > 0) {
            $payuData['availabilityRuleId'] = $ruleId;
        }

        $this->paymentRepository->create([$payuData], $this->context);

        return $payUPaymentId;
    }

    public function setPaymentMethodIsActive(bool $active): void
    {
        $paymentMethodId = $this->methodFinder->getPayUPaymentMethodId($this->context);
        if (!$paymentMethodId) {
            return;
        }
        $paymentMethod = [
            'id' => $paymentMethodId,
            'active' => $active,
        ];
        $this->paymentRepository->update([$paymentMethod], $this->context);
    }
}
