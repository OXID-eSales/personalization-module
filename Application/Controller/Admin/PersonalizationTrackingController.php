<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\Eshop\Core\Registry;

class PersonalizationTrackingController extends PersonalizationBaseController
{
    protected $_sThisTemplate = 'oepersonalizationtracking.tpl';

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
        $this->_aViewData['sClassMain'] = PersonalizationTrackingController::class;
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
}
