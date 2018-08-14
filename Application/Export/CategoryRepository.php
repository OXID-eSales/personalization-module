<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export;

use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Application\Model\CategoryList;

/**
 * Class responsible for retrieving category related data for export.
 */
class CategoryRepository
{
    /**
     * @param array $categoriesIds
     *
     * @return Category[]
     */
    public function findCategoriesByIds(array $categoriesIds)
    {
        $list = [];
        foreach ($categoriesIds as $categoryId) {
            $category = oxNew(Category::class);
            if ($category->load($categoryId)) {
                $list[] =  $category;
            }
        }

        return $list;
    }

    /**
     * @return CategoryList
     */
    public function getAllCategories()
    {
        $categories = oxNew(CategoryList::class);
        $categories->loadList();

        return $categories;
    }
}
