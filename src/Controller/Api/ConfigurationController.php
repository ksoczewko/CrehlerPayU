<?php declare(strict_types=1);
/**
 * @copyright 2019 Crehler Sp. z o. o.
 *
 * https://crehler.com/
 * support@crehler.com
 *
 * This file is part of the PayU plugin for Shopware 6.
 * All rights reserved.
 */

namespace Crehler\PayU\Controller\Api;

use Crehler\PayU\Service\PayU\ConfigurationService;
use Crehler\PayU\Util\PayuMethodFinder;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class ConfigurationController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;

    /** @var ConfigurationService */
    private $settingsService;

    /** @var PayuMethodFinder */
    private $methodFinder;

    private SystemConfigService $systemConfigService;

    public function __construct(
        LoggerInterface $logger,
        ConfigurationService $settingsService,
        PayuMethodFinder $methodFinder,
        SystemConfigService $systemConfigService
    ) {
        $this->logger = $logger;
        $this->settingsService = $settingsService;
        $this->methodFinder = $methodFinder;
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @Route(
     *     "/api/crehler/payu/sales-channel-payment-configuration-notification",
     *     name="api.crehler.payu.sales-channel-payment-configuration-notification",
     *     methods={"POST"}
     *     )
     *
     * @param Request $request
     * @param Context $context
     * @return JsonResponse
     */
    public function salesChannelPaymentConfigurationNotification(Request $request, Context $context): JsonResponse
    {
        $paymentMethodIds = $request->get('paymentMethodIds');

        if (!in_array($this->methodFinder->getPayUPaymentMethodId($context), $paymentMethodIds)) {
            return new JsonResponse(['error' => false]);
        }

        if (!$this->settingsService->isCompleteConfiguration()) {
            return new JsonResponse(['error' => true]);
        }

        return new JsonResponse(['error' => false, 'sandbox' => $this->settingsService->isSadBox(), 'credentials' => $this->settingsService->checkSavedCredentials($request)]);
    }

    /**
     * @Route(
     *     "/api/crehler/payu/check-credentials",
     *     name="api.crehler.payu.check-credentials",
     *     methods={"POST"}
     *     )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function checkCredentials(Request $request): JsonResponse
    {
        try {
            $result = $this->settingsService->checkRequestCredentials($request);
        } catch (\Exception $e) {
            $result = false;
        }

        return $this->json($result);
    }
}
