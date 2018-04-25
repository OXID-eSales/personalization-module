<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use OxidEsales\Eshop\Application\Controller\ThankYouController;
use OxidEsales\Eshop\Application\Model\Category;

class ThankYouControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testOeEcondaGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('testId');
        $articleDetails = oxNew(ThankYouController::class);
        $articleDetails->setActiveCategory($category);

        $this->assertEquals('testId', $articleDetails->oeEcondaGetCategoryId());
    }
}
