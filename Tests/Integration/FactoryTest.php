<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use FileUpload\FileUpload;
use OxidEsales\EcondaModule\Application\Factory;
use OxidEsales\EcondaModule\Component\Tracking\File\FileSystem;
use OxidEsales\EcondaModule\Component\Tracking\File\JsFileLocator;

class FactoryTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetJsFileLocator()
    {
        $this->assertInstanceOf(JsFileLocator::class, $this->getFactory()->getJsFileLocator());
    }

    public function testGetFileUploader()
    {
        $this->assertInstanceOf(FileUpload::class, $this->getFactory()->getFileUploader());
    }

    public function testGetFileSystem()
    {
        $this->assertInstanceOf(FileSystem::class, $this->getFactory()->getFileSystem());
    }

    protected function getFactory()
    {
        return oxNew(Factory::class);
    }
}
