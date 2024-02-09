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
 * @RouteScope(scopes={"api"})
 */
class PayUDetailController extends AbstractController
{
    /** @var TransactionDetails */
    private $transactionDetails;

    /**
     * PayUDetailController constructor.
     *
     * @param TransactionDetails $transactionDetails
     */
    public function __construct(TransactionDetails $transactionDetails)
    {
        $this->transactionDetails = $transactionDetails;
    }

    /**
     * @Route("/api/crehler/payu/detail/{id}", name="api.action.crehler.payu.detail", methods={"GET"})
     *
     * @param string  $id
     * @param Request $request
     * @param Context $context
     *
     * @return JsonResponse
     */
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
