<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

/**
 * Class used for export functionality.
 */
class GenerateCSVExportsController extends \OxidEsales\Eshop\Application\Controller\Admin\GenericExport
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
