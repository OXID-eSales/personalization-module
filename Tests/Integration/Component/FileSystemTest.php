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
    private $virtualDirectory;

    public function setUp()
    {
        parent::setUp();
        $this->virtualDirectory = $this->createVirtualDirectory();
    }

    public function testDirectorySuccessfulCreation()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->createDirectory($this->virtualDirectory.'/testDirectory'));
        $this->assertTrue(is_dir($this->virtualDirectory.'/testDirectory'));
    }

    public function testDirectoryUnsuccessfulCreation()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());

        $this->assertFalse($fileSystem->createDirectory('/not_existing_directory/testDirectory'));
        $this->assertFalse(is_dir('/not_existing_directory/testDirectory'));
    }

    public function testIfPathNotWritable()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());
        chmod($this->virtualDirectory, 555);

        $this->assertFalse($fileSystem->isWritable($this->virtualDirectory.'/testDirectory'));
    }

    public function testFileDoesNotExists()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());

        $this->assertFalse($fileSystem->isFilePresent($this->virtualDirectory . '/any_file'));
    }

    public function testFileExists()
    {
        $fileSystem = new \OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->isFilePresent($this->virtualDirectory.'/file.js'));
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
