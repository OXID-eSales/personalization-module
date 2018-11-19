<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\PersonalizationModule\Application\Model\Product;
use OxidEsales\TestingLibrary\UnitTestCase;

class ProductTest extends UnitTestCase
{
    public function testHasVariants()
    {
        /** @var Product $product */
        $product = oxNew(Article::class);
        $product->load('1952');

        $this->assertTrue($product->oeEcondaTrackingHasVariants());
    }

    public function testHasNoVariants()
    {
        /** @var Product $product */
        $product = oxNew(Article::class);
        $product->load('1952_variant_1');

        $this->assertFalse($product->oeEcondaTrackingHasVariants());
    }

    public function testGetProductIdForParent()
    {
        /** @var Product $product */
        $product = oxNew(Article::class);
        $product->load('1952');

        $this->assertSame('1952', $product->oeEcondaTrackingGetProductId());
    }

    public function testGetProductIdForVariant()
    {
        /** @var Product $product */
        $product = oxNew(Article::class);
        $product->load('1952_variant_1');

        $this->assertSame('1952', $product->oeEcondaTrackingGetProductId());
    }

    public function testGetSkuForParent()
    {
        /** @var Product $product */
        $product = oxNew(Article::class);
        $product->load('1952');

        $this->assertSame(null, $product->oeEcondaTrackingGetSku());
    }

    public function testGetSkuForVariant()
    {
        /** @var Product $product */
        $product = oxNew(Article::class);
        $product->load('1952_variant_1');

        $this->assertSame('1952_variant_1', $product->oeEcondaTrackingGetSku());
    }

    public function testGetSkuForProductWithoutVariants()
    {
        /** @var Product $product */
        $product = oxNew(Article::class);
        $product->load('1849');

        $this->assertSame('1849', $product->oeEcondaTrackingGetSku());
    }
}
