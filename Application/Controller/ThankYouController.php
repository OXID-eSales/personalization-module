<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller;

use OxidEsales\PersonalizationModule\Component\DemoAccountData;

/**
 * @mixin \OxidEsales\Eshop\Application\Controller\ThankYouController
 */
class ThankYouController extends ThankYouController_parent
{
    /**
     * @return string
     */
    public function oePersonalizationGetCategoryId()
    {
        $activeCategoryId = $this->getActiveCategory()->getId();
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            $activeCategoryId = DemoAccountData::getCategoryId();
        }

        return $activeCategoryId;
    }
}
