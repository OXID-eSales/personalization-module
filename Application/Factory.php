<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application;

use OxidEsales\EcondaModule\Application\Tracking\Helper\ActiveControllerCategoryPathBuilder;
use OxidEsales\EcondaModule\Application\Tracking\Helper\CategoryPathBuilder;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\OrderStepsMapModifier;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\PageMapModifier;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\EntityModifierByCurrentAction;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\EntityModifierByCurrentBasketAction;
use OxidEsales\EcondaModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\EcondaModule\Application\Tracking\Page\PageMap;
use OxidEsales\EcondaModule\Application\Tracking\ProductPreparation\ProductDataPreparator;
use OxidEsales\EcondaModule\Application\Tracking\ProductPreparation\ProductTitlePreparator;
use OxidEsales\EcondaModule\Application\Tracking\ActivePageEntityPreparator;
use OxidEsales\EcondaModule\Component\Tracking\ActivePageEntity;
use OxidEsales\EcondaModule\Component\Tracking\ActivePageEntityInterface;
use OxidEsales\EcondaModule\Component\Tracking\TrackingCodeGenerator;
use OxidEsales\EcondaModule\Component\Tracking\File\FileSystem;
use OxidEsales\EcondaModule\Component\Tracking\File\JsFileLocator;
use OxidEsales\EcondaModule\Component\Tracking\File\JsFileUploadFactory;
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
    public function getJsFileLocator()
    {
        return oxNew(JsFileLocator::class, Registry::getConfig()->getOutDir(), Registry::getConfig()->getOutUrl());
    }

    /**
     * @return FileSystem
     */
    public function getFileSystem()
    {
        return oxNew(FileSystem::class, oxNew(\Symfony\Component\Filesystem\Filesystem::class));
    }

    /**
     * @return \FileUpload\FileUpload
     */
    public function getFileUploader()
    {
        $jsFileUploadFactory = oxNew(
            JsFileUploadFactory::class,
            $this->getJsFileLocator()->getJsDirectoryLocation(),
            $this->getJsFileLocator()->getFileName()
        );

        return $jsFileUploadFactory->getFileUploader();
    }

    /**
     * @param ActivePageEntityInterface $activePageEntity
     * @param array                     $pluginParameters
     * @param Smarty                    $smarty
     *
     * @return TrackingCodeGenerator
     */
    public function getTrackingCodeGenerator(ActivePageEntityInterface $activePageEntity, $pluginParameters, $smarty)
    {
        return oxNew(
            TrackingCodeGenerator::class,
            $activePageEntity,
            $this->getJsFileLocator()->getJsFileUrl(),
            $this->getActivePageEntityPreparator()->prepareEntity($pluginParameters, $smarty)
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
            oxNew(PageIdentifiers::class)
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
            $trackingCodeGeneratorModifierForBasketEvents
        );

        return $trackingCodePreparator;
    }
}
