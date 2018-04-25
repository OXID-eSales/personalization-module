<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use OxidEsales\EcondaModule\Component\DemoAccountData;
use OxidEsales\Eshop\Application\Controller\ThankYouController;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Registry;

class ThankYouControllerDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOeEcondaUseDemoAccount', '1');
    }

    public function testOeEcondaGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('testId');
        $articleDetails = oxNew(ThankYouController::class);
        $articleDetails->setActiveCategory($category);

        $this->assertEquals(DemoAccountData::getCategoryId(), $articleDetails->oeEcondaGetCategoryId());
    }
}
