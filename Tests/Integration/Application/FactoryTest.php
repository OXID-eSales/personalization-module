<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use FileUpload\FileUpload;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\File\FileSystem;
use OxidEsales\PersonalizationModule\Component\File\JsFileLocator;

class FactoryTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testmakeJsFileLocator()
    {
        $this->assertInstanceOf(JsFileLocator::class, $this->getFactory()->makeEmosJsFileLocator());
    }

    public function testGetFileUploader()
    {
        $this->assertInstanceOf(FileUpload::class, $this->getFactory()->makeEmosJsFileUploader());
    }

    public function testGetFileSystem()
    {
        $this->assertInstanceOf(FileSystem::class, $this->getFactory()->makeFileSystem());
    }

    protected function getFactory()
    {
        return oxNew(Factory::class);
    }
}
