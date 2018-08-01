<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\PersonalizationModule\Application\Factory;

/**
 * Controller responsible for .js file upload.
 */
class EmosJsUploadController extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param null|Factory $factory
     */
    public function __construct($factory = null)
    {
        $this->factory = $factory;
        if (is_null($factory)) {
            $this->factory = oxNew(Factory::class);
        }
        parent::__construct();
    }

    /**
     * An action to upload file.
     *
     * @return string
     */
    public function upload()
    {
        $isCreated = $this->factory->makeFileSystem()->createDirectory(
            $this->factory->makeJsFileLocator()->getJsDirectoryLocation()
        );

        if ($isCreated === false) {
            $this->addErrorToDisplay(
                'Unable to create directory '
                . $this->factory->makeJsFileLocator()->getJsDirectoryLocation()
                . '. Add write permissions for web user or create this '
                . ' directory with write permissions manually.'
            );
            return PersonalizationTrackingController::class;
        }

        $fileUploader = $this->factory->makeFileUploader();

        $dataAfterFileUpload = $fileUploader->processAll();
        foreach ($dataAfterFileUpload[0] as $fileData) {
            if ($fileData->error) {
                $this->addErrorToDisplay($fileData->error . '.');
            }
        }

        return PersonalizationTrackingController::class;
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
