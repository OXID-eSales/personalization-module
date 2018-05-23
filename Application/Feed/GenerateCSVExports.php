<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Feed;

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
