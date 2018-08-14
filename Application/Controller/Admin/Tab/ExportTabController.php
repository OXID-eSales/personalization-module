<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\ErrorDisplayTrait;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\ConfigurationTrait;
use OxidEsales\PersonalizationModule\Application\Export\CategoryDataPreparator;
use OxidEsales\PersonalizationModule\Application\Export\Filter\ParentProductsFilter;
use OxidEsales\PersonalizationModule\Application\Export\ProductDataPreparator;
use OxidEsales\PersonalizationModule\Application\Export\ProductRepository;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\Export\CsvWriter;
use OxidEsales\PersonalizationModule\Component\Export\ExportFilePathProvider;
use OxidEsales\PersonalizationModule\Component\File\FileSystem;

/**
 * Class used for export functionality and used as a export tab controller.
 */
class ExportTabController extends ShopConfiguration
{
    use ConfigurationTrait;
    use ErrorDisplayTrait;

    protected $_sThisTemplate = 'oepersonalization_export_tab.tpl';

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
     * @var string
     */
    private $relativeExportPath;

    /**
     * @var bool
     */
    private $isExportSuccessful = false;


    /**
     * @param null|Factory $factory
     * @param string       $relativeExportPath
     */
    public function __construct($factory = null, $relativeExportPath = '')
    {
        if (is_null($factory)) {
            $factory = oxNew(Factory::class);
        }
        $this->relativeExportPath = $relativeExportPath;
        if (empty($relativeExportPath)) {
            $this->relativeExportPath = Registry::getConfig()->getConfigParam('sOePersonalizationExportPath');
        }
        $this->productRepository = $factory->makeProductRepositoryForExport();
        $this->csvWriter = $factory->makeCsvWriterForExport();
        $this->parentProductsFilter = $factory->makeParentProductsFilterForExport();
        $this->productDataPreparator = $factory->makeProductDataPreparatorForExport();
        $this->categoryDataPreparator = $factory->makeCategoryDataPreparatorForExport();
        $this->fileSystem = $factory->makeFileSystem();
        $this->exportFilePathProvider = $factory->makeExportFilePathProvider();

        $this->_aViewData['sClassMain'] = __CLASS__;

        parent::__construct();
    }

    /**
     * Action method to execute export.
     */
    public function executeExport()
    {
        $categoriesIds = Registry::getRequest()->getRequestEscapedParameter('acat', []);
        $shouldExportVariants = Registry::getRequest()->getRequestEscapedParameter('blExportVars', true);
        $shouldExportBaseProducts = Registry::getRequest()->getRequestEscapedParameter('blExportMainVars', true);
        $minimumQuantityInStock = Registry::getRequest()->getRequestEscapedParameter('sExportMinStock', 0);
        $relativeExportPath = $this->relativeExportPath;

        $directoryForFileToExport = $this->exportFilePathProvider->makeDirectoryPath($relativeExportPath);
        if ($this->fileSystem->createDirectory($directoryForFileToExport) === false) {
            $this->addErrorToDisplay(
                'Unable to create directory '
                . $directoryForFileToExport
                . '. Add write permissions for web user or create this '
                . ' directory with write permissions manually.'
            );
        } else {
            $productsDataForExport = $this->productRepository
                ->findProductsToExport($this->_iEditLang, $shouldExportVariants, $categoriesIds, $minimumQuantityInStock);

            if ($shouldExportBaseProducts === false) {
                $productsDataForExport = $this->parentProductsFilter->filterOutParentProducts($productsDataForExport);
            }

            $productsToExport = $this->productDataPreparator->appendDataForExport($productsDataForExport);
            $categoriesToExport = $this->categoryDataPreparator->prepareDataForExport($categoriesIds);
            try {
                $this->executeWritingToFile($relativeExportPath, $productsToExport, $categoriesToExport);
                $this->isExportSuccessful = true;
            } catch (\Exception $exception) {
                $this->addErrorToDisplay(
                    'Error occurred while writing data to file with message: '
                    . $exception->getMessage()
                );
            }
        }
    }

    /**
     * @return \OxidEsales\Eshop\Application\Model\CategoryList
     */
    public function getCategoryList()
    {
        $categoryList = oxNew(\OxidEsales\Eshop\Application\Model\CategoryList::class);
        $categoryList->loadList();

        return $categoryList;
    }

    /**
     * @return bool
     */
    public function isExportSuccessful()
    {
        return $this->isExportSuccessful;
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
