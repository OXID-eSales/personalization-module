<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export\Filter;

/**
 * Class responsible to filter out parent products from given data.
 */
class ParentProductsFilter
{
    /**
     * @param array $products
     *
     * @return array
     */
    public function filterOutParentProducts($products)
    {
        $filteredProductIds = [];
        foreach ($products as $productData) {
            if (!empty($productData['OXPARENTID'])) {
                $filteredProductIds[] = $productData;
            }
        }

        return $filteredProductIds;
    }
}
