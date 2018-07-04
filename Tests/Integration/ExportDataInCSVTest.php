<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

abstract class ExportDataInCSVTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected $exportPath = 'export/oeeconda';

    /**
     * Prepare the structure and return the shop dir
     *
     * @return string
     */
    abstract protected function prepareShopStructureForExport();

    /**
     * @param bool   $exportParentProducts
     * @param bool   $exportVars
     * @param array  $categories
     * @param int    $minStock
     * @param string $exportPath
     */
    abstract protected function setParametersForExport($exportParentProducts, $exportVars, $categories, $minStock, $exportPath);

    /**
     * Prepare the structure and return the shop url
     *
     * @return string
     */
    abstract protected function prepareShopUrlForExport();

    /**
     * Run the export command
     */
    abstract protected function runExport();

    /**
     * @dataProvider productExportCountProvider
     */
    public function testProductExportCount($categories, $minStock, $exportMainVars, $exportVars, $expectedCount)
    {
        $this->setParametersForExport($exportMainVars, $exportVars, $categories, $minStock, $this->exportPath);

        $shopDir = $this->prepareShopStructureForExport();

        $this->runExport();

        $productFilename = $shopDir . $this->exportPath . '/products.csv';
        $lines = file($productFilename);

        $this->assertCount($expectedCount, $lines);
    }

    public function productExportCountProvider()
    {
        return [
            'categories' => [
                'categories' => [
                    '8a142c3e4143562a5.46426637'
                ],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                3
            ],
            'non-existent category' => [
                'categories' => [
                    'non_existent'
                ],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                1
            ],
            'all categories' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                4
            ],
            'minimum stock' => [
                'categories' => [],
                'minStock' => 7,
                'exportMainVars' => false,
                'exportVars' => true,
                2
            ],
            'no main variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => false,
                'exportVars' => true,
                3
            ],
            'no variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => false,
                3
            ],
            'with variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                4
            ]
        ];
    }

    /**
     * @dataProvider productExportContentProvider
     */
    public function testProductExportContent($categories, $minStock, $exportMainVars, $exportVars, $expectedContent)
    {
        $this->setParametersForExport($exportMainVars, $exportVars, $categories, $minStock, $this->exportPath);

        $shopDir = $this->prepareShopStructureForExport();

        $shopUrl = $this->prepareShopUrlForExport();

        $this->runExport();

        $productFilename = $shopDir . $this->exportPath . '/products.csv';
        $lines = array_map('trim', file($productFilename));

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
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||"%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||"%shopUrl%Geschenke/Popcornschale-PINK.html"|"%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|11|0|0|7|||8a142c3e4143562a5.46426637'
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
            'all categories' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||"%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html"|"%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||"%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||"%shopUrl%Geschenke/Popcornschale-PINK.html"|"%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
            'minimum stock' => [
                'categories' => [],
                'minStock' => 7,
                'exportMainVars' => false,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||"%shopUrl%Geschenke/Popcornschale-PINK.html"|"%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
            'no main variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => false,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||"%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||"%shopUrl%Geschenke/Popcornschale-PINK.html"|"%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
            'no variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => false,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||"%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html"|"%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||"%shopUrl%Geschenke/Popcornschale-PINK.html"|"%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
            'with variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||"%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html"|"%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||"%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||"%shopUrl%Geschenke/Popcornschale-PINK.html"|"%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html"|"%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg"|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ]
        ];
    }

    public function testCategoryExportCount()
    {
        $this->setParametersForExport(true, true, [], 1, $this->exportPath);

        $shopDir = $this->prepareShopStructureForExport();

        $this->runExport();

        $categoryFilename = $shopDir . $this->exportPath . '/categories.csv';
        $lines = file($categoryFilename);

        $this->assertCount(6, $lines);
    }

    public function testCategoryExportContent()
    {
        $this->setParametersForExport(true, true, [], 1, $this->exportPath);

        $shopDir = $this->prepareShopStructureForExport();

        $this->runExport();

        $categoryFilename = $shopDir . $this->exportPath . '/categories.csv';
        $lines = array_map('trim', file($categoryFilename));

        $expectedContent = [
            'ID|ParentId|Name|Name_var1',
            '8a142c3e4143562a5.46426637|ROOT|"Geschenke"|"Gifts"',
            '8a142c3e49b5a80c1.23676990|8a142c3e4143562a5.46426637|"Bar-Equipment"|"Bar Equipment"',
            '8a142c3e4d3253c95.46563530|8a142c3e4143562a5.46426637|"Fantasy"|"Fantasy"',
            '8a142c3e44ea4e714.31136811|8a142c3e4143562a5.46426637|"Wohnen"|"Living"',
            '8a142c3e60a535f16.78077188|8a142c3e44ea4e714.31136811|"Uhren"|"Clocks"'
        ];

        $this->assertEquals($expectedContent, $lines);
    }
}
