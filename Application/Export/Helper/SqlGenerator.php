<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export\Helper;

use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class responsible for generating queries needed to fulfill export functionality.
 */
class SqlGenerator
{
    /**
     * @param array $categorieIds
     * @return string
     */
    public function makeCategoriesQueryPart($categorieIds)
    {
        $categoryIdToConcatenate = '';
        if (!empty($categorieIds)) {
            $categoryIdToConcatenate = " and (";
            $isNotFirstElement = false;
            foreach ($categorieIds as $categoryId) {
                if ($isNotFirstElement) {
                    $categoryIdToConcatenate .= " or ";
                }
                $categoryIdToConcatenate .= "oxobject2category.oxcatnid = " . DatabaseProvider::getDb()->quote($categoryId);
                $isNotFirstElement = true;
            }
            $categoryIdToConcatenate .= ")";
        }

        return $categoryIdToConcatenate;
    }
}
