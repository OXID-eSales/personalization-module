<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use FileUpload\File;
use OxidEsales\PersonalizationModule\Component\Tracking\File\Validator\PermissionsValidator;

class PermissionsValidatorTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testValidPermissions()
    {
        $pathToCreateDirectory = $this->createVirtualPath();
        $validator = new PermissionsValidator($pathToCreateDirectory, 'writable_file');
        $this->assertTrue($validator->validate(new File('')));
    }

    public function testInValidPermissionsWhenDirectoryNotWritable()
    {
        $pathToCreateDirectory = $this->createVirtualPath();
        chmod($pathToCreateDirectory, 555);
        $validator = new PermissionsValidator($pathToCreateDirectory, 'writable_file');
        $this->assertFalse($validator->validate(new File('')));
    }

    public function testInValidPermissionsWhenFileNotWritable()
    {
        $pathToCreateDirectory = $this->createVirtualPath();
        chmod("$pathToCreateDirectory/writable_file", 555);
        $validator = new PermissionsValidator($pathToCreateDirectory, 'writable_file');
        $this->assertFalse($validator->validate(new File('')));
    }

    /**
     * @return string
     */
    protected function createVirtualPath()
    {
        $structure = [
            'writable_file' => 'contents'
        ];
        $vfsStream = $this->getVfsStreamWrapper();
        $vfsStream->createStructure($structure);
        $pathToCreateDirectory = $vfsStream->getRootPath();

        return $pathToCreateDirectory;
    }
}
