<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\ExportTabController;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\TrackingTabController;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\Export\CsvWriter;
use OxidEsales\PersonalizationModule\Component\File\FileSystem;

class ExportTabControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testExportFailureWhenCreatingDirectory()
    {
        $controller = oxNew(ExportTabController::class, $this->getFactoryStubWhenNotPossibleToCreateDirectory());
        $controller->executeExport();
        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');

        $this->assertNotNull($errors, 'Error must be set when unable to create directory.');
    }

    public function testExportSuccess()
    {
        $controller = oxNew(ExportTabController::class, $this->getFactoryStubWhenExportSucceeds());
        $controller->executeExport();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNull($errors, 'Some error appeared during file upload.');
    }

    public function testExportFailureWhileWritingProductEntriesToFile()
    {
        $controller = oxNew(ExportTabController::class, $this->getFactoryStubWhenErrorOccursWhileWritingToFile());
        $controller->executeExport();
        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');

        $this->assertNotNull($errors, 'Error must be set when unable to create directory.');
    }

    protected function getFactoryStubWhenNotPossibleToCreateDirectory()
    {

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem'])
            ->getMock();

        $fileSystem = $this->getMockBuilder(FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileSystem->method('createDirectory')->willReturn(false);
        $factory->method('makeFileSystem')->willReturn($fileSystem);

        return $factory;
    }

    protected function getFactoryStubWhenExportSucceeds()
    {
        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem', 'makeCsvWriterForExport'])
            ->getMock();

        $factory->method('makeFileSystem')->willReturn($this->getFileSystemStubForSuccessfulDirectoryCreation());
        $factory->method('makeCsvWriterForExport')->willReturn($this->getCsvWriterStub());

        return $factory;
    }

    protected function getFactoryStubWhenErrorOccursWhileWritingToFile()
    {
        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem', 'makeCsvWriterForExport'])
            ->getMock();

        $factory->method('makeFileSystem')->willReturn($this->getFileSystemStubForSuccessfulDirectoryCreation());

        $csvWriter = $this->getCsvWriterStub();
        $csvWriter->method('write')->willThrowException(new \Exception());
        $factory->method('makeCsvWriterForExport')->willReturn($csvWriter);

        return $factory;
    }

    /**
     * @param FileSystem $fileSystem
     * @return TrackingTabController
     */
    protected function getController($fileSystem)
    {
        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);
        $controller = oxNew(TrackingTabController::class, $factory);

        return $controller;
    }

    /**
     * @return FileSystem|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getFileSystemStubForSuccessfulDirectoryCreation()
    {
        $fileSystem = $this->getMockBuilder(FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileSystem->method('createDirectory')->willReturn(true);
        return $fileSystem;
    }

    /**
     * @return CsvWriter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCsvWriterStub()
    {
        $csvWriter = $this->getMockBuilder(CsvWriter::class)
            ->setMethods(['write'])
            ->getMock();
        return $csvWriter;
    }
}
