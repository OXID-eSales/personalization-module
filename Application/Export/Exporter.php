<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export;

use OxidEsales\PersonalizationModule\Application\Export\Filter\ParentProductsFilter;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\Export\CsvWriter;
use OxidEsales\PersonalizationModule\Component\Export\ExportFilePathProvider;
use OxidEsales\PersonalizationModule\Component\File\FileSystem;

/**
 * Class is used to execute products/categories export.
 */
class Exporter
{
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
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var ExportFilePathProvider
     */
    private $exportFilePathProvider;

    /**
     * @param null|Factory $factory
     */
    public function __construct($factory = null)
    {
        if (is_null($factory)) {
            $factory = oxNew(Factory::class);
        }
        $this->productRepository = $factory->makeProductRepositoryForExport();
        $this->csvWriter = $factory->makeCsvWriterForExport();
        $this->parentProductsFilter = $factory->makeParentProductsFilterForExport();
        $this->productDataPreparator = $factory->makeProductDataPreparatorForExport();
        $this->categoryDataPreparator = $factory->makeCategoryDataPreparatorForExport();
        $this->fileSystem = $factory->makeFileSystem();
        $this->exportFilePathProvider = $factory->makeExportFilePathProvider();
    }

    /**
     * Action method to execute export.
     *
     * @param array  $categoriesIds
     * @param bool   $shouldExportVariants
     * @param bool   $shouldExportBaseProducts
     * @param int    $minimumQuantityInStock
     * @param string $relativeExportPath
     * @param int    $languageId
     *
     * @throws ExporterException
     */
    public function executeExport(
        array $categoriesIds,
        bool $shouldExportVariants,
        bool $shouldExportBaseProducts,
        int $minimumQuantityInStock,
        string $relativeExportPath,
        int $languageId
    ) {
        $directoryForFileToExport = $this->exportFilePathProvider->makeDirectoryPath($relativeExportPath);
        if ($this->fileSystem->createDirectory($directoryForFileToExport) === false) {
            throw new ExporterException(
                'Unable to create directory '
                . $directoryForFileToExport
                . '. Add write permissions for active user or create this '
                . ' directory with write permissions manually.'
            );
        } else {
            $productsDataForExport = $this->productRepository
                ->findProductsToExport($languageId, $shouldExportVariants, $categoriesIds, $minimumQuantityInStock);

            if ($shouldExportBaseProducts === false) {
                $productsDataForExport = $this->parentProductsFilter->filterOutParentProducts($productsDataForExport);
            }

            $productsToExport = $this->productDataPreparator->appendDataForExport($productsDataForExport);
            $categoriesToExport = $this->categoryDataPreparator->prepareDataForExport($categoriesIds);
            try {
                $this->executeWritingToFile($relativeExportPath, $productsToExport, $categoriesToExport);
            } catch (\Exception $exception) {
                throw new ExporterException(
                    'Error occurred while writing data to file with message: '
                    . $exception->getMessage()
                );
            }
        }
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
}
