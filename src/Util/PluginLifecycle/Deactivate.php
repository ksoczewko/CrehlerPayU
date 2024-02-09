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

namespace Crehler\PayU\Util\PluginLifecycle;

use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
final class Deactivate extends AbstractLifecycle
{
    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function deactivate()
    {
        $this->paymentMethodUtil->setPaymentMethodIsActive(false);
        $this->transactionFieldsUtil->removeTransactionFields();
    }
}
