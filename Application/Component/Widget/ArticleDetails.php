<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Component\Widget;

use OxidEsales\EcondaModule\Component\DemoAccountData;

/**
 * @mixin \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails
 */
class ArticleDetails extends ArticleDetails_parent
{
    public function oeEcondaGetCategoryId()
    {
        $activeCategoryId = $this->getActiveCategory()->getId();
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            $activeCategoryId = DemoAccountData::getCategoryId();
        }

        return $activeCategoryId;
    }

    public function oeEcondaGetProductNumber()
    {
        $product = $this->getProduct();
        $productId = (isset($product->oxarticles__oxartnum->value) && $product->oxarticles__oxartnum->value) ? $product->oxarticles__oxartnum->value : $product->getId();
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            $productId = DemoAccountData::getProductId();
        }

        return $productId;
    }
}
