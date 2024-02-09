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

use Crehler\PayU\Entity\OrderTransactionRepository;
use OpenPayU_Exception;
use OpenPayU_Retrieve;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

/**
 * Class TransactionDetails
 */
class TransactionDetails
{
    /** @var EntityRepositoryInterface */
    private $transactionEntity;

    /**
     * TransactionDetails constructor.
     *
     * @param ConfigurationService      $configurationFactor
     * @param EntityRepositoryInterface $transactionEntity
     *
     * @throws \OpenPayU_Exception_Configuration
     */
    public function __construct(ConfigurationService $configurationFactor, EntityRepositoryInterface $transactionEntity)
    {
        $configurationFactor->initialize();
        $this->transactionEntity = $transactionEntity;
    }

    /**
     * @param string  $orderID
     * @param Context $context
     *
     * @throws InconsistentCriteriaIdsException
     *
     * @return array
     */
    public function getData(string $orderID, Context $context): array
    {
        /** @var EntityCollection $entities */
        $entities = $this->transactionEntity->search(
            (new Criteria())->addFilter(new EqualsFilter('orderId', $orderID)),
            $context
        );
        if ($entities->count() <= 0) {
            return [];
        }

        /** @var OrderTransactionEntity $transaction */
        $transaction = $entities->first();

        if (!is_array($transaction->getCustomFields()) || !array_key_exists(OrderTransactionRepository::PAYU_EXTERNAL_ID, $transaction->getCustomFields())) {
            return [];
        }

        $payuTransactionID = $transaction->getCustomFields()[OrderTransactionRepository::PAYU_EXTERNAL_ID];

        try {
            $response = \OpenPayU_Order::retrieveTransaction($payuTransactionID);
        } catch (\Exception $e) {
            return [];
        }

        $transactions = $this->responseToArray($response);

        if (!array_key_exists('transactions', $transactions) || !isset($transactions['transactions'][0])) {
            return [];
        }
        $transaction = $transactions['transactions'][0];

        return $this->getPaymentMethod($transaction['payMethod']['value']);
    }

    /**
     * @param \OpenPayU_Result $response
     *
     * @return array
     */
    private function responseToArray(\OpenPayU_Result $response): array
    {
        return json_decode(json_encode($response->getResponse()), true);
    }

    /**
     * @param string $payMethodKey
     *
     * @return array
     */
    private function getPaymentMethod(string $payMethodKey): array
    {
        $methods = $this->getAvailableMethods();
        if (array_key_exists($payMethodKey, $methods)) {
            return $methods[$payMethodKey];
        }

        return [];
    }

    /**
     * @return array
     */
    private function getAvailableMethods(): array
    {
        $methods = [];
        try {
            $response = OpenPayU_Retrieve::payMethods();
            if ($response->getStatus() == 'SUCCESS') {
                $methods = $this->responseToArray($response);
            }
        } catch (OpenPayU_Exception $e) {
            return [];
        }
        if (empty($methods)) {
            return [];
        }
        if (!is_array($methods['cardTokens'])) {
            $methods['cardTokens'] = [];
        }
        if (!is_array($methods['pexTokens'])) {
            $methods['pexTokens'] = [];
        }
        if (!is_array($methods['payByLinks'])) {
            $methods['payByLinks'] = [];
        }
        $methods = array_merge($methods['cardTokens'], $methods['pexTokens'], $methods['payByLinks']);
        $return = [];
        foreach ($methods as $method) {
            $key = $method['value'];
            $return[$key] = $method;
        }

        return $return;
    }
}
