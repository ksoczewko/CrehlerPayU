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

namespace Crehler\PayU;

use Crehler\PayU\Util\PluginLifecycle\Activate;
use Crehler\PayU\Util\PluginLifecycle\Deactivate;
use Crehler\PayU\Util\PluginLifecycle\Install;
use Crehler\PayU\Util\PluginLifecycle\Uninstall;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

/**
 * Class CrehlerPayU
 */
class CrehlerPayU extends Plugin
{
    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context): void
    {
        (new Install($this->container, $context))->install();
        parent::install($context);
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context): void
    {
        (new Uninstall($this->container, $context))->uninstall();

        parent::uninstall($context);
    }

    /**
     * @param ActivateContext $context
     *
     * @throws InconsistentCriteriaIdsException
     */
    public function activate(ActivateContext $context): void
    {
        (new Activate($this->container, $context))->activate();

        parent::activate($context);
    }

    /**
     * @param DeactivateContext $context
     *
     * @throws InconsistentCriteriaIdsException
     */
    public function deactivate(DeactivateContext $context): void
    {
        (new Deactivate($this->container, $context))->deactivate();

        parent::deactivate($context);
    }
}
