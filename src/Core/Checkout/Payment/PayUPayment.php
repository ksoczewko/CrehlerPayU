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

namespace Crehler\PayU\Core\Checkout\Payment;

use Crehler\PayU\Entity\OrderTransactionRepository;
use Crehler\PayU\Service\PayU\OrderCreate;
use Crehler\PayU\Service\PayU\UpdateStatus;
use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Payment\Cart\AsyncPaymentTransactionStruct;
use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\AsynchronousPaymentHandlerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\StateMachine\Exception\StateMachineNotFoundException;
use Shopware\Core\System\StateMachine\Exception\StateMachineStateNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PayUPayment
 */
class PayUPayment implements AsynchronousPaymentHandlerInterface
{
    /** @var EntityRepository */
    private $orderTransactionRepository;

    /**
     * PayUPayment constructor.
     */
    public function __construct(
        private readonly OrderTransactionStateHandler $transactionStateHandler,
        private readonly OrderCreate $orderCreate,
        EntityRepository $orderTransactionRepository,
        private readonly UpdateStatus $updateStatus,
        private readonly LoggerInterface $logger
    ) {
        $this->orderTransactionRepository = $orderTransactionRepository;
    }

    /**
     * @throws \OpenPayU_Exception
     */
    public function pay(AsyncPaymentTransactionStruct $transaction, RequestDataBag $dataBag, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $order = $this->orderCreate->createOrder($transaction, $salesChannelContext);

        /** @var \OpenPayU_Result $response */
        $response = \OpenPayU_Order::create($order->toArray());

        $data = [
            'id' => $transaction->getOrderTransaction()->getId(),
            'customFields' => [
                OrderTransactionRepository::PAYU_EXTERNAL_ID => $response->getResponse()->orderId,
            ],
        ];
        $this->orderTransactionRepository->update([$data], $salesChannelContext->getContext());

        return new RedirectResponse($response->getResponse()->redirectUri);
    }

    /**
     * @throws \Exception
     */
    public function finalize(AsyncPaymentTransactionStruct $transaction, Request $request, SalesChannelContext $salesChannelContext): void
    {
        // Just redirect to Than You Page...
    }

    /**
     * @throws \OpenPayU_Exception
     * @throws InconsistentCriteriaIdsException
     * @throws StateMachineNotFoundException
     * @throws StateMachineStateNotFoundException
     */
    public function notify(AsyncPaymentTransactionStruct $transaction, Request $request, SalesChannelContext $salesChannelContext): bool
    {
        $result = \OpenPayU_Order::consumeNotification($request->getContent());
        $orderID = $result->getResponse()->order->orderId;
        $shopOrderId = $result->getResponse()->order->extOrderId;
        $paymentStatus = $result->getResponse()->order->status; // NEW PENDING CANCELED REJECTED COMPLETED WAITING_FOR_CONFIRMATION
        if ($orderID) {
            /* Check if OrderId exists in Merchant Service, update Order data by OrderRetrieveRequest */
            $order = \OpenPayU_Order::retrieve($orderID);
            if ($order->getStatus() == \OpenPayU_Order::SUCCESS) {
                $this->logger->info('PayU - Paid status: ' . $paymentStatus . ' for order: ' . $shopOrderId);

                match ($paymentStatus) {
                    \OpenPayuOrderStatus::STATUS_COMPLETED => $this->transactionStateHandler->paid($transaction->getOrderTransaction()->getId(), $salesChannelContext->getContext()),
                    \OpenPayuOrderStatus::STATUS_CANCELED => $this->transactionStateHandler->cancel($transaction->getOrderTransaction()->getId(), $salesChannelContext->getContext()),
                    \OpenPayuOrderStatus::STATUS_WAITING_FOR_CONFIRMATION => $this->updateStatus->complete($orderID),
                    default => true,
                };

                return true;
            }
            $this->transactionStateHandler->cancel($transaction->getOrderTransaction()->getId(), $salesChannelContext->getContext());

            return false;
        }

        return false;
    }
}
