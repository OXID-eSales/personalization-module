<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use OxidEsales\EcondaModule\Component\DemoAccountData;
use OxidEsales\Eshop\Application\Component\Widget\ArticleDetails;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Registry;

class ArticleDetailsDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOeEcondaUseDemoAccount', '1');
    }

    public function testOeEcondaGetProductId()
    {
        $article = oxNew(Article::class);
        $article->setId('__testId');
        $article->save();
        $articleDetails = oxNew(ArticleDetails::class);
        $articleDetails->setViewProduct($article);

        $this->assertEquals(DemoAccountData::getProductId(), $articleDetails->oeEcondaGetArticleId());
    }

    public function testOeEcondaGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('__testId');
        $articleDetails = oxNew(ArticleDetails::class);
        $articleDetails->setActiveCategory($category);

        $this->assertEquals(DemoAccountData::getCategoryId(), $articleDetails->oeEcondaGetCategoryId());
    }
}
