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

namespace Crehler\PayU\Util;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Rule\Container\AndRule;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Currency\Rule\CurrencyRule;

class RuleUtil
{
    final public const RULE_NAME = 'PayU only PLN';

    /** @var EntityRepository */
    private $ruleRepository;

    /** @var EntityRepository */
    private $currencyRepository;

    public function __construct(EntityRepository $ruleRepository,
        EntityRepository $currencyRepository,
        private readonly Context $context
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @throws \Exception
     */
    public function getRuleId(): ?string
    {
        $ruleID = $this->checkRuleExist();
        if ($ruleID !== null) {
            return $ruleID;
        }

        return $this->createRule();
    }

    /**
     * @throws \Exception
     */
    private function checkRuleExist(): ?string
    {
        $ruleCriteria = (new Criteria())
            ->addFilter(new EqualsFilter('name', self::RULE_NAME));
        $ruleIds = $this->ruleRepository->searchIds($ruleCriteria, $this->context);
        if ($ruleIds->getTotal() === 0) {
            return null;
        }

        return $ruleIds->firstId();
    }

    /**
     * @throws \Exception
     */
    private function createRule(): ?string
    {
        $ruleId = Uuid::randomHex();
        $currencyId = $this->getCurrencyID();
        $data = [
            'id' => $ruleId,
            'name' => self::RULE_NAME,
            'priority' => 1,
            'description' => 'The currency required is PLN',
            'conditions' => [
                [
                    'type' => (new AndRule())->getName(),
                    'children' => [
                        [
                            'type' => (new CurrencyRule())->getName(),
                            'value' => [
                                'currencyIds' => [$currencyId],
                                'operator' => CurrencyRule::OPERATOR_EQ,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $this->ruleRepository->create([$data], $this->context);

        return $ruleId;
    }

    /**
     * @throws \Exception
     */
    private function getCurrencyID(): string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('currency.isoCode', 'PLN'));

        $currency = $this->currencyRepository->search($criteria, $this->context);

        if ($currency->count() < 1) {
            throw new \Exception('You must have the currency PLN in the store before installing Polish payments.');
        }

        return $currency->first()->getId();
    }
}
