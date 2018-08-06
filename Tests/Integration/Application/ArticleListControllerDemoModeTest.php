<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use \OxidEsales\Eshop\Application\Controller\ArticleListController;
use OxidEsales\Eshop\Application\Model\Category;
use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\PersonalizationModule\Component\DemoAccountData;

class ArticleListControllerDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOePersonalizationUseDemoAccount', '1');
    }

    public function testGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('categoryId');
        $controller = oxNew(ArticleListController::class);
        $controller->setActiveCategory($category);

        $this->assertEquals(DemoAccountData::getCategoryId(), $controller->oePersonalizationGetCategoryId());
    }
}
