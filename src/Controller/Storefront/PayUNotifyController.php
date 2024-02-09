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

namespace Crehler\PayU\Controller\Storefront;

use Crehler\PayU\Core\Checkout\Payment\PayUPayment;
use Crehler\PayU\Service\FinalizeTokenGenerator;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PayUNotifyController
 *
 * @RouteScope(scopes={"storefront"})
 */
class PayUNotifyController extends StorefrontController
{
    /** @var FinalizeTokenGenerator */
    private $finalizeTokenGenerator;

    /** @var PayUPayment */
    private $payUPayment;

    /** @var LoggerInterface */
    private $logger;

    /**
     * PayUNotifyController constructor.
     *
     * @param FinalizeTokenGenerator $finalizeTokenGenerator
     * @param PayUPayment $payUPayment
     * @param LoggerInterface $logger
     */
    public function __construct(
        FinalizeTokenGenerator $finalizeTokenGenerator,
        PayUPayment            $payUPayment,
        LoggerInterface        $logger
    )
    {
        $this->finalizeTokenGenerator = $finalizeTokenGenerator;
        $this->payUPayment = $payUPayment;
        $this->logger = $logger;
    }

    /**
     * @Route(
     *     "/crehler/payu/notify",
     *     name="action.crehler.payu.notify",
     *     options={"seo"="false"},
     *     methods={"POST"},
     *     defaults={"csrf_protected"=false}
     *     )
     */
    public function notifyAction(
        Request             $request,
        SalesChannelContext $salesChannelContext): JsonResponse
    {
        $token = $request->get('_sw_payment_token');
        if (empty($token)) {
            $this->logger->error('A token is required and the notify action.');

            return new JsonResponse(['success' => false], 500);
        }

        $paymentTransactionStruct = $this->finalizeTokenGenerator->getTransationDetails($token, $salesChannelContext);

        try {
            $status = $this->payUPayment->notify($paymentTransactionStruct, $request, $salesChannelContext);
        } catch (\Exception $e) {
            $this->logger->error('Crehler PayU notify exception ' . $e->getCode() . ' in file: ' . $e->getFile());
            $this->logger->error('on line: ' . $e->getLine());
            $this->logger->error('with a message: ' . $e->getMessage());

            return new JsonResponse(['success' => false], 500);
        }

        return new JsonResponse(['success' => $status], ($status ? 200 : 500));
    }
}
