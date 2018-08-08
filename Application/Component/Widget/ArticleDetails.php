<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Component\Widget;

use OxidEsales\PersonalizationModule\Component\DemoAccountData;

/**
 * @mixin \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails
 */
class ArticleDetails extends ArticleDetails_parent
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

    /**
     * @return string
     */
    public function oePersonalizationGetProductNumber()
    {
        $product = $this->getProduct();
        $productId = (isset($product->oxarticles__oxartnum->value) && $product->oxarticles__oxartnum->value) ? $product->oxarticles__oxartnum->value : $product->getId();
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            $productId = DemoAccountData::getProductId();
        }

        return $productId;
    }
}
