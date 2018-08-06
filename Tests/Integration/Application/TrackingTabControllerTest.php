<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use FileUpload\FileUpload;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\TrackingTabController;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem;
use stdClass;

class TrackingTabControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetTrackingScriptMessageIfEnabledWhenFileIsPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(true);

        $controller = $this->getGenerateCSVExportsMain($jsFileLocatorStub);
        $this->assertNotEmpty($controller->getTrackingScriptMessageIfEnabled());
    }

    public function testGetTrackingScriptMessageIfEnabledWhenFileIsNotPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(false);

        $controller = $this->getGenerateCSVExportsMain($jsFileLocatorStub);
        $this->assertEmpty($controller->getTrackingScriptMessageIfEnabled());
    }

    public function testGetTrackingScriptMessageIfDisabledWhenFileIsPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(true);

        $controller = $this->getGenerateCSVExportsMain($jsFileLocatorStub);
        $this->assertEmpty($controller->getTrackingScriptMessageIfDisabled());
    }

    public function testGetTrackingScriptMessageIfDisabledWhenFileIsNotPresent()
    {
        $jsFileLocatorStub = $this->makeFileSystemStub(false);

        $controller = $this->getGenerateCSVExportsMain($jsFileLocatorStub);
        $this->assertNotEmpty($controller->getTrackingScriptMessageIfDisabled());
    }

    public function testUploadFailureWhenCreatingDirectory()
    {
        $controller = oxNew(TrackingTabController::class, $this->getFactoryStubWhenNotPossibleToCreateDirectory());
        $redirectToControllerName = $controller->upload();
        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');

        $this->assertNotNull($errors, 'Error must be set when unable to create directory.');
        $this->assertSame(TrackingTabController::class, $redirectToControllerName);
    }

    public function testUploadFailureWhenUploadingFile()
    {
        $controller = oxNew(TrackingTabController::class, $this->getFactoryStubWhenUploadingFileFailure());
        $redirectToControllerName = $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNotNull($errors, 'Error must be set when unable to upload file.');
    }

    public function testUploadSuccess()
    {
        $controller = oxNew(TrackingTabController::class, $this->getFactoryStubWhenUploadingFileSucceeds());
        $redirectToControllerName = $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNull($errors, 'Some error appeared during file upload.');
    }

    protected function getFactoryStubWhenNotPossibleToCreateDirectory()
    {
        $fileSystem = $this->getMockBuilder(FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileSystem->method('createDirectory')->willReturn(false);

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);

        return $factory;
    }

    protected function getFactoryStubWhenUploadingFileSucceeds()
    {
        $fileSystem = $this->getMockBuilder(FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileSystem->method('createDirectory')->willReturn(true);

        $fileUploader = $this->getMockBuilder(FileUpload::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileUploader->method('processAll')->willReturn([[]]);

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileUploader'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);
        $factory->method('makeFileUploader')->willReturn($fileUploader);

        return $factory;
    }

    protected function getFactoryStubWhenUploadingFileFailure()
    {
        $fileSystem = $this->getMockBuilder(FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileSystem->method('createDirectory')->willReturn(true);

        $fileUploader = $this->getMockBuilder(FileUpload::class)
            ->disableOriginalConstructor()
            ->getMock();
        $errorObject = new StdClass();
        $errorObject->error = 'some error';
        $fileUploader->method('processAll')->willReturn([[$errorObject]]);

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileUploader'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);
        $factory->method('makeFileUploader')->willReturn($fileUploader);

        return $factory;
    }

    /**
     * @param FileSystem $fileSystem
     * @return TrackingTabController
     */
    protected function getGenerateCSVExportsMain($fileSystem)
    {
        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['makeFileSystem'])
            ->getMock();
        $factory->method('makeFileSystem')->willReturn($fileSystem);
        $controller = oxNew(TrackingTabController::class, $factory);

        return $controller;
    }

    /**
     * @param bool $isFilePresent
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FileSystem
     */
    protected function makeFileSystemStub($isFilePresent)
    {
        $fileSystemStub = $this->getMockBuilder(FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileSystemStub->method('isFilePresent')->willReturn($isFilePresent);

        return $fileSystemStub;
    }
}
