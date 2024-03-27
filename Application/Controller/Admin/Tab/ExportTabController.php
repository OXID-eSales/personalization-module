<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\HttpErrorsDisplayer;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\ConfigurationTrait;
use OxidEsales\PersonalizationModule\Application\Export\Exporter;
use OxidEsales\PersonalizationModule\Application\Export\ExporterException;
use OxidEsales\PersonalizationModule\Application\Factory;

/**
 * Class used for export functionality and used as a export tab controller.
 */
class ExportTabController extends ShopConfiguration
{
    use ConfigurationTrait;

    protected $_sThisTemplate = '@oepersonalization/admin/export_tab';

    /**
     * @var bool
     */
    private $isExportSuccessful = false;

    /**
     * @var HttpErrorsDisplayer
     */
    private $errorDisplayer;

    /**
     * @var Exporter
     */
    private $exporter;


    /**
     * @param null|Factory $factory
     */
    public function __construct($factory = null)
    {
        if (is_null($factory)) {
            $factory = oxNew(Factory::class);
        }

        $this->exporter = $factory->makeExporter();
        $this->errorDisplayer = $factory->makeHttpErrorDisplayer();

        $this->_aViewData['sClassMain'] = __CLASS__;

        parent::__construct();
    }

    /**
     * Action method to execute export.
     */
    public function executeExport()
    {
        $categoriesIds = Registry::getRequest()->getRequestEscapedParameter('acat', []);
        $shouldExportVariants = (bool) Registry::getRequest()->getRequestEscapedParameter('blExportVars', false);
        $shouldExportBaseProducts = (bool) Registry::getRequest()->getRequestEscapedParameter('blExportMainVars', false);
        $minimumQuantityInStock = Registry::getRequest()->getRequestEscapedParameter('sExportMinStock', 0);
        // TODO: Replace this hotfix with proper solution
        $relativeExportPath = Registry::getConfig()->getShopConfVar('sOePersonalizationExportPath', 1, 'module:oepersonalization');

        try {
            $this->exporter->executeExport(
                $categoriesIds,
                $shouldExportVariants,
                $shouldExportBaseProducts,
                $minimumQuantityInStock,
                $relativeExportPath,
                $this->_iEditLang
            );
            $this->isExportSuccessful = true;
        } catch (ExporterException $exception) {
            $this->errorDisplayer->addErrorToDisplay($exception->getMessage());
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
}
