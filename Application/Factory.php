<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application;

use OxidEsales\PersonalizationModule\Application\Controller\Admin\HttpErrorsDisplayer;
use OxidEsales\PersonalizationModule\Application\Export\CategoryDataPreparator;
use OxidEsales\PersonalizationModule\Application\Export\CategoryRepository;
use OxidEsales\PersonalizationModule\Application\Export\Cli\CliErrorDisplayer;
use OxidEsales\PersonalizationModule\Application\Export\Exporter;
use OxidEsales\PersonalizationModule\Application\Export\Helper\SqlGenerator;
use OxidEsales\PersonalizationModule\Application\Export\Filter\ParentProductsFilter;
use OxidEsales\PersonalizationModule\Application\Export\ProductRepository;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\ActiveControllerCategoryPathBuilder;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\CategoryPathBuilder;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\SearchDataProvider;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\UserActionIdentifier;
use OxidEsales\PersonalizationModule\Application\Tracking\Modifiers\OrderStepsMapModifier;
use OxidEsales\PersonalizationModule\Application\Tracking\Modifiers\PageMapModifier;
use OxidEsales\PersonalizationModule\Application\Tracking\Modifiers\EntityModifierByCurrentAction;
use OxidEsales\PersonalizationModule\Application\Tracking\Modifiers\EntityModifierByCurrentBasketAction;
use OxidEsales\PersonalizationModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\PersonalizationModule\Application\Tracking\Page\PageMap;
use OxidEsales\PersonalizationModule\Application\Tracking\ProductPreparation\ProductDataPreparator;
use OxidEsales\PersonalizationModule\Application\Tracking\ProductPreparation\ProductTitlePreparator;
use OxidEsales\PersonalizationModule\Application\Tracking\ActivePageEntityPreparator;
use OxidEsales\PersonalizationModule\Component\ErrorDisplayerInterface;
use OxidEsales\PersonalizationModule\Component\Export\ColumnNameVariationsGenerator;
use OxidEsales\PersonalizationModule\Component\Export\CsvWriter;
use OxidEsales\PersonalizationModule\Component\Export\ExportFilePathProvider;
use OxidEsales\PersonalizationModule\Component\Tracking\ActivePageEntity;
use OxidEsales\PersonalizationModule\Component\Tracking\ActivePageEntityInterface;
use OxidEsales\PersonalizationModule\Component\Tracking\File\EmosFileData;
use OxidEsales\PersonalizationModule\Component\Tracking\File\TagManagerFileData;
use OxidEsales\PersonalizationModule\Component\Tracking\TrackingCodeGenerator;
use OxidEsales\PersonalizationModule\Component\File\FileSystem;
use OxidEsales\PersonalizationModule\Component\File\JsFileLocator;
use OxidEsales\PersonalizationModule\Component\File\JsFileUploadFactory;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use Smarty;
use OxidEsales\PersonalizationModule\Application\Export\ProductDataPreparator as ProductDataPreparatorForExport;

/**
 * Class responsible for building objects.
 */
class Factory
{
    /**
     * @return JsFileLocator
     */
    public function makeEmosJsFileLocator()
    {
        return new JsFileLocator(Registry::getConfig()->getOutDir(), EmosFileData::TRACKING_CODE_FILE_NAME, Registry::getConfig()->getOutUrl());
    }

    /**
     * @return JsFileLocator
     */
    public function makeTagManagerJsFileLocator()
    {
        return new JsFileLocator(Registry::getConfig()->getOutDir(), TagManagerFileData::TRACKING_CODE_FILE_NAME, Registry::getConfig()->getOutUrl());
    }

    /**
     * @return FileSystem
     */
    public function makeFileSystem()
    {
        return new FileSystem(new \Symfony\Component\Filesystem\Filesystem());
    }

    /**
     * @return \FileUpload\FileUpload
     */
    public function makeEmosJsFileUploader()
    {
        $fileLocator = $this->makeEmosJsFileLocator();
        $jsFileUploadFactory = new JsFileUploadFactory(
            $fileLocator->getJsDirectoryLocation(),
            $fileLocator->getFileName()
        );

        return $jsFileUploadFactory->makeFileUploader();
    }

    /**
     * @return \FileUpload\FileUpload
     */
    public function makeTagManagerFileUploader()
    {
        $fileLocator = $this->makeTagManagerJsFileLocator();
        $jsFileUploadFactory = new JsFileUploadFactory(
            $fileLocator->getJsDirectoryLocation(),
            $fileLocator->getFileName()
        );

        return $jsFileUploadFactory->makeFileUploader();
    }

