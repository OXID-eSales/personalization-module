<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration;

use FileUpload\FileUpload;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\PersonalizationTrackingController;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\EmosJsUploadController;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\Tracking\File\FileSystem;
use stdClass;

class EmosJsUploadControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testUploadFailureWhenCreatingDirectory()
    {
        $controller = oxNew(EmosJsUploadController::class, $this->getFactoryStubWhenNotPossibleToCreateDirectory());
        $redirectToControllerName = $controller->upload();
        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');

        $this->assertNotNull($errors, 'Error must be set when unable to create directory.');
        $this->assertSame(PersonalizationTrackingController::class, $redirectToControllerName);
    }

    public function testUploadFailureWhenUploadingFile()
    {
        $controller = oxNew(EmosJsUploadController::class, $this->getFactoryStubWhenUploadingFileFailure());
        $redirectToControllerName = $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNotNull($errors, 'Error must be set when unable to upload file.');
        $this->assertSame(PersonalizationTrackingController::class, $redirectToControllerName);
    }

    public function testUploadSuccess()
    {
        $controller = oxNew(EmosJsUploadController::class, $this->getFactoryStubWhenUploadingFileSucceeds());
        $redirectToControllerName = $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNull($errors, 'Some error appeared during file upload.');
        $this->assertSame(PersonalizationTrackingController::class, $redirectToControllerName);
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
}
