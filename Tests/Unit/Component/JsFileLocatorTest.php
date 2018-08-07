<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use OxidEsales\PersonalizationModule\Component\File\JsFileLocator;

class JsFileLocatorTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetFileName()
    {
        $locator = new JsFileLocator('', 'file_name', '');

        $this->assertSame('file_name', $locator->getFileName());
    }

    public function testGetDirectoryName()
    {
        $locator = new JsFileLocator('root_path', '', '');
        $this->assertSame(JsFileLocator::TRACKING_CODE_DIRECTORY_NAME, $locator->getDirectoryName());
    }

    public function testGetJsDirectoryLocation()
    {
        $locator = new JsFileLocator('root_path', '', '');
        $this->assertSame('root_path/'.JsFileLocator::TRACKING_CODE_DIRECTORY_NAME, $locator->getJsDirectoryLocation());
    }

    public function testGetJsFileLocation()
    {
        $locator = new JsFileLocator('root_path', 'file_name', '');
        $expectedLocation = 'root_path'
            . '/' . JsFileLocator::TRACKING_CODE_DIRECTORY_NAME
            . '/file_name';

        $this->assertSame($expectedLocation, $locator->getJsFileLocation());
    }

    public function testGetJsFileUrl()
    {
        $locator = new JsFileLocator('root_path', 'file_name', 'oxideshop.local/out');
        $expectedUrl = 'oxideshop.local/out'
            . '/' . JsFileLocator::TRACKING_CODE_DIRECTORY_NAME
            . '/file_name';


        $this->assertSame($expectedUrl, $locator->getJsFileUrl());
    }
}
