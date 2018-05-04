<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Controller\Admin;

use OxidEsales\EcondaModule\Application\Factory;
use OxidEsales\Eshop\Core\Registry;

class EcondaAdminController extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
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
     * @return string
     */
    public function render()
    {
        $aConfVars = $this->getConfVarsFromDatabase();
        foreach ($this->_aConfParams as $sType => $sParam) {
            if (is_array($aConfVars[$sType])) {
                foreach ($aConfVars[$sType] as $sName => $sValue) {
                    $this->_aViewData[$sName] = $sValue;
                }
            }
        }
        return 'oeecondaadmin.tpl';
    }

    /**
     * @return string
     */
    public function getTrackingScriptMessageIfEnabled()
    {
        $message = '';
        if ($this->factory->getFileSystem()->isFilePresent($this->factory->getJsFileLocator()->getJsFileLocation())) {
            $message = sprintf(Registry::getLang()->translateString("OEECONDA_MESSAGE_FILE_IS_PRESENT"), $this->factory->getJsFileLocator()->getFileName());
        }

        return $message;
    }

    /**
     * @return string
     */
    public function getTrackingScriptMessageIfDisabled()
    {
        $message = '';
        if (!$this->factory->getFileSystem()->isFilePresent($this->factory->getJsFileLocator()->getJsFileLocation())) {
            $message = sprintf(Registry::getLang()->translateString("OEECONDA_MESSAGE_FILE_IS_NOT_PRESENT"), $this->factory->getJsFileLocator()->getFileName());
        }

        return $message;
    }

    /**
     * Return theme filter for config variables.
     *
     * @return string
     */
    protected function _getModuleForConfigVars()
    {
        return 'module:oeeconda';
    }

    /**
     * Get configuration variables from database.
     *
     * @return array
     */
    protected function getConfVarsFromDatabase()
    {
        $sShopId = $this->getEditObjectId();
        $aDbVariables = $this->loadConfVars($sShopId, $this->_getModuleForConfigVars());

        return $aDbVariables['vars'];
    }
}
