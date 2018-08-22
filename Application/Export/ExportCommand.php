<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\ExportTabController;

/**
 * Class used only for CLI to execute export.
 */
class ExportCommand
{
    /**
     * @param array $cliArguments
     */
    public function export($cliArguments)
    {
        if (!Registry::getConfig()->getActiveView()->getViewConfig()->isModuleActive('oepersonalization')) {
            exit('Please activate the "OXID personalization powered by Econda" module before running the script.' . "\n");
        }

        $config = $this->getConfigurationParameters($cliArguments);

        $_POST['blExportVars'] = (isset($config['exportVariants'])) ? $config['exportVariants'] : false;
        $_POST['blExportMainVars'] = (isset($config['exportVariantsParentProduct'])) ? $config['exportVariantsParentProduct'] : true;
        $_POST['acat'] = (isset($config['exportCategories'])) ? $config['exportCategories'] : [];
        $_POST['sExportMinStock'] = (isset($config['exportMinStock'])) ? $config['exportMinStock'] : 1;
        $exportPath = (isset($config['exportPath'])) ? $config['exportPath'] : null;

        /** @var ExportTabController $export */
        $export = oxNew(ExportTabController::class, null, $exportPath);

        $export->executeExport();

        $exportMessage = $this->getExportMessage($export);
        print($exportMessage . "\n");
        exit(0);
    }

    /**
     * @param array $cliArguments
     *
     * @return string
     */
    private function getConfigFile($cliArguments)
    {
        $configFile = Registry::getConfig()->getActiveView()->getViewConfig()->getModulePath('oepersonalization', 'config/default_params.php');
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

    /**
     * @param ExportTabController $export
     *
     * @return string
     */
    private function getExportMessage($export)
    {
        $isExportSuccessful = $export->isExportSuccessful();
        if ($isExportSuccessful === true) {
            $message = 'Export completed.';
        } else {
            $message = 'Not able to execute export.';
        }

        return $message;
    }
}
