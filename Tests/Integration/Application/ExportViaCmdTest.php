<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Core\Registry;

class ExportViaCmdTest extends AbstractExportDataInCSV
{
    private $configFile = null;

    private $phpBin;

    protected $exportPath = 'export/test_oepersonalization';

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->phpBin = getenv('PHPBIN') ? getenv('PHPBIN') . ' ': '';
        parent::__construct($name, $data, $dataName);
    }

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
        $configFile = __DIR__.'/../../fixtures/config/params.php';
        file_put_contents($configFile, '<?php ');
    }

    protected function prepareShopStructureForExport()
    {
        return Registry::getConfig()->getConfigParam('sShopDir');
    }

    protected function setParametersForExport($exportParentProducts, $exportVars, $categories, $minStock, $exportPath, $shopId)
    {
        $fileContent = '<?php return ["exportVariants" => '.
            json_encode($exportVars).', "shopId" => '.
            $shopId.', "exportVariantsParentProduct" => '.
            json_encode($exportParentProducts).', "exportCategories" => '.
            json_encode($categories).', "exportPath" => "'.
            $exportPath.'", "exportMinStock" => '.
            json_encode($minStock).'];';

        $configFile = __DIR__.'/../../fixtures/config/params.php';
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
        exec($this->phpBin . ' ' . escapeshellarg($feedFile) . $addArguments, $returnOutput);
        $isFound = false;
        if (array_search('Export completed.', $returnOutput) !== false) {
            $isFound = true;
        }
        $this->assertTrue($isFound);
    }

    public function testIfWrongCommandWillBeUsed()
    {
        $configFile = __DIR__.'/../../fixtures/config/params.php';
        $feedFile = VENDOR_PATH. '/bin/oe-personalization-data-feed';
        exec($this->phpBin . ' ' . escapeshellarg($feedFile) . ' --configuration ' . escapeshellarg($configFile), $returnOutput);

        $message = 'Unknown command: --configuration'.
            '. If you want to override the configuration file for the export, please, use the "--config" command';
        $this->assertEquals($message, $returnOutput[0]);
    }

    public function testIfNotExistingConfigFileWillBeUsed()
    {
        $configFile = __DIR__.'/../../fixtures/config/wrong_name.php';
        $feedFile = VENDOR_PATH. '/bin/oe-personalization-data-feed';
        exec($this->phpBin . ' ' . escapeshellarg($feedFile) . ' --config ' . escapeshellarg($configFile), $returnOutput);

        $message = 'File does not exist: ' . $configFile;
        $this->assertEquals($message, $returnOutput[0]);
    }

    public function testIfConfigFileIsEmpty()
    {
        $configFile = __DIR__.'/../../fixtures/config/params.php';
        $feedFile = VENDOR_PATH. '/bin/oe-personalization-data-feed';
        exec($this->phpBin . ' ' . escapeshellarg($feedFile) . ' --config ' . escapeshellarg($configFile), $returnOutput);

        $message = 'Config file has wrong format.';
        $this->assertEquals($message, $returnOutput[0]);
    }
}
