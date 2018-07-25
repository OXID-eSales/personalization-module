<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use FileUpload\File;
use OxidEsales\PersonalizationModule\Component\File\Validator\ContentValidator;

class ContentValidatorTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testValidFile()
    {
        $pathToVirtualFile = $this->createVirtualFile(
            'someTestingStringWith' . ContentValidator::NEEDLE . ' in it'
        );
        list($file, $isValid) = $this->executeValidation($pathToVirtualFile);
        $this->assertTrue($isValid);
        $this->assertEmpty($file->error);
    }

    public function testInvalidFile()
    {
        $pathToVirtualFile = $this->createVirtualFile(
            'someTestingStringWithOut needle in it'
        );
        list($file, $isValid) = $this->executeValidation($pathToVirtualFile);
        $this->assertFalse($isValid);
        $this->assertNotEmpty($file->error);
    }

    /**
     * @param $contentsOfFile
     * @return string
     */
    protected function createVirtualFile($contentsOfFile)
    {
        $structure = [
            'file' => $contentsOfFile
        ];
        $vfsStream = $this->getVfsStreamWrapper();
        $vfsStream->createStructure($structure);
        $pathToCreateDirectory = $vfsStream->getRootPath();

        return $pathToCreateDirectory.'/'.'file';
    }

    /**
     * @param $pathToCreateDirectory
     * @return array
     */
    protected function executeValidation($pathToCreateDirectory)
    {
        $validator = new ContentValidator();
        $file = new File($pathToCreateDirectory);
        $isValid = $validator->validate($file);

        return [$file, $isValid];
    }
}
