<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Tracking\Modifiers;

use OxidEsales\PersonalizationModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\PersonalizationModule\Application\Tracking\Page\PageMap;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class responsible for modifying order steps map which later is used as content for Econda.
 */
class OrderStepsMapModifier
{
    /**
     * @var PageIdentifiers
     */
    private $pageIdentifiers;

    /**
     * @var PageMap
     */
    private $pageMap;

    /**
     * @param PageMap         $pageMap
     * @param PageIdentifiers $pageIdentifiers
     */
    public function __construct($pageMap, $pageIdentifiers)
    {
        $this->pageMap = $pageMap;
        $this->pageIdentifiers = $pageIdentifiers;
    }

    /**
     * @return array
     */
    public function modifyOrderStepsMap()
    {
        $orderStepsMap = $this->pageMap->getOrderStepNames();
        $controllerName = $this->pageIdentifiers->getCurrentControllerName();
        if ($controllerName === 'user') {
            $orderStepsMap = $this->modifyByUserController($orderStepsMap);
        }

        return $orderStepsMap;
    }

    /**
     * @param array $orderStepsMap
     *
     * @return array
     */
    protected function modifyByUserController($orderStepsMap)
    {
        $option = Registry::getRequest()->getRequestEscapedParameter('option');
        $option = (isset($option)) ? $option : Registry::getSession()->getVariable('option');

        if (isset($option) && array_key_exists('user_' . $option, $orderStepsMap)) {
            $orderStepsMap['user'] = $orderStepsMap['user_' . $option];
        }

        return $orderStepsMap;
    }
}
