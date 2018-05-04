<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Unit\Component;

use OxidEsales\EcondaModule\Component\Tracking\File\JsFileUploadFactory;

class JsFileUploadFactoryTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetFileUploader()
    {
        $factory = new JsFileUploadFactory('test_dir_path', 'test_file.js');

        $this->assertInstanceOf(\FileUpload\FileUpload::class, $factory->getFileUploader());
    }
}
