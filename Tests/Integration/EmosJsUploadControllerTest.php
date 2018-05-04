<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use FileUpload\FileUpload;
use OxidEsales\EcondaModule\Application\Controller\Admin\EcondaAdminController;
use OxidEsales\EcondaModule\Application\Controller\Admin\EmosJsUploadController;
use OxidEsales\EcondaModule\Application\Factory;
use OxidEsales\EcondaModule\Component\Tracking\File\FileSystem;
use stdClass;

class EmosJsUploadControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testUploadFailureWhenCreatingDirectory()
    {
        $controller = oxNew(EmosJsUploadController::class, $this->getFactoryStubWhenNotPossibleToCreateDirectory());
        $redirectToControllerName = $controller->upload();
        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');

        $this->assertNotNull($errors, 'Error must be set when unable to create directory.');
        $this->assertSame(EcondaAdminController::class, $redirectToControllerName);
    }

    public function testUploadFailureWhenUploadingFile()
    {
        $controller = oxNew(EmosJsUploadController::class, $this->getFactoryStubWhenUploadingFileFailure());
        $redirectToControllerName = $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNotNull($errors, 'Error must be set when unable to upload file.');
        $this->assertSame(EcondaAdminController::class, $redirectToControllerName);
    }

    public function testUploadSuccess()
    {
        $controller = oxNew(EmosJsUploadController::class, $this->getFactoryStubWhenUploadingFileSucceeds());
        $redirectToControllerName = $controller->upload();

        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
        $this->assertNull($errors, 'Some error appeared during file upload.');
        $this->assertSame(EcondaAdminController::class, $redirectToControllerName);
    }

    protected function getFactoryStubWhenNotPossibleToCreateDirectory()
    {
        $fileSystem = $this->getMockBuilder(FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileSystem->method('createDirectory')->willReturn(false);

        $factory = $this->getMockBuilder(Factory::class)
            ->setMethods(['getFileSystem'])
            ->getMock();
        $factory->method('getFileSystem')->willReturn($fileSystem);

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
            ->setMethods(['getFileUploader'])
            ->getMock();
        $factory->method('getFileSystem')->willReturn($fileSystem);
        $factory->method('getFileUploader')->willReturn($fileUploader);

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
            ->setMethods(['getFileUploader'])
            ->getMock();
        $factory->method('getFileSystem')->willReturn($fileSystem);
        $factory->method('getFileUploader')->willReturn($fileUploader);

        return $factory;
    }
}
