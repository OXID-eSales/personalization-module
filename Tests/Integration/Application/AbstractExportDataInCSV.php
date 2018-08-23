<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Facts\Facts;

abstract class AbstractExportDataInCSV extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected $exportPath = 'export/oepersonalization';

    /**
     * Prepare the structure and return the shop dir
     *
     * @return string
     */
    abstract protected function prepareShopStructureForExport();

    /**
     * @param bool $exportParentProducts
     * @param bool $exportVars
     * @param array $categories
     * @param int $minStock
     * @param string $exportPath
     * @param int $shopId
     * @return
     */
    abstract protected function setParametersForExport($exportParentProducts, $exportVars, $categories, $minStock, $exportPath, $shopId);

    /**
     * Prepare the structure and return the shop url
     *
     * @return string
     */
    abstract protected function prepareShopUrlForExport();

    /**
     * Executes export functionality.
     */
    abstract protected function runExport();

    /**
     * @dataProvider productExportContentProvider
     */
    public function testProductExportContent($categories, $minStock, $exportMainVars, $exportVars, $expectedContent)
    {
        $this->setParametersForExport($exportMainVars, $exportVars, $categories, $minStock, $this->exportPath, 1);

        $shopUrl = $this->prepareShopUrlForExport();

        $lines = $this->prepareAndExecuteProductExport();

        array_walk($expectedContent, function(&$item) use ($shopUrl) {
            $item = str_replace('%shopUrl%', $shopUrl, $item);
        });

        $this->assertEquals($expectedContent, $lines);
    }

    public function productExportContentProvider()
    {
        return [
            'categories' => [
                'categories' => [
                    '8a142c3e4143562a5.46426637'
                ],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||%shopUrl%Geschenke/Popcornschale-PINK.html|%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
            'non-existent category' => [
                'categories' => [
                    'non_existent'
                ],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory'
                ]
            ],
            'all categories and all variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1849|"Bar Butler 6 BOTTLES"|"Bar Butler 6 BOTTLES"|||%shopUrl%Geschenke/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%en/Gifts/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|89.9|94|0|6|||8a142c3e49b5a80c1.23676990',
                    '1952|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||%shopUrl%Geschenke/Popcornschale-PINK.html|%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
            'minimum stock' => [
                'categories' => [],
                'minStock' => 8,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                ]
            ],
            'no main variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => false,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                ]
            ],
            'no variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => false,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1849|"Bar Butler 6 BOTTLES"|"Bar Butler 6 BOTTLES"|||%shopUrl%Geschenke/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%en/Gifts/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|89.9|94|0|6|||8a142c3e49b5a80c1.23676990',
                    '1952|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||%shopUrl%Geschenke/Popcornschale-PINK.html|%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
        ];
    }

    public function testCategoryExportWithSelectedCategories()
    {
        $this->setParametersForExport(
            true,
            true,
            [
                '8a142c3e4143562a5.46426637',
                '8a142c3e49b5a80c1.23676990',
                '8a142c3e4d3253c95.46563530',
            ],
            1,
            $this->exportPath,
            1
        );

        $lines = $this->prepareAndExecuteCategoriesExport();

        $expectedContent = [
            'ID|ParentId|Name|Name_var1',
            '8a142c3e4143562a5.46426637|ROOT|Geschenke|Gifts',
            '8a142c3e49b5a80c1.23676990|8a142c3e4143562a5.46426637|Bar-Equipment|"Bar Equipment"',
            '8a142c3e4d3253c95.46563530|8a142c3e4143562a5.46426637|Fantasy|Fantasy',
        ];

        $this->assertEquals($expectedContent, $lines);
    }

    public function testCategoriesExportWithAllCategories()
    {
        $this->setParametersForExport(
            true,
            true,
            [],
            1,
            $this->exportPath,
            1
        );

        $lines = $this->prepareAndExecuteCategoriesExport();

        $expectedContent = [
            'ID|ParentId|Name|Name_var1',
            '8a142c3e4143562a5.46426637|ROOT|Geschenke|Gifts',
            '8a142c3e49b5a80c1.23676990|8a142c3e4143562a5.46426637|Bar-Equipment|"Bar Equipment"',
            '8a142c3e4d3253c95.46563530|8a142c3e4143562a5.46426637|Fantasy|Fantasy',
            '8a142c3e44ea4e714.31136811|8a142c3e4143562a5.46426637|Wohnen|Living',
            '8a142c3e60a535f16.78077188|8a142c3e44ea4e714.31136811|Uhren|Clocks'
        ];

        $this->assertEquals($expectedContent, $lines);
    }

    public function testCategoriesExportWithSubshop()
    {
        if (!$this->isEnterpriseEdition()) {
            return;
        }

        $this->setParametersForExport(
            true,
            true,
            [],
            1,
            $this->exportPath,
            2
        );

        $this->prepareSubShop();

        $lines = $this->prepareAndExecuteCategoriesExport();

        $expectedContent = [
            'ID|ParentId|Name|Name_var1',
            'subshopcategoryid|ROOT|Geschenke|Gifts',
        ];

        $this->assertEquals($expectedContent, $lines);
    }

    public function testProductsExportWithSubshop()
    {
        if (!$this->isEnterpriseEdition()) {
            return;
        }

        $this->setParametersForExport(
            true,
            true,
            [],
            1,
            $this->exportPath,
            2
        );

        $this->prepareSubShop();

        $shopUrl = $this->prepareShopUrlForExport();
        $lines = $this->prepareAndExecuteProductExport();
        $expectedContent = [
            'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
            '8888|"Bar Butler 6 BOTTLES"|"Bar Butler 6 BOTTLES"|||%shopUrl%Geschenke/Bar-Butler-6-BOTTLES.html?shp=2|%shopUrl%en/Gifts/Bar-Butler-6-BOTTLES.html?shp=2|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|89.9|94|0|6|||subshopcategoryid'
        ];
        array_walk($expectedContent, function(&$item) use ($shopUrl) {
            $item = str_replace('%shopUrl%', $shopUrl, $item);
        });

        $this->assertEquals($expectedContent, $lines);
    }

    /**
     * @return array
     */
    protected function prepareAndExecuteCategoriesExport(): array
    {
        $shopDir = $this->prepareShopStructureForExport();

        $this->runExport();

        $categoryFilename = $shopDir . $this->exportPath . '/categories.csv';
        $lines = array_map('trim', file($categoryFilename));
        return $lines;
    }

    /**
     * @return array
     */
    private function prepareAndExecuteProductExport(): array
    {
        $shopDir = $this->prepareShopStructureForExport();

        $this->runExport();

        $productFilename = $shopDir . $this->exportPath . '/products.csv';
        $lines = array_map('trim', file($productFilename));
        return $lines;
    }

    private function prepareSubShop()
    {
        $this->getConfig()->setShopId(2);
        $shop = oxNew(Shop::class);
        $shop->load(2);
        $shop->generateViews();
    }

    /**
     * @return bool
     */
    private function isEnterpriseEdition(): bool
    {
        $facts = new Facts;

        return ('EE' === $facts->getEdition());
    }
}
