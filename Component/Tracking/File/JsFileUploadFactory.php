<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking\File;

use OxidEsales\PersonalizationModule\Component\Tracking\File\Validator\ContentValidator;
use OxidEsales\PersonalizationModule\Component\Tracking\File\Validator\ExtensionValidator;
use OxidEsales\PersonalizationModule\Component\Tracking\File\Validator\PermissionsValidator;

class JsFileUploadFactory
{
    private $pathToUpload;

    private $fileName;

    public function __construct($pathToUpload, $fileName)
    {
        $this->pathToUpload = $pathToUpload;
        $this->fileName = $fileName;
    }

    /**
     * @return \FileUpload\FileUpload
     */
    public function makeFileUploader()
    {
        $fileForUpload = $_FILES['file_to_upload'];

        $pathResolver = new \FileUpload\PathResolver\Simple($this->pathToUpload);
        $filesystem = new \FileUpload\FileSystem\Simple();
        $validatorSimple = new \FileUpload\Validator\Simple('1M', ['text/plain']);
        $permissionsValidator = new PermissionsValidator($this->pathToUpload, $this->fileName);
        $contentValidator = new ContentValidator();
        $extensionValidator = new ExtensionValidator($fileForUpload['name']);
        $fileNameGenerator = new \FileUpload\FileNameGenerator\Custom($this->fileName);

        $fileUpload = new \FileUpload\FileUpload($fileForUpload, $_SERVER);
        $fileUpload->setPathResolver($pathResolver);
        $fileUpload->setFileSystem($filesystem);
        $fileUpload->addValidator($validatorSimple);
        $fileUpload->addValidator($permissionsValidator);
        $fileUpload->addValidator($contentValidator);
        $fileUpload->addValidator($extensionValidator);
        $fileUpload->setFileNameGenerator($fileNameGenerator);

        return $fileUpload;
    }
}
