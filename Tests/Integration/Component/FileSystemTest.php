<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Component;

use OxidEsales\PersonalizationModule\Tests\Integration\Component\VirtualFileHelperTrait;
use Symfony\Component\Filesystem\Filesystem;
use OxidEsales\TestingLibrary\UnitTestCase;

class FileSystemTest extends UnitTestCase
{
    use VirtualFileHelperTrait;

    private $virtualDirectory;

    public function setUp()
    {
        parent::setUp();
        $this->virtualDirectory = $this->createVirtualPath();
    }

    public function testDirectorySuccessfulCreation()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->createDirectory($this->virtualDirectory.'/testDirectory'));
        $this->assertTrue(is_dir($this->virtualDirectory.'/testDirectory'));
    }

    public function testDirectoryUnsuccessfulCreation()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\File\FileSystem(new Filesystem());

        chmod($this->virtualDirectory, 555);
        $this->assertFalse($fileSystem->createDirectory($this->virtualDirectory.'/testDirectory'));
        $this->assertFalse(is_dir($this->virtualDirectory.'/testDirectory'));
    }

    public function testIfPathNotWritable()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\File\FileSystem(new Filesystem());
        chmod($this->virtualDirectory, 555);

        $this->assertFalse($fileSystem->isWritable($this->virtualDirectory.'/testDirectory'));
    }

    public function testFileDoesNotExists()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\File\FileSystem(new Filesystem());

        $this->assertFalse($fileSystem->isFilePresent($this->virtualDirectory . '/any_file'));
    }

    public function testFileExists()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->isFilePresent($this->virtualDirectory.'/file'));
    }
}
