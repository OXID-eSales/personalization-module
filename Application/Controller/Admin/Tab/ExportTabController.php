<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\GenerateCSVExportsDoController;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\GenerateCSVExportsMainController;

/**
 * Class used for export functionality.
 */
class ExportTabController extends \OxidEsales\Eshop\Application\Controller\Admin\GenericExport
{
    /**
     * @inheritdoc
     */
    public $sClassDo = GenerateCSVExportsDoController::class;

    /**
     * @inheritdoc
     */
    public $sClassMain = GenerateCSVExportsMainController::class;
}
