<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Feed;

class GenerateCSVExports extends \OxidEsales\Eshop\Application\Controller\Admin\GenericExport
{
    /**
     * @inheritdoc
     */
    public $sClassDo = GenerateCSVExportsDo::class;

    /**
     * @inheritdoc
     */
    public $sClassMain = GenerateCSVExportsMain::class;
}
