<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Tracking;

use OxidEsales\EcondaModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\EntityModifierByCurrentAction;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\OrderStepsMapModifier;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\PageMapModifier;
use OxidEsales\EcondaModule\Application\Tracking\Modifiers\EntityModifierByCurrentBasketAction;
use OxidEsales\EcondaModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\EcondaModule\Component\Tracking\ActivePageEntityInterface;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Str;
use Smarty;

/**
 * This class is responsible for preparing entity for later use in Econda tracking component.
 */
class ActivePageEntityPreparator extends \OxidEsales\Eshop\Core\Base
{
    /**
     * @var ActivePageEntityInterface
     */
    private $activePageEntity;

    /**
     * @var PageIdentifiers
     */
    private $pageIdentifiers;

    /**
     * @var User
     */
    private $user;

    /**
     * @var PageMapModifier
     */
    private $pageMapModifier;

    /**
     * @var EntityModifierByCurrentAction
     */
    private $entityModifierByCurrentAction;

    /**
     * @var OrderStepsMapModifier
     */
    private $orderStepsMapModifier;

    /**
     * @var EntityModifierByCurrentBasketAction
     */
    private $entityModifierByCurrentBasketAction;

    /**
     * @var ActiveUserDataProvider
     */
    private $activeUserDataProvider;

    /**
     * @param ActivePageEntityInterface           $activePageEntity
     * @param PageIdentifiers                     $pageIdentifiers
     * @param User                                $activeUser
     * @param PageMapModifier                     $pageMapModifier
     * @param EntityModifierByCurrentAction       $entityModifierByCurrentAction
     * @param OrderStepsMapModifier               $orderStepsMapModifier
     * @param EntityModifierByCurrentBasketAction $trackingCodeGeneratorModifierForBasketEvents
     * @param ActiveUserDataProvider              $activeUserDataProvider
     */
    public function __construct(
        ActivePageEntityInterface $activePageEntity,
        $pageIdentifiers,
        $activeUser,
        $pageMapModifier,
        $entityModifierByCurrentAction,
        $orderStepsMapModifier,
        $trackingCodeGeneratorModifierForBasketEvents,
        $activeUserDataProvider
    ) {
        $this->activePageEntity = $activePageEntity;
        $this->pageIdentifiers = $pageIdentifiers;
        $this->user = $activeUser;
        $this->pageMapModifier = $pageMapModifier;
        $this->entityModifierByCurrentAction = $entityModifierByCurrentAction;
        $this->orderStepsMapModifier = $orderStepsMapModifier;
        $this->entityModifierByCurrentBasketAction = $trackingCodeGeneratorModifierForBasketEvents;
        $this->activeUserDataProvider = $activeUserDataProvider;

        parent::__construct();
    }

    /**
     * @param array  $parameters Plugin parameters.
     * @param smarty $smarty     Template engine object.
     *
     * @return ActivePageEntityInterface
     */
    public function prepareEntity($parameters, $smarty)
    {
        $this->activePageEntity->setPageid($this->getPageIdentifiers()->getPageId());
        $this->activePageEntity->setLangid(Registry::getLang()->getBaseLanguage());
        $domain = str_ireplace('www.', '', parse_url($this->getConfig()->getShopUrl(), PHP_URL_HOST));
        $this->activePageEntity->setSiteid($domain);

        $this->setControllerInfo($parameters, $smarty);

        $this->setLoginsTracking();

        $this->activePageEntity = $this->getEntityModifierByCurrentBasketAction()->modifyEntity($this->activePageEntity);

        return $this->activePageEntity;
    }

    /**
     * @param array  $parameters
     * @param Smarty $smarty
     */
    private function setControllerInfo($parameters, $smarty)
    {
        $pageMap = $this->getPageMapModifer()->modifyPagesMap($parameters);
        $this->activePageEntity = $this->getEntityModifierByCurrentAction()->modifyEntity(
            $parameters,
            $smarty,
            $this->getActiveUser(),
            $this->activePageEntity
        );

        $controllerName = $this->getPageIdentifiers()->getCurrentControllerName();
        if (is_string($controllerName) && array_key_exists($controllerName, $pageMap)) {
            $this->activePageEntity->setContent($pageMap[$controllerName]);
        } else {
            $this->activePageEntity->setContent('Content/' . Str::getStr()->preg_replace('/\.tpl$/', '', $this->getPageIdentifiers()->getCurrentTemplateName()));
        }

        $orderStepsMap = $this->getOrderStepsMapModifier()->modifyOrderStepsMap();
        if (is_string($controllerName) && array_key_exists($controllerName, $orderStepsMap)) {
            $this->activePageEntity->setOrderProcess($orderStepsMap[$controllerName]);
        }
    }

    /**
     * Sets login event for tracking.
     */
    private function setLoginsTracking()
    {
        $currentView = Registry::getConfig()->getActiveView();
        $functionName = $currentView->getFncName();
        if ('login_noredirect' == $functionName) {
            $this->activePageEntity->setLoginUserId($this->activeUserDataProvider->getActiveUserHashedId());
            $this->activePageEntity->setLoginResult($this->activeUserDataProvider->isLoaded() ? '0' : '1');
        }
    }

    /**
     * @return bool|User
     */
    private function getActiveUser()
    {
        return $this->user;
    }

    /**
     * @return PageIdentifiers
     */
    private function getPageIdentifiers()
    {
        return $this->pageIdentifiers;
    }

    /**
     * @return PageMapModifier
     */
    private function getPageMapModifer()
    {
        return $this->pageMapModifier;
    }

    /**
     * @return EntityModifierByCurrentAction
     */
    private function getEntityModifierByCurrentAction()
    {
        return $this->entityModifierByCurrentAction;
    }

    /**
     * @return OrderStepsMapModifier
     */
    private function getOrderStepsMapModifier()
    {
        return $this->orderStepsMapModifier;
    }

    /**
     * @return EntityModifierByCurrentBasketAction
     */
    private function getEntityModifierByCurrentBasketAction()
    {
        return $this->entityModifierByCurrentBasketAction;
    }
}
