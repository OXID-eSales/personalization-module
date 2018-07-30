<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application;

use OxidEsales\PersonalizationModule\Application\Tracking\Helper\ActiveControllerCategoryPathBuilder;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\CategoryPathBuilder;
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
use OxidEsales\PersonalizationModule\Component\Tracking\TrackingCodeGenerator;
use OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem;
use OxidEsales\PersonalizationModule\Component\Tracking\File\JsFileLocator;
use OxidEsales\PersonalizationModule\Component\Tracking\File\JsFileUploadFactory;
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
    public function makeJsFileLocator()
    {
        return oxNew(JsFileLocator::class, Registry::getConfig()->getOutDir(), Registry::getConfig()->getOutUrl());
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
    public function makeFileUploader()
    {
        $jsFileUploadFactory = oxNew(
            JsFileUploadFactory::class,
            $this->makeJsFileLocator()->getJsDirectoryLocation(),
            $this->makeJsFileLocator()->getFileName()
        );

        return $jsFileUploadFactory->makeFileUploader();
    }

    /**
     * @param ActivePageEntityInterface $activePageEntity
     * @param array                     $pluginParameters
     * @param Smarty                    $smarty
     *
     * @return TrackingCodeGenerator
     */
    public function makeTrackingCodeGenerator(ActivePageEntityInterface $activePageEntity, $pluginParameters, $smarty)
    {
        return oxNew(
            TrackingCodeGenerator::class,
            $activePageEntity,
            $this->makeJsFileLocator()->getJsFileUrl(),
            $this->getActivePageEntityPreparator()->prepareEntity($pluginParameters, $smarty)
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
     * @return ActivePageEntityPreparator
     */
    public function getActivePageEntityPreparator()
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
            oxNew(ActiveUserDataProvider::class)
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
