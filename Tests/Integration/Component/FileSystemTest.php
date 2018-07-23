<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Component;

use org\bovigo\vfs\vfsStream;
use Symfony\Component\Filesystem\Filesystem;

class FileSystemTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testDirectorySuccessfulCreation()
    {
        $pathToCreateDirectory = $this->createVirtualDirectory();
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->createDirectory($pathToCreateDirectory.'/testDirectory'));
        $this->assertTrue(is_dir($pathToCreateDirectory.'/testDirectory'));
    }

    public function testDirectoryUnsuccessfulCreation()
    {
        $pathToCreateDirectory = $this->createVirtualDirectory();
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());
        chmod($pathToCreateDirectory, 555);

        $this->assertFalse($fileSystem->createDirectory($pathToCreateDirectory.'/testDirectory'));
        $this->assertFalse(is_dir($pathToCreateDirectory.'/testDirectory'));
    }

    public function testIfPathNotWritable()
    {
        $pathToCreateDirectory = $this->createVirtualDirectory();
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());
        chmod($pathToCreateDirectory, 555);

        $this->assertFalse($fileSystem->isWritable($pathToCreateDirectory.'/testDirectory'));
    }

    public function testFileDoesNotExists()
    {
        $pathToCreateDirectory = $this->createVirtualDirectory();
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());

        $this->assertFalse($fileSystem->isFilePresent($pathToCreateDirectory . '/any_file'));
    }

    public function testFileExists()
    {
        $pathToCreateDirectory = $this->createVirtualDirectory();
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->isFilePresent($pathToCreateDirectory.'/file.js'));
    }

    /**
     * @return string
     */
    protected function createVirtualDirectory()
    {
        $structure = [
            'out_dir' => [
                'file.js' => 'contents'
            ],
        ];
        $vfsStream = $this->getVfsStreamWrapper();
        $vfsStream->createStructure($structure);
        $pathToCreateDirectory = $vfsStream->getRootPath();

        return $pathToCreateDirectory . DIRECTORY_SEPARATOR . 'out_dir';
    }
}
