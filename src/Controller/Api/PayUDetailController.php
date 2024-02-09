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

namespace Crehler\PayU\Controller\Api;

use Crehler\PayU\Service\PayU\TransactionDetails;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"api"}})
 */
class PayUDetailController extends AbstractController
{
    /**
     * PayUDetailController constructor.
     */
    public function __construct(private readonly TransactionDetails $transactionDetails)
    {
    }

    /**
     *
     *
     * @return JsonResponse
     */
    #[Route(path: '/api/crehler/payu/detail/{id}', name: 'api.action.crehler.payu.detail', methods: ['GET'])]
    public function getDetailInfo(string $id, Request $request, Context $context): JsonResponse
    {
        $data = $this->transactionDetails->getData($id, $context);
        if (!empty($data)) {
            return new JsonResponse([
                'isPayU' => true,
                'method' => $data,
            ]);
        }

        return new JsonResponse([
            'isPayU' => false,
            'method' => [],
        ]);
    }
}
