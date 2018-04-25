<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use OxidEsales\Eshop\Application\Component\Widget\ArticleDetails;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Category;

class ArticleDetailsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testOeEcondaGetProductId()
    {
        $article = oxNew(Article::class);
        $article->setId('__testId');
        $article->save();
        $articleDetails = oxNew(ArticleDetails::class);
        $articleDetails->setViewProduct($article);

        $this->assertEquals('__testId', $articleDetails->oeEcondaGetArticleId());
    }

    public function testOeEcondaGetCategoryId()
    {
        $category = oxNew(Category::class);
        $category->setId('__testId');
        $articleDetails = oxNew(ArticleDetails::class);
        $articleDetails->setActiveCategory($category);

        $this->assertEquals('__testId', $articleDetails->oeEcondaGetCategoryId());
    }
}
