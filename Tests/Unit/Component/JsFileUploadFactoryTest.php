<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use OxidEsales\PersonalizationModule\Component\File\JsFileUploadFactory;

class JsFileUploadFactoryTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetFileUploader()
    {
        $factory = new JsFileUploadFactory('test_dir_path', 'test_file.js');

        $this->assertInstanceOf(\FileUpload\FileUpload::class, $factory->makeFileUploader());
    }
}
