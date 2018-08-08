<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\ConfigurationTrait;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\Eshop\Core\Registry;

/**
 * Used as tracking tab controller.
 */
class TrackingTabController extends ShopConfiguration
{
    use ConfigurationTrait;

    protected $_sThisTemplate = 'oepersonalization_tracking_tab.tpl';

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
        $this->_aViewData['sClassMain'] = __CLASS__;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getTrackingScriptMessageIfEnabled()
    {
        $message = '';
        if ($this->factory->makeFileSystem()->isFilePresent($this->factory->makeJsFileLocator()->getJsFileLocation())) {
            $message = sprintf(Registry::getLang()->translateString("OEPERSONALIZATION_MESSAGE_FILE_IS_PRESENT"), $this->factory->makeJsFileLocator()->getFileName());
        }

        return $message;
    }

    /**
     * @return string
     */
    public function getTrackingScriptMessageIfDisabled()
    {
        $message = '';
        if (!$this->factory->makeFileSystem()->isFilePresent($this->factory->makeJsFileLocator()->getJsFileLocation())) {
            $message = sprintf(Registry::getLang()->translateString("OEPERSONALIZATION_MESSAGE_FILE_IS_NOT_PRESENT"), $this->factory->makeJsFileLocator()->getFileName());
        }

        return $message;
    }

    /**
     * An action to upload file emos.js file.
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
            return TrackingTabController::class;
        }

        $fileUploader = $this->factory->makeFileUploader();

        $dataAfterFileUpload = $fileUploader->processAll();
        foreach ($dataAfterFileUpload[0] as $fileData) {
            if ($fileData->error) {
                $this->addErrorToDisplay($fileData->error . '.');
            }
        }
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
