<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export\Cli;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Export\Exporter;
use OxidEsales\PersonalizationModule\Application\Export\ExporterException;
use OxidEsales\PersonalizationModule\Application\Factory;
use Webmozart\PathUtil\Path;

/**
 * Class used only for CLI to execute export.
 */
class ExportCommand
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $eShopSourcePath;

    /**
     * @var Exporter
     */
    private $exporter;

    /**
     * @param array   $cliArguments
     * @param Factory $factory
     * @param string  $eShopSourcePath
     */
    public function __construct($cliArguments, $factory, string $eShopSourcePath)
    {
        Registry::getConfig()->setAdminMode(true);
        $this->eShopSourcePath = $eShopSourcePath;
        $this->config = $this->getConfigurationParameters($cliArguments);
        Registry::getConfig()->setShopId($this->config['shopId']);
        if (!Registry::getConfig()->getActiveView()->getViewConfig()->isModuleActive('oepersonalization')) {
            exit('Please activate the "OXID personalization powered by Econda" module before running the script.' . "\n");
        }
        $this->exporter = $factory->makeExporter();
    }

    /**
     * Method executes export.
     */
    public function export()
    {
        $categoriesIds = (isset($this->config['exportCategories'])) ? $this->config['exportCategories'] : [];
        $shouldExportVariants = (isset($this->config['exportVariants'])) ? $this->config['exportVariants'] : false;
        $shouldExportBaseProducts = (isset($this->config['exportVariantsParentProduct'])) ? $this->config['exportVariantsParentProduct'] : true;
        $minimumQuantityInStock = (isset($this->config['exportMinStock'])) ? $this->config['exportMinStock'] : 1;
        $relativeExportPath = (isset($this->config['exportPath'])) ? $this->config['exportPath'] : null;

        try {
            $this->exporter->executeExport(
                $categoriesIds,
                $shouldExportVariants,
                $shouldExportBaseProducts,
                $minimumQuantityInStock,
                $relativeExportPath,
                0
            );
        } catch (ExporterException $exception) {
            exit($exception->getMessage());
        }

        print("Export completed.\n");
        exit(0);
    }

    /**
     * @param array $cliArguments
     *
     * @return string
     */
    private function getConfigFile($cliArguments)
    {
        $configFile = Path::join($this->eShopSourcePath, 'modules/oe/personalization/config/default_params.php');
        array_shift($cliArguments);
        if (isset($cliArguments[0])) {
            if ($cliArguments[0] === '--config') {
                $configFile = (isset($cliArguments[1])) ? $cliArguments[1] : '';
                if (!file_exists($configFile)) {
                    exit('File does not exist: ' . $configFile . "\n");
                }
            } else {
                $message = 'Unknown command: ' . $cliArguments[0] .
                    '. If you want to override the configuration file for the export, please, use the "--config" command' .
                    "\n";
                exit($message);
            }
        }

        if (!file_exists($configFile)) {
            exit('Config file is missing: ' . $configFile . "\n");
        }
        return $configFile;
    }

    /**
     * @param array $argv
     *
     * @return array
     */
    private function getConfigurationParameters($argv)
    {
        $configFile = $this->getConfigFile($argv);

        $config = include $configFile;

        if (!is_array($config)) {
            exit('Config file has wrong format.'."\n");
        }

        return $config;
    }
}
