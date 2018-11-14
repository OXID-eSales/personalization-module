<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EcondaTrackingComponent\File\FileSystem;
use OxidEsales\EcondaTrackingComponent\File\JsFileLocator;
use FileUpload\FileUpload;

/**
 * Trait used to contain upload functionality for controllers.
 * @property FileSystem $fileSystem
 * @property JsFileLocator $fileLocator
 * @property FileUpload $fileUploader
 * @property HttpErrorsDisplayer $errorDisplayer
 */
trait FileUploadTrait
{
    /**
     * An action to upload file emos.js file.
     */
    public function upload()
    {
        $isCreated = $this->fileSystem->createDirectory(
            $this->fileLocator->getJsDirectoryLocation()
        );

        if ($isCreated === false) {
            $this->errorDisplayer->addErrorToDisplay(
                'Unable to create directory '
                . $this->fileLocator->getJsDirectoryLocation()
                . '. Add write permissions for web user or create this '
                . ' directory with write permissions manually.'
            );
        } else {
            $dataAfterFileUpload = $this->fileUploader->processAll();
            foreach ($dataAfterFileUpload[0] as $fileData) {
                if ($fileData->error) {
                    $this->errorDisplayer->addErrorToDisplay($fileData->error . '.');
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getTrackingScriptMessageIfEnabled()
    {
        $message = '';
        if ($this->fileSystem->isFilePresent($this->fileLocator->getJsFileLocation())) {
            $message = sprintf(Registry::getLang()->translateString(static::TRANSLATION_WHEN_FILE_IS_PRESENT), $this->fileLocator->getFileName());
        }

        return $message;
    }

    /**
     * @return string
     */
    public function getTrackingScriptMessageIfDisabled()
    {
        $message = '';
        if (!$this->fileSystem->isFilePresent($this->fileLocator->getJsFileLocation())) {
            $message = sprintf(Registry::getLang()->translateString(static::TRANSLATION_WHEN_FILE_IS_NOT_PRESENT), $this->fileLocator->getFileName());
        }

        return $message;
    }
}
