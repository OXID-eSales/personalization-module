<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use OxidEsales\PersonalizationModule\Component\Export\ExportFilePathProvider;
use PHPUnit\Framework\TestCase;

class ExportFilePathProviderTest extends TestCase
{
    public function makeExportDirectoryPathProvider()
    {
        return [
            ['/test_root_dir/', '/path/'],
            ['/test_root_dir', 'path'],
        ];
    }

    /**
     * @param string $rootDirectoryPath
     * @param string $relativeDirectoryPath
     * @dataProvider makeExportDirectoryPathProvider
     */
    public function testMakeDirectoryPath(string $rootDirectoryPath, string $relativeDirectoryPath)
    {
        $provider = new ExportFilePathProvider($rootDirectoryPath);
        $this->assertSame(
            '/test_root_dir/path/',
            $provider->makeDirectoryPath($relativeDirectoryPath)
        );
    }

    public function testMakeProductsFilePath()
    {
        $rootDirectoryPath = '/test_root_dir/';
        $relativeDirectoryPath = '/path/';
        $provider = new ExportFilePathProvider($rootDirectoryPath);
        $this->assertSame(
            '/test_root_dir/path/' . ExportFilePathProvider::PRODUCTS_FILE_NAME,
            $provider->makeProductsFilePath($relativeDirectoryPath)
        );
    }

    public function testMakeCategoriesFilePath()
    {
        $rootDirectoryPath = '/test_root_dir/';
        $relativeDirectoryPath = '/path/';
        $provider = new ExportFilePathProvider($rootDirectoryPath);
        $this->assertSame(
            '/test_root_dir/path/' . ExportFilePathProvider::CATEGORY_FILE_PATH,
            $provider->makeCategoriesFilePath($relativeDirectoryPath)
        );
    }
}
