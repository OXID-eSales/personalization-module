<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\PersonalizationModule\Component\DemoAccountData;
use OxidEsales\Eshop\Application\Component\Widget\ArticleDetails;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Registry;

class ArticleDetailsDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOePersonalizationUseDemoAccount', '1');
    }

    public function testOePersonalizationGetProductNumber()
    {
        $article = oxNew(Article::class);
        $article->setId('__testId');
        $article->save();
        $articleDetails = oxNew(ArticleDetails::class);
        $articleDetails->setViewProduct($article);

        $this->assertEquals(DemoAccountData::getProductId(), $articleDetails->oePersonalizationGetProductNumber());
    }

    public function testOePersonalizationGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('__testId');
        $articleDetails = oxNew(ArticleDetails::class);
        $articleDetails->setActiveCategory($category);

        $this->assertEquals(DemoAccountData::getCategoryId(), $articleDetails->oePersonalizationGetCategoryId());
    }
}
