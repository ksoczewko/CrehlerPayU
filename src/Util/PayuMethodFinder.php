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
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class PayuMethodFinder
{
    /**
     * @var EntityRepository
     */
    private $paymentRepository;

    public function __construct(EntityRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getPayUPaymentMethodId(?Context $context = null): ?string
    {
        if(empty($context)) $context = Context::createDefaultContext();
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('handlerIdentifier', PayUPayment::class));

        return $this->paymentRepository->searchIds($criteria, $context)->firstId();
    }
}
