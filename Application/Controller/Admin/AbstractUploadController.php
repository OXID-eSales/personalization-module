<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\PersonalizationModule\Component\File\FileSystem;
use OxidEsales\PersonalizationModule\Component\File\JsFileLocator;
use FileUpload\FileUpload;

/**
 * Class for basic upload functionality.
 */
abstract class AbstractUploadController extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * @var JsFileLocator
     */
    protected $fileLocator;

    /**
     * @var FileUpload
     */
    protected $fileUploader;

    /**
     * An action to upload file.
     *
     * @return string
     */
    public function upload()
    {
        $isCreated = $this->fileSystem->createDirectory(
            $this->fileLocator->getJsDirectoryLocation()
        );

        if ($isCreated === false) {
            $this->addErrorToDisplay(
                'Unable to create directory '
                . $this->fileLocator->getJsDirectoryLocation()
                . '. Add write permissions for web user or create this '
                . ' directory with write permissions manually.'
            );
            return PersonalizationAdminController::class;
        }

        $dataAfterFileUpload = $this->fileUploader->processAll();
        foreach ($dataAfterFileUpload[0] as $fileData) {
            if ($fileData->error) {
                $this->addErrorToDisplay($fileData->error . '.');
            }
        }

        return PersonalizationAdminController::class;
    }

    /**
     * @param string $errorMessage
     */
    protected function addErrorToDisplay($errorMessage)
    {
        $exception = oxNew(\OxidEsales\Eshop\Core\Exception\StandardException::class, $errorMessage);
        $utilsView = \OxidEsales\Eshop\Core\Registry::getUtilsView();
        $utilsView->addErrorToDisplay($exception);
    }
}
