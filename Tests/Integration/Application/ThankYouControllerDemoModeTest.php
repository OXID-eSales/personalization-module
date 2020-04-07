<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\PersonalizationModule\Component\DemoAccountData;
use OxidEsales\Eshop\Application\Controller\ThankYouController;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Registry;

class ThankYouControllerDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOePersonalizationUseDemoAccount', '1');
    }

    public function testOePersonalizationGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('testId');
        $articleDetails = oxNew(ThankYouController::class);
        $articleDetails->setActiveCategory($category);

        $this->assertEquals(DemoAccountData::getCategoryId(), $articleDetails->oePersonalizationGetCategoryId());
    }
}
