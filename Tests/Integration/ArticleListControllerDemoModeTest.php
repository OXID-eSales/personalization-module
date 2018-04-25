<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use \OxidEsales\Eshop\Application\Controller\ArticleListController;
use OxidEsales\Eshop\Application\Model\Category;
use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\EcondaModule\Component\DemoAccountData;

class ArticleListControllerDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOeEcondaUseDemoAccount', '1');
    }

    public function testGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('categoryId');
        $controller = oxNew(ArticleListController::class);
        $controller->setActiveCategory($category);

        $this->assertEquals(DemoAccountData::getCategoryId(), $controller->oeEcondaGetCategoryId());
    }
}
