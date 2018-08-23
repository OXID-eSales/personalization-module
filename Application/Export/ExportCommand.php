<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Export\Filter\ParentProductsFilter;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\Export\CsvWriter;
use OxidEsales\PersonalizationModule\Component\Export\ExportFilePathProvider;
use OxidEsales\PersonalizationModule\Component\File\FileSystem;

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
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var ExportFilePathProvider
     */
    private $exportFilePathProvider;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CsvWriter
     */
    private $csvWriter;

    /**
     * @var ParentProductsFilter
     */
    private $parentProductsFilter;

    /**
     * @var ProductDataPreparator
     */
    private $productDataPreparator;

    /**
     * @var CategoryDataPreparator
     */
    private $categoryDataPreparator;

    /**
     * @param array   $cliArguments
     * @param Factory $factory
     */
    public function __construct($cliArguments, $factory)
    {
        Registry::getConfig()->setAdminMode(true);
        $this->config = $this->getConfigurationParameters($cliArguments);
        Registry::getConfig()->setShopId($this->config['shopId']);
        if (!Registry::getConfig()->getActiveView()->getViewConfig()->isModuleActive('oepersonalization')) {
            exit('Please activate the "OXID personalization powered by Econda" module before running the script.' . "\n");
        }

        $this->exportFilePathProvider = $factory->makeExportFilePathProvider();
        $this->productRepository = $factory->makeProductRepositoryForExport();
        $this->csvWriter = $factory->makeCsvWriterForExport();
        $this->parentProductsFilter = $factory->makeParentProductsFilterForExport();
        $this->productDataPreparator = $factory->makeProductDataPreparatorForExport();
        $this->categoryDataPreparator = $factory->makeCategoryDataPreparatorForExport();
        $this->fileSystem = $factory->makeFileSystem();
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

        $directoryForFileToExport = $this->exportFilePathProvider->makeDirectoryPath($relativeExportPath);
        if ($this->fileSystem->createDirectory($directoryForFileToExport) === false) {
            exit('Unable to create directory ' . $directoryForFileToExport);
        } else {
            $productsDataForExport = $this->productRepository
                ->findProductsToExport(0, $shouldExportVariants, $categoriesIds, $minimumQuantityInStock);

            if ($shouldExportBaseProducts === false) {
                $productsDataForExport = $this->parentProductsFilter->filterOutParentProducts($productsDataForExport);
            }

            $productsToExport = $this->productDataPreparator->appendDataForExport($productsDataForExport);
            $categoriesToExport = $this->categoryDataPreparator->prepareDataForExport($categoriesIds);
            try {
                $this->executeWritingToFile($relativeExportPath, $productsToExport, $categoriesToExport);
            } catch (\Exception $exception) {
                exit('Error occurred while writing data to file with message: ' . $exception->getMessage());
            }
        }

        print("Export completed.\n");
        exit(0);
    }

    /**
     * @param string $relativeExportPath
     * @param array  $productsToExport
     * @param array  $categoriesToExport
     */
    private function executeWritingToFile($relativeExportPath, $productsToExport, $categoriesToExport)
    {
        $this->csvWriter->write(
            $this->exportFilePathProvider->makeProductsFilePath($relativeExportPath),
            $productsToExport
        );
        $this->csvWriter->write(
            $this->exportFilePathProvider->makeCategoriesFilePath($relativeExportPath),
            $categoriesToExport
        );
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
}
