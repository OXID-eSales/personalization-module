<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use org\bovigo\vfs\vfsStream;
use OxidEsales\PersonalizationModule\Component\File\JsFileLocator;

class JsFileLocatorTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetFileName()
    {
        $locator = new JsFileLocator('', 'file_name', '', 1);

        $this->assertSame('file_name', $locator->getFileName());
    }

    public function testGetDirectoryName()
    {
        $locator = new JsFileLocator('root_path', '', '', 1);
        $this->assertSame(JsFileLocator::TRACKING_CODE_DIRECTORY_NAME, $locator->getDirectoryName());
    }

    public function testGetJsDirectoryLocationWhenMainShop()
    {
        $locator = new JsFileLocator('root_path', '', '', 1);
        $this->assertSame('root_path/'.JsFileLocator::TRACKING_CODE_DIRECTORY_NAME, $locator->getJsDirectoryLocation());
    }

    public function testGetJsDirectoryLocationWhenSubShop()
    {
        $locator = new JsFileLocator('root_path', '', '', 2);
        $this->assertSame('root_path/'.JsFileLocator::TRACKING_CODE_DIRECTORY_NAME.'/2', $locator->getJsDirectoryLocation());
    }

    public function testGetJsFileLocation()
    {
        $locator = new JsFileLocator('root_path', 'file_name', '', 1);
        $expectedLocation = 'root_path'
            . '/' . JsFileLocator::TRACKING_CODE_DIRECTORY_NAME
            . '/file_name';

        $this->assertSame($expectedLocation, $locator->getJsFileLocation());
    }

    public function testGetJsFileUrlWhenMainShop()
    {
        $vfs = $this->getVfsStreamWrapper();
        $vfs->createFile('root_path/oepersonalization/file_name');

        $locator = new JsFileLocator('vfs://root_path', 'file_name', 'oxideshop.local/out', 1);
        $expectedUrl = 'oxideshop.local/out'
            . '/' . JsFileLocator::TRACKING_CODE_DIRECTORY_NAME
            . '/file_name?%d';

        $this->assertStringMatchesFormat($expectedUrl, $locator->getJsFileUrl());
    }

    public function testGetJsFileUrlWhenSubShop()
    {
        $vfs = $this->getVfsStreamWrapper();
        $vfs->createFile('root_path/oepersonalization/2/file_name');

        $locator = new JsFileLocator('vfs://root_path', 'file_name', 'oxideshop.local/out', 2);
        $expectedUrl = 'oxideshop.local/out'
            . '/' . JsFileLocator::TRACKING_CODE_DIRECTORY_NAME
            . '/2'
            . '/file_name?%d';

        $this->assertStringMatchesFormat($expectedUrl, $locator->getJsFileUrl());
    }

    public function testGetJsFileUrlWhenFileNotExists()
    {
        $locator = new JsFileLocator('vfs://root_path', 'file_name', 'oxideshop.local/out', 1);
        $expectedUrl = 'oxideshop.local/out'
                       . '/' . JsFileLocator::TRACKING_CODE_DIRECTORY_NAME
                       . '/file_name';

        $this->assertSame($expectedUrl, $locator->getJsFileUrl());
    }
}
