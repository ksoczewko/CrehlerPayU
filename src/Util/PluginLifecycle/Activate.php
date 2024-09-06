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

namespace Crehler\PayU\Util\PluginLifecycle;

use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;

final class Activate extends AbstractLifecycle
{
    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function activate()
    {
        $this->paymentMethodUtil->setPaymentMethodIsActive(true);
        $this->transactionFieldsUtil->createTransactionFields();
    }
}
