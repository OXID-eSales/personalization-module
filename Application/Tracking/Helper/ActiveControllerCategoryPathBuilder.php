<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Tracking\Helper;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class responsible for building active controller category path.
 */
class ActiveControllerCategoryPathBuilder
{
    /**
     * @return string
     */
    public function getCategoryPath()
    {
        // #4016: econda: json function returns null if title has an umlaut
        $categoryTitles = [];
        if ($categoryPaths = Registry::getConfig()->getActiveView()->getBreadCrumb()) {
            foreach ($categoryPaths as $categoryPathParts) {
                $categoryTitles[] = $categoryPathParts['title'];
            }
        }
        $categoryPath = (count($categoryTitles) ? strip_tags(implode('/', $categoryTitles)) : 'NULL');

        return $categoryPath;
    }
}
