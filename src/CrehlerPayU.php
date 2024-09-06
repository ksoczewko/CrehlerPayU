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
    public function install(InstallContext $context): void
    {
        (new Install($this->container, $context))->install();
        parent::install($context);
    }

    public function uninstall(UninstallContext $context): void
    {
        (new Uninstall($this->container, $context))->uninstall();

        parent::uninstall($context);
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function activate(ActivateContext $context): void
    {
        (new Activate($this->container, $context))->activate();

        parent::activate($context);
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function deactivate(DeactivateContext $context): void
    {
        (new Deactivate($this->container, $context))->deactivate();

        parent::deactivate($context);
    }
}
