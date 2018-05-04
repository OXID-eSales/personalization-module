<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Component\Tracking\File;

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
    public function getFileUploader()
    {
        $pathResolver = new \FileUpload\PathResolver\Simple($this->pathToUpload);
        $filesystem = new \FileUpload\FileSystem\Simple();
        $validatorSimple = new \FileUpload\Validator\Simple('1M', ['text/plain']);
        $permissionsValidator = new PermissionsValidator($this->pathToUpload, $this->fileName);
        $fileNameGenerator = new \FileUpload\FileNameGenerator\Custom($this->fileName);

        $fileUpload = new \FileUpload\FileUpload($_FILES['file_to_upload'], $_SERVER);
        $fileUpload->setPathResolver($pathResolver);
        $fileUpload->setFileSystem($filesystem);
        $fileUpload->addValidator($validatorSimple);
        $fileUpload->addValidator($permissionsValidator);
        $fileUpload->setFileNameGenerator($fileNameGenerator);

        return $fileUpload;
    }
}
