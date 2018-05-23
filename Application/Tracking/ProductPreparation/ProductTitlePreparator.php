<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Tracking\ProductPreparation;

/**
 * Class responsible for preparing product title for sending to Econda.
 */
class ProductTitlePreparator
{
    /**
     * Returns formatted product title.
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $product product which title must be prepared.
     *
     * @return string
     */
    public function prepareProductTitle($product)
    {
        $title = $product->oxarticles__oxtitle->value;
        if ($product->oxarticles__oxvarselect->value) {
            $title .= ' ' . $product->oxarticles__oxvarselect->value;
        }

        return $title;
    }
}
