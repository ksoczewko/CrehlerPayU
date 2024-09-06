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

namespace Crehler\PayU\Util;

use Crehler\PayU\Entity\OrderTransactionRepository;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class TransactionFieldsUtil
{
    /** @var EntityRepository */
    private $customFieldRepository;

    public function __construct(EntityRepository $customFieldRepository, private readonly Context $context)
    {
        $this->customFieldRepository = $customFieldRepository;
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function createTransactionFields(): void
    {
        $customFieldIds = $this->getCustomFieldIds();

        if ($customFieldIds->getTotal() !== 0) {
            return;
        }

        $this->customFieldRepository->upsert(
            [
                [
                    'name' => OrderTransactionRepository::PAYU_PAY_URL,
                    'type' => CustomFieldTypes::TEXT,
                ], [
                'name' => OrderTransactionRepository::PAYU_EXTERNAL_ID,
                'type' => CustomFieldTypes::TEXT,
            ],
            ],
            $this->context
        );
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function removeTransactionFields(): void
    {
        $customFieldIds = $this->getCustomFieldIds();

        if ($customFieldIds->getTotal() !== 0) {
            return;
        }

        $ids = array_map(static fn ($id) => ['id' => $id], $customFieldIds->getIds());

        $this->customFieldRepository->delete($ids, $this->context);
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    private function getCustomFieldIds(): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new MultiFilter('OR', [
                new EqualsFilter('name', OrderTransactionRepository::PAYU_PAY_URL),
                new EqualsFilter('name', OrderTransactionRepository::PAYU_EXTERNAL_ID),
            ])
        );

        return $this->customFieldRepository->searchIds($criteria, $this->context);
    }
}