    /**
     * @param ActivePageEntityInterface $activePageEntity
     *
     * @return TrackingCodeGenerator
     */
    public function makeTrackingCodeGenerator(ActivePageEntityInterface $activePageEntity)
    {
        return new TrackingCodeGenerator(
            $activePageEntity,
            $this->makeEmosJsFileLocator()->getJsFileUrl()
        );
    }

    /**
     * @return UserActionIdentifier
     */
    public function makeUserActionIdentifier()
    {
        return oxNew(
            UserActionIdentifier::class,
            oxNew(User::class),
            oxNew(PageIdentifiers::class)
        );
    }

    /**
     * @param Smarty $templateEngine
     *
     * @return ActivePageEntityPreparator
     */
    public function makeActivePageEntityPreparator($templateEngine)
    {
        $activeUser = oxNew(User::class);
        $activeUser->loadActiveUser();
        $categoryPathBuilder = oxNew(CategoryPathBuilder::class);
        $pageIdentifiers = oxNew(PageIdentifiers::class);
        $productTitlePreparator = oxNew(ProductTitlePreparator::class);
        /** @var PageMapModifier $pageMapModifier */
        $pageMapModifier = oxNew(
            PageMapModifier::class,
            $categoryPathBuilder,
            $productTitlePreparator,
            oxNew(ActiveControllerCategoryPathBuilder::class),
            $pageIdentifiers,
            oxNew(PageMap::class)
        );
        $productDataPreparator = oxNew(ProductDataPreparator::class, $productTitlePreparator);
        /** @var EntityModifierByCurrentAction $emosModifier */
        $trackingCodeGeneratorModifier = oxNew(
            EntityModifierByCurrentAction::class,
            $categoryPathBuilder,
            $productDataPreparator,
            oxNew(ProductTitlePreparator::class),
            oxNew(PageIdentifiers::class),
            oxNew(ActiveUserDataProvider::class),
            oxNew(SearchDataProvider::class, $templateEngine)
        );
        $orderStepsMapModifier = oxNew(
            OrderStepsMapModifier::class,
            oxNew(PageMap::class),
            oxNew(PageIdentifiers::class)
        );
        $trackingCodeGeneratorModifierForBasketEvents = oxNew(
            EntityModifierByCurrentBasketAction::class,
            $categoryPathBuilder,
            $productDataPreparator
        );
        $trackingCodePreparator = oxNew(
            ActivePageEntityPreparator::class,
            oxNew(ActivePageEntity::class),
            $pageIdentifiers,
            $activeUser,
            $pageMapModifier,
            $trackingCodeGeneratorModifier,
            $orderStepsMapModifier,
            $trackingCodeGeneratorModifierForBasketEvents,
            oxNew(ActiveUserDataProvider::class)
        );

        return $trackingCodePreparator;
    }

    /**
     * @return ProductRepository
     */
    public function makeProductRepositoryForExport()
    {
        return oxNew(ProductRepository::class, oxNew(SqlGenerator::class));
    }

    /**
     * @return ParentProductsFilter
     */
    public function makeParentProductsFilterForExport()
    {
        return oxNew(ParentProductsFilter::class);
    }

    /**
     * @return ProductDataPreparatorForExport
     */
    public function makeProductDataPreparatorForExport()
    {
        return oxNew(
            ProductDataPreparatorForExport::class,
            $this->makeProductRepositoryForExport(),
            $this->makeColumnNameVariationsGeneratorForExport()
        );
    }

    /**
     * @return CategoryDataPreparator
     */
    public function makeCategoryDataPreparatorForExport()
    {
        return oxNew(
            CategoryDataPreparator::class,
            oxNew(CategoryRepository::class),
            $this->makeColumnNameVariationsGeneratorForExport()
        );
    }

    /**
     * @return HttpErrorsDisplayer
     */
    public function makeHttpErrorDisplayer()
    {
        return oxNew(HttpErrorsDisplayer::class);
    }

    /**
     * @return CliErrorDisplayer
     */
    public function makeCliErrorDisplayer()
    {
        return oxNew(CliErrorDisplayer::class);
    }

    /**
     * @return Exporter
     */
    public function makeExporter()
    {
        return oxNew(Exporter::class, $this);
    }

    /**
     * @return ExportFilePathProvider
     */
    public function makeExportFilePathProvider()
    {
        return new ExportFilePathProvider(Registry::getConfig()->getConfigParam('sShopDir'));
    }

    /**
     * @return CsvWriter
     */
    public function makeCsvWriterForExport()
    {
        return new CsvWriter();
    }

    /**
     * @return ColumnNameVariationsGenerator
     */
    public function makeColumnNameVariationsGeneratorForExport()
    {
        return new ColumnNameVariationsGenerator(count(Registry::getLang()->getLanguageArray(null, true, true)));
    }
}
