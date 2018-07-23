<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use FileUpload\File;
use OxidEsales\PersonalizationModule\Component\Tracking\File\Validator\ExtensionValidator;

class ExtensionValidatorTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testValidExtension()
    {
        list($file, $isValid) = $this->executeValidation('emos.'.ExtensionValidator::EXTENSION);
        $this->assertTrue($isValid);
        $this->assertEmpty($file->error);
    }

    public function testValidExtensionWithDoubleDots()
    {
        list($file, $isValid) = $this->executeValidation('emos.anything.'.ExtensionValidator::EXTENSION);
        $this->assertTrue($isValid);
        $this->assertEmpty($file->error);
    }

    public function testInvalidExtension()
    {
        list($file, $isValid) = $this->executeValidation('emos.txt');
        $this->assertFalse($isValid);
        $this->assertNotEmpty($file->error);
    }

    public function testInvalidExtensionWithNonStandardEnding()
    {
        list($file, $isValid) = $this->executeValidation('emos.xjs');
        $this->assertFalse($isValid);
        $this->assertNotEmpty($file->error);
    }

    /**
     * @param $fileName
     * @return string
     */
    protected function createVirtualFile($fileName)
    {
        $structure = [
            $fileName => 'contents'
        ];
        $vfsStream = $this->getVfsStreamWrapper();
        $vfsStream->createStructure($structure);
        $pathToCreateDirectory = $vfsStream->getRootPath();

        return $pathToCreateDirectory.'/'.$fileName;
    }

    /**
     * @param $fileName
     * @return array
     */
    protected function executeValidation($fileName)
    {
        $filePath = $this->createVirtualFile($fileName);
        $validator = new ExtensionValidator($filePath);
        $file = new File('');
        $isValid = $validator->validate($file);

        return [$file, $isValid];
    }
}
