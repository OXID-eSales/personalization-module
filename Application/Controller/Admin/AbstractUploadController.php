<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\PersonalizationModule\Application\Factory;

/**
 * Class for basic upload functionality.
 */
abstract class AbstractUploadController extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    /**
     * @param string $errorMessage
     */
    protected function addErrorToDisplay($errorMessage)
    {
        $exception = oxNew(\OxidEsales\Eshop\Core\Exception\StandardException::class, $errorMessage);
        $utilsView = \OxidEsales\Eshop\Core\Registry::getUtilsView();
        $utilsView->addErrorToDisplay($exception);
    }
}
