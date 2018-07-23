<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration;

use \OxidEsales\Eshop\Application\Model\Category;
use \OxidEsales\Eshop\Application\Controller\ArticleListController;

class ArticleListControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('categoryId');
        $controller = oxNew(ArticleListController::class);
        $controller->setActiveCategory($category);

        $this->assertEquals('categoryId', $controller->oePersonalizationGetCategoryId());
    }
}
