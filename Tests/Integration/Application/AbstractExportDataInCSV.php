<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Facts\Facts;

abstract class AbstractExportDataInCSV extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected $exportPath = 'export/oepersonalization';

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
                    'ID|SKU|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952||"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952|1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||%shopUrl%Geschenke/Popcornschale-PINK.html|%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|11|0|0|7|||8a142c3e4143562a5.46426637'
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
                    'ID|SKU|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory'
                ]
            ],
            'all categories and all variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|SKU|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1849|1849|"Bar Butler 6 BOTTLES"|"Bar Butler 6 BOTTLES"|||%shopUrl%Geschenke/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%en/Gifts/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|89.9|94|0|6|||8a142c3e49b5a80c1.23676990',
                    '1952||"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952|1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||%shopUrl%Geschenke/Popcornschale-PINK.html|%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|11|0|0|7|||8a142c3e4143562a5.46426637'
                ]
            ],
            'minimum stock' => [
                'categories' => [],
                'minStock' => 8,
                'exportMainVars' => true,
                'exportVars' => true,
                [
                    'ID|SKU|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952||"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '1952|1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                ]
            ],
            'no main variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => false,
                'exportVars' => true,
                [
                    'ID|SKU|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1952|1952_variant_1|"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER-1952-variant-1.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER-1952-variant-1.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                ]
            ],
            'no variants' => [
                'categories' => [],
                'minStock' => 1,
                'exportMainVars' => true,
                'exportVars' => false,
                [
                    'ID|SKU|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
                    '1849|1849|"Bar Butler 6 BOTTLES"|"Bar Butler 6 BOTTLES"|||%shopUrl%Geschenke/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%en/Gifts/Bar-Equipment/Bar-Butler-6-BOTTLES.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|89.9|94|0|6|||8a142c3e49b5a80c1.23676990',
                    '1952||"Hangover Pack LITTLE HELPER"|"Hangover Set LITTLE HELPER"|||%shopUrl%Geschenke/Hangover-Pack-LITTLE-HELPER.html|%shopUrl%en/Gifts/Hangover-Set-LITTLE-HELPER.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|6|0|0|22|||8a142c3e4143562a5.46426637',
                    '2024|2024|"Popcornschale PINK"|"Popcorn Bowl PINK"|||%shopUrl%Geschenke/Popcornschale-PINK.html|%shopUrl%en/Gifts/Popcorn-Bowl-PINK.html|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|11|0|0|7|||8a142c3e4143562a5.46426637'
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
        $this->prepareSubShop();

        $this->setParametersForExport(
            true,
            true,
            [],
            1,
            $this->exportPath,
            2
        );


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
            'ID|SKU|Name|Name_var1|Description|Description_var1|ProductUrl|ProductUrl_var1|ImageUrl|Price|OldPrice|New|Stock|EAN|Brand|ProductCategory',
            '8888|8888|"Bar Butler 6 BOTTLES"|"Bar Butler 6 BOTTLES"|||%shopUrl%Geschenke/Bar-Butler-6-BOTTLES.html?shp=2|%shopUrl%en/Gifts/Bar-Butler-6-BOTTLES.html?shp=2|%shopUrl%out/pictures/generated/product/1/540_340_75/nopic.jpg|89.9|94|0|6|||subshopcategoryid'
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
        $this->switchToShop(2);
        $this->activateModule('oepersonalization');
        $this->getConfig()->saveShopConfVar('arr','aCurrencies', [
            'EUR@ 1.00@ ,@ .@ €@ 2',
            'GBP@ 0.8565@ .@  @ £@ 2',
            'CHF@ 1.4326@ ,@ .@ <small>CHF</small>@ 2',
            'USD@ 1.2994@ .@  @ $@ 2',
        ], 2);
        $this->getConfig()->saveShopConfVar('arr','aDetailImageSizes', [
            'oxpic1' => '540*340',
            'oxpic2' => '540*340',
            'oxpic3' => '540*340',
            'oxpic4' => '540*340',
            'oxpic5' => '540*340',
            'oxpic6' => '540*340',
            'oxpic7' => '540*340',
            'oxpic8' => '540*340',
            'oxpic9' => '540*340',
            'oxpic10' => '540*340',
            'oxpic11' => '540*340',
            'oxpic12' => '540*340',
        ], 2);
        $this->getConfig()->saveShopConfVar('string','sDefaultImageQuality', '75', 2);
        $this->getConfig()->saveShopConfVar('bool', 'bl_perfLoadPrice', true, 2);
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

    /**
     * Switch shop in session.
     *
     * @param int $shopId
     *
     * @return int
     */
    public function switchToShop($shopId)
    {
        $_POST['shp'] = $shopId;
        $_POST['actshop'] = $shopId;
        $keepThese = [\OxidEsales\Eshop\Core\ConfigFile::class];
        $registryKeys = Registry::getKeys();
        foreach ($registryKeys as $key) {
            if (in_array($key, $keepThese)) {
                continue;
            }
            Registry::set($key, null);
        }
        $utilsObject = new \OxidEsales\Eshop\Core\UtilsObject;
        $utilsObject->resetInstanceCache();
        Registry::set(\OxidEsales\Eshop\Core\UtilsObject::class, $utilsObject);
        \OxidEsales\Eshop\Core\Module\ModuleVariablesLocator::resetModuleVariables();
        Registry::getSession()->setVariable('shp', $shopId);
        Registry::set(\OxidEsales\Eshop\Core\Config::class, null);
        Registry::getConfig()->setConfig(null);
        Registry::set(\OxidEsales\Eshop\Core\Config::class, null);
        $moduleVariablesCache = new \OxidEsales\Eshop\Core\FileCache();
        $shopIdCalculator = new \OxidEsales\Eshop\Core\ShopIdCalculator($moduleVariablesCache);

        return  $shopIdCalculator->getShopId();
    }

    public function activateModule($moduleId)
    {
        $modulesDirectory = Registry::getConfig()->getModulesDir();
        $moduleList = oxNew(\OxidEsales\Eshop\Core\Module\ModuleList::class);
        $modules    = $moduleList->getModulesFromDir($modulesDirectory);
        foreach ($modules as $moduleName => $module) {
            if ($moduleName == $moduleId) {
                $moduleCache     = oxNew(\OxidEsales\Eshop\Core\Module\ModuleCache::class, $module);
                $moduleInstaller = oxNew(\OxidEsales\Eshop\Core\Module\ModuleInstaller::class, $moduleCache);
                $moduleInstaller->deactivate($module);
                $moduleInstaller->activate($module);
            }
        }
    }

    /**
     * Prepare the structure and return the shop url
     *
     * @return string
     */
    protected function prepareShopUrlForExport()
    {
        return Registry::getConfig()->getConfigParam('sShopURL');
    }
    /**
     * Prepare the structure and return the shop dir
     *
     * @return string
     */
    protected function prepareShopStructureForExport()
    {
        return Registry::getConfig()->getConfigParam('sShopDir');
    }
}
