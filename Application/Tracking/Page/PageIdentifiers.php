<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Tracking\Page;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class responsible for returning identifiers from request.
 */
class PageIdentifiers
{
    /**
     * Returns purpose of this page (current view name)
     *
     * @return string
     */
    public function getCurrentControllerName()
    {
        $activeController = Registry::getConfig()->getActiveView();
        $className = $activeController->getClassKey();

        return $className ? strtolower($className) : 'start';
    }

    /**
     * Returns current controller template name.
     *
     * @return string
     */
    public function getCurrentTemplateName()
    {
        if (!($currentTemplate = basename((string) Registry::getRequest()->getRequestEscapedParameter('tpl')))) {
            // in case template was not defined in request
            $currentTemplate = Registry::getConfig()->getActiveView()->getTemplateName();
        }

        return $currentTemplate;
    }

    /**
     * @return string
     */
    public function getPageId()
    {
        $sPageId = Registry::getConfig()->getShopId() .
            $this->getCurrentControllerName() .
            $this->getCurrentTemplateName() .
            Registry::getRequest()->getRequestEscapedParameter('cnid') .
            Registry::getRequest()->getRequestEscapedParameter('anid') .
            Registry::getRequest()->getRequestEscapedParameter('option');

        return md5($sPageId);
    }
}
