<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application;

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
        return oxNew(JsFileLocator::class, Registry::getConfig()->getOutDir(), EmosFileData::TRACKING_CODE_FILE_NAME, Registry::getConfig()->getOutUrl());
    }

    /**
     * @return JsFileLocator
     */
    public function makeTagManagerJsFileLocator()
    {
        return oxNew(JsFileLocator::class, Registry::getConfig()->getOutDir(), TagManagerFileData::TRACKING_CODE_FILE_NAME, Registry::getConfig()->getOutUrl());
    }

    /**
     * @return FileSystem
     */
    public function makeFileSystem()
    {
        return oxNew(FileSystem::class, oxNew(\Symfony\Component\Filesystem\Filesystem::class));
    }

    /**
     * @return \FileUpload\FileUpload
     */
    public function makeEmosJsFileUploader()
    {
        $fileLocator = $this->makeEmosJsFileLocator();
        $jsFileUploadFactory = oxNew(
            JsFileUploadFactory::class,
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
        $jsFileUploadFactory = oxNew(
            JsFileUploadFactory::class,
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
        return oxNew(
            TrackingCodeGenerator::class,
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
    public function getActivePageEntityPreparator($templateEngine)
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
}
