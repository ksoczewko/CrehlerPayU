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

namespace Crehler\PayU\Service;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use Shopware\Core\Checkout\Payment\Cart\AsyncPaymentTransactionStruct;
use Shopware\Core\Checkout\Payment\Cart\Token\TokenFactoryInterfaceV2 as TokenFactoryInterface;
use Shopware\Core\Checkout\Payment\Cart\Token\TokenStruct;
use Shopware\Core\Checkout\Payment\Exception\InvalidTransactionException;
use Shopware\Core\Checkout\Payment\Exception\TokenExpiredException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class FinalizeTokenGenerator
 */
class FinalizeTokenGenerator
{
    /**
     * @var EntityRepository
     */
    private $orderTransactionRepository;

    /**
     * FinalizeTokenGenerator constructor.
     *
     * @param TokenFactoryInterface     $tokenFactory
     */
    public function __construct(private readonly TokenFactoryInterface $tokenFactory, private readonly RouterInterface $router, EntityRepository $orderTransactionRepository)
    {
        $this->orderTransactionRepository = $orderTransactionRepository;
    }

    /**
     * @return string
     */
    public function buildUrl(OrderTransactionEntity $orderTransactionEntity): string
    {
        $tokenStruct = new TokenStruct(
            null,
            null,
            $orderTransactionEntity->getPaymentMethodId(),
            $orderTransactionEntity->getId(),
            null,
            288000,
            null
        );
        $token = $this->tokenFactory->generateToken($tokenStruct);

        return $this->assembleReturnUrl($token);
    }

    /**
     *
     * @throws InconsistentCriteriaIdsException
     * @throws InvalidTransactionException
     * @throws TokenExpiredException
     *
     * @return AsyncPaymentTransactionStruct
     */
    public function getTransationDetails(
        string $paymentToken,
        SalesChannelContext $salesChannelContext
    ): AsyncPaymentTransactionStruct {
        $paymentTokenStruct = $this->parseToken($paymentToken);
        $transactionId = $paymentTokenStruct->getTransactionId();
        $context = $salesChannelContext->getContext();
        $paymentTransactionStruct = $this->getPaymentTransactionStruct($transactionId, $context);

        return $paymentTransactionStruct;
    }

    /**
     * @return string
     */
    private function assembleReturnUrl(string $token): string
    {
        $parameter = ['_sw_payment_token' => $token];

        return $this->router->generate('action.crehler.payu.notify', $parameter, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     *
     * @throws TokenExpiredException
     * @return TokenStruct
     */
    private function parseToken(string $token): TokenStruct
    {
        $tokenStruct = $this->tokenFactory->parseToken($token);

        if ($tokenStruct->isExpired()) {
            throw new TokenExpiredException($tokenStruct->getToken());
        }

        // $this->tokenFactory->invalidateToken($tokenStruct->getToken());

        return $tokenStruct;
    }

    /**
     *
     * @throws InvalidTransactionException
     * @throws InconsistentCriteriaIdsException
     *
     * @return AsyncPaymentTransactionStruct
     */
    private function getPaymentTransactionStruct(string $orderTransactionId, Context $context): AsyncPaymentTransactionStruct
    {
        $criteria = new Criteria([$orderTransactionId]);
        $criteria->addAssociation('order');
        /** @var OrderTransactionEntity|null $orderTransaction */
        $orderTransaction = $this->orderTransactionRepository->search($criteria, $context)->first();

        if ($orderTransaction === null) {
            throw new InvalidTransactionException($orderTransactionId);
        }

        return new AsyncPaymentTransactionStruct($orderTransaction, $orderTransaction->getOrder(), '');
    }
}
