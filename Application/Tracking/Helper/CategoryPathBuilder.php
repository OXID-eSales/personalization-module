<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Tracking\Helper;

/**
 * Class responsible for building category path.
 */
class CategoryPathBuilder
{
    /**
     * Builds basket product category path.
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $product Article to build category id.
     *
     * @return string
     */
    public function getBasketProductCategoryPath($product)
    {
        $categoryPath = '';
        if ($category = $product->getCategory()) {
            $table = $category->getViewName();
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
            $query = "select {$table}.oxtitle as oxtitle from {$table}
                       where {$table}.oxleft <= " . $database->quote($category->oxcategories__oxleft->value) . " and
                             {$table}.oxright >= " . $database->quote($category->oxcategories__oxright->value) . " and
                             {$table}.oxrootid = " . $database->quote($category->oxcategories__oxrootid->value) . "
                       order by {$table}.oxleft";

            $result = $database->select($query);
            if ($result != false && $result->count() > 0) {
                while (!$result->EOF) {
                    if ($categoryPath) {
                        $categoryPath .= '/';
                    }
                    $categoryPath .= strip_tags($result->fields['oxtitle']);
                    $result->fetchRow();
                }
            }
        }

        return $categoryPath;
    }
}
