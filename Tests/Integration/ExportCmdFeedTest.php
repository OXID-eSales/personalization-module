<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use OxidEsales\Eshop\Core\Registry;

class ExportCmdFeedTest extends ExportDataInCSVTest
{
    private $configFile = null;

    protected $exportPath = 'export/test_oeeconda';

    protected function tearDown()
    {
        parent::tearDown();
        $exportDir = Registry::getConfig()->getConfigParam('sShopDir') . $this->exportPath;
        if (file_exists($exportDir.'/products.csv')) {
            unlink($exportDir.'/products.csv');
        }
        if (file_exists($exportDir.'/categories.csv')) {
            unlink($exportDir.'/categories.csv');
        }
        if (file_exists($exportDir) && is_dir($exportDir)) {
            rmdir($exportDir);
        }
        $configFile = __DIR__.'/../fixtures/config/params.php';
        file_put_contents($configFile, '<?php ');
    }

    protected function prepareShopStructureForExport()
    {
        return Registry::getConfig()->getConfigParam('sShopDir');
    }

    protected function setParametersForExport($exportParentProducts, $exportVars, $categories, $minStock, $exportPath)
    {
        $fileContent = '<?php return ["blExportVars" => '.
            json_encode($exportVars).', "blExportMainVars" => '.
            json_encode($exportParentProducts).', "acat" => '.
            json_encode($categories).', "sOeEcondaExportPath" => "'.
            $exportPath.'", "sExportMinStock" => '.
            json_encode($minStock).', "iStart" => 1];';

        $configFile = __DIR__.'/../fixtures/config/params.php';
        file_put_contents($configFile, $fileContent);

        $this->configFile = $configFile;
    }

    protected function prepareShopUrlForExport()
    {
        return Registry::getConfig()->getConfigParam('sShopURL');
    }

    protected function runExport($configFile = null)
    {
        $addArguments = ($this->configFile ) ? ' --config ' . escapeshellarg($this->configFile) : '';
        $feedFile = VENDOR_PATH. '/bin/oe-personalization-data-feed';
        passthru(escapeshellarg($feedFile) . $addArguments, $returnOutput);
    }
}
