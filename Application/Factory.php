<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application;

use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\TrackingCodeGenerator;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\TrackingCodeGeneratorInterface;
use OxidEsales\Eshop\Core\ShopIdCalculator;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\HttpErrorsDisplayer;
use OxidEsales\PersonalizationModule\Application\Core\Events;
use OxidEsales\PersonalizationModule\Application\Export\CategoryDataPreparator;
use OxidEsales\PersonalizationModule\Application\Export\CategoryRepository;
use OxidEsales\PersonalizationModule\Application\Export\Exporter;
use OxidEsales\PersonalizationModule\Application\Export\Helper\SqlGenerator;
use OxidEsales\PersonalizationModule\Application\Export\Filter\ParentProductsFilter;
use OxidEsales\PersonalizationModule\Application\Export\ProductRepository;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\ActiveControllerCategoryPathBuilder;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\ActiveUserDataProvider;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\CategoryPathBuilder;
use OxidEsales\EcondaTrackingComponent\Adapter\Helper\SearchDataProvider;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\UserActionIdentifier;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\OrderStepsMapModifier;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\PageMapModifier;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\EntityModifierByCurrentAction;
use OxidEsales\EcondaTrackingComponent\Adapter\Modifiers\EntityModifierByCurrentBasketAction;
use OxidEsales\EcondaTrackingComponent\Adapter\Page\PageIdentifiers;
use OxidEsales\EcondaTrackingComponent\Adapter\Page\PageMap;
use OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation\ProductDataPreparator;
use OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation\ProductTitlePreparator;
use OxidEsales\EcondaTrackingComponent\Adapter\ActivePageEntityPreparator;
use OxidEsales\PersonalizationModule\Component\Export\ColumnNameVariationsGenerator;
use OxidEsales\PersonalizationModule\Component\Export\CsvWriter;
use OxidEsales\PersonalizationModule\Component\Export\ExportFilePathProvider;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\ActivePageEntity;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\ActivePageEntityInterface;
use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\File\EmosFileData;
use OxidEsales\PersonalizationModule\Component\Tracking\File\TagManagerFileData;
use OxidEsales\EcondaTrackingComponent\File\FileSystem;
use OxidEsales\EcondaTrackingComponent\File\JsFileLocator;
use OxidEsales\EcondaTrackingComponent\File\JsFileUploadFactory;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Component\Tracking\TrackingCodeGeneratorDecorator;
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
        return new JsFileLocator(
            Registry::getConfig()->getOutDir(),
            Events::MODULE_ID,
            EmosFileData::TRACKING_CODE_FILE_NAME,
            Registry::getConfig()->getOutUrl(null, null, true),
            ShopIdCalculator::BASE_SHOP_ID
        );
    }

    /**
     * @return JsFileLocator
     */
    public function makeTagManagerJsFileLocator()
    {
        $config = Registry::getConfig();
        return new JsFileLocator(
            $config->getOutDir(),
            Events::MODULE_ID,
            TagManagerFileData::TRACKING_CODE_FILE_NAME,
            $config->getOutUrl(null, null, true),
            $config->getShopId()
        );
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
     * @return TrackingCodeGeneratorInterface
     */
    public function makeTrackingCodeGenerator(ActivePageEntityInterface $activePageEntity)
    {
        $generator = new TrackingCodeGenerator(
            $activePageEntity,
            $this->makeEmosJsFileLocator()->getJsFileUrl()
        );

        return new TrackingCodeGeneratorDecorator($generator);
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
