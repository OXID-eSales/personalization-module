<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\ExportTabController;

/**
 * Shop contains actions to save export functionality configuration values
 */
class ExportConfigurationController extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    use ConfigurationTrait;

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function save()
    {
        parent::save();

        \OxidEsales\Eshop\Core\Registry::getUtils()->oxResetFileCache();

        return ExportTabController::class;
    }
}
