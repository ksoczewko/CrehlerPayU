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
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class PayUNotifyController extends StorefrontController
{
    /**
     * PayUNotifyController constructor.
     */
    public function __construct(private readonly FinalizeTokenGenerator $finalizeTokenGenerator, private readonly PayUPayment $payUPayment, private readonly LoggerInterface $logger)
    {
    }

    #[Route(path: '/crehler/payu/notify', name: 'action.crehler.payu.notify', options: ['seo' => 'false'], methods: ['POST'], defaults: ['csrf_protected' => false])]
    public function notifyAction(
        Request $request,
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

        return new JsonResponse(['success' => $status], $status ? 200 : 500);
    }
}
