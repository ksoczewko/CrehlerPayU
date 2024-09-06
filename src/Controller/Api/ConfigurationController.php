<?php
declare(strict_types=1);
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
 * @Route(defaults={"_routeScope"={"api"}})
 */
class ConfigurationController extends AbstractController
{
    public function __construct(private readonly LoggerInterface $logger, private readonly ConfigurationService $settingsService, private readonly PayuMethodFinder $methodFinder, private readonly SystemConfigService $systemConfigService)
    {
    }

    #[Route(path: '/api/crehler/payu/sales-channel-payment-configuration-notification', name: 'api.crehler.payu.sales-channel-payment-configuration-notification', methods: ['POST'])]
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

    #[Route(path: '/api/crehler/payu/check-credentials', name: 'api.crehler.payu.check-credentials', methods: ['POST'])]
    public function checkCredentials(Request $request): JsonResponse
    {
        try {
            $result = $this->settingsService->checkRequestCredentials($request);
        } catch (\Exception) {
            $result = false;
        }

        return $this->json($result);
    }
}
