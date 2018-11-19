<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Model;

use OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation\ProductInterface;
use OxidEsales\Eshop\Application\Model\Article;

/**
 * @mixin \OxidEsales\Eshop\Application\Model\Article
 */
class Product extends Product_parent implements ProductInterface
{
    /**
     * Method checks if product has variants.
     *
     * @return bool
     */
    public function oeEcondaTrackingHasVariants()
    {
        $result = false;
        $sId = $this->getId();
        if ($sId) {
            $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
            $sQ = "select oxid from " . $this->getViewName(true) . " where oxparentid = " . $oDb->quote($sId) .
                " and " . $this->getSqlActiveSnippet(true) . " LIMIT 1";
            $oRs = $oDb->select($sQ);
            if ($oRs != false && $oRs->count() > 0) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @return null|string
     */
    public function oeEcondaTrackingGetSku()
    {
        $sku = null;
        if (!$this->oeEcondaTrackingHasVariants()) {
            $sku = (isset($this->oxarticles__oxartnum->value) && $this->oxarticles__oxartnum->value) ? $this->oxarticles__oxartnum->value : $this->getId();
        }

        return $sku;
    }

    /**
     * @return string
     */
    public function oeEcondaTrackingGetProductId()
    {
        /** @var Article $parent */
        $parent = $this->getParentArticle();
        if ($parent) {
            $productId = (isset($parent->oxarticles__oxartnum->value) && $parent->oxarticles__oxartnum->value) ? $parent->oxarticles__oxartnum->value : $parent->getId();
        } else {
            $productId = (isset($this->oxarticles__oxartnum->value) && $this->oxarticles__oxartnum->value) ? $this->oxarticles__oxartnum->value : $this->getId();
        }

        return $productId;
    }
}
