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

namespace Crehler\PayU\Core\Checkout\Payment;

use Crehler\PayU\Entity\OrderTransactionRepository;
use Crehler\PayU\Service\PayU\OrderCreate;
use Crehler\PayU\Service\PayU\UpdateStatus;
use OpenPayU_Exception;
use OpenPayU_Order;
use OpenPayuOrderStatus;
use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Payment\Cart\AsyncPaymentTransactionStruct;
use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\AsynchronousPaymentHandlerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PayUPayment
 */
class PayUPayment implements AsynchronousPaymentHandlerInterface
{
    /**
     * @var OrderTransactionStateHandler
     */
    private $transactionStateHandler;

    /**
     * @var OrderCreate
     */
    private $orderCreate;

    /** @var EntityRepositoryInterface */
    private $orderTransactionRepository;

    /** @var UpdateStatus */
    private $updateStatus;

    /** @var LoggerInterface */
    private $logger;

    /**
     * PayUPayment constructor.
     *
     * @param OrderTransactionStateHandler $transactionStateHandler
     * @param OrderCreate                  $orderCreate
     * @param EntityRepositoryInterface    $orderTransactionRepository
     * @param UpdateStatus                 $updateStatus
     * @param LoggerInterface              $logger
     */
    public function __construct(
        OrderTransactionStateHandler $transactionStateHandler,
        OrderCreate $orderCreate,
        EntityRepositoryInterface $orderTransactionRepository,
        UpdateStatus $updateStatus,
        LoggerInterface $logger
    ) {
        $this->transactionStateHandler = $transactionStateHandler;
        $this->orderCreate = $orderCreate;
        $this->orderTransactionRepository = $orderTransactionRepository;
        $this->updateStatus = $updateStatus;
        $this->logger = $logger;
    }

    /**
     * @param AsyncPaymentTransactionStruct $transaction
     * @param RequestDataBag                $dataBag
     * @param SalesChannelContext           $salesChannelContext
     *
     * @throws OpenPayU_Exception
     *
     * @return RedirectResponse
     */
    public function pay(AsyncPaymentTransactionStruct $transaction, RequestDataBag $dataBag, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $order = $this->orderCreate->createOrder($transaction, $salesChannelContext);

        /** @var \OpenPayU_Result $response */
        $response = OpenPayU_Order::create($order->toArray());

        $data = [
            'id' => $transaction->getOrderTransaction()->getId(),
            'customFields' => [
                OrderTransactionRepository::PAYU_EXTERNAL_ID => $response->getResponse()->orderId,
            ],
        ];
        $this->orderTransactionRepository->update([$data], $salesChannelContext->getContext());

        return  new RedirectResponse($response->getResponse()->redirectUri);
    }

    /**
     * @param AsyncPaymentTransactionStruct $transaction
     * @param Request                       $request
     * @param SalesChannelContext           $salesChannelContext
     *
     * @throws \Exception
     */
    public function finalize(AsyncPaymentTransactionStruct $transaction, Request $request, SalesChannelContext $salesChannelContext): void
    {
        // Just redirect to Than You Page...
    }

    /**
     * @param AsyncPaymentTransactionStruct $transaction
     * @param Request                       $request
     * @param SalesChannelContext           $salesChannelContext
     *
     * @throws OpenPayU_Exception
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineNotFoundException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineStateNotFoundException
     *
     * @return bool
     */
    public function notify(AsyncPaymentTransactionStruct $transaction, Request $request, SalesChannelContext $salesChannelContext): bool
    {
        $result = OpenPayU_Order::consumeNotification($request->getContent());
        $orderID = $result->getResponse()->order->orderId;
        $shopOrderId = $result->getResponse()->order->extOrderId;
        $paymentStatus = $result->getResponse()->order->status; //NEW PENDING CANCELED REJECTED COMPLETED WAITING_FOR_CONFIRMATION
        if ($orderID) {
            /* Check if OrderId exists in Merchant Service, update Order data by OrderRetrieveRequest */
            $order = OpenPayU_Order::retrieve($orderID);
            if ($order->getStatus() == OpenPayU_Order::SUCCESS) {
                $this->logger->info('PayU - Paid status: ' . $paymentStatus . ' for order: ' . $shopOrderId);

                switch ($paymentStatus) {
                    case OpenPayuOrderStatus::STATUS_COMPLETED:
                        $this->transactionStateHandler->paid($transaction->getOrderTransaction()->getId(), $salesChannelContext->getContext());
                        break;
                    case OpenPayuOrderStatus::STATUS_CANCELED:
                        $this->transactionStateHandler->cancel($transaction->getOrderTransaction()->getId(), $salesChannelContext->getContext());
                        break;
                    case OpenPayuOrderStatus::STATUS_WAITING_FOR_CONFIRMATION:
                        $this->updateStatus->complete($orderID);
                        break;
                }

                return true;
            }
            $this->transactionStateHandler->cancel($transaction->getOrderTransaction()->getId(), $salesChannelContext->getContext());

            return false;
        }

        return false;
    }
}
