<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\ExportTabController;

class ExportViaAdminTest extends AbstractExportDataInCSV
{
    /**
     * @inheritdoc
     */
    protected function setParametersForExport($exportParentProducts, $exportVars, $categories, $minStock, $exportPath, $shopId)
    {
        $this->setRequestParameter('acat', $categories);
        $this->setRequestParameter('sExportMinStock', $minStock);
        $this->setRequestParameter('blExportMainVars', $exportParentProducts);
        $this->setRequestParameter('blExportVars', $exportVars);
        $this->setRequestParameter("iStart", 1);
        Registry::getConfig()->setConfigParam('sOePersonalizationExportPath', $exportPath);
    }


    protected function runExport()
    {
        $controller = oxNew(ExportTabController::class);
        $controller->executeExport();
    }
}
