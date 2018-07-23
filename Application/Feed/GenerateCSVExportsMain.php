<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Feed;

use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\Eshop\Core\Registry;

class GenerateCSVExportsMain extends \OxidEsales\Eshop\Application\Controller\Admin\GenericExportMain
{
    /**
     * @inheritdoc
     */
    public $sClassDo = GenerateCSVExportsDo::class;

    /**
     * @inheritdoc
     */
    public $sClassMain = self::class;

    /**
     * @inheritdoc
     */
    protected $_sThisTemplate = "oepersonalizationadmin.tpl";

    protected $_aSkipMultiline = ['aHomeCountry'];
    protected $_aParseFloat = ['iMinOrderPrice'];

    protected $_aConfParams = [
        "bool"   => 'confbools',
        "str"    => 'confstrs',
        "arr"    => 'confarrs',
        "aarr"   => 'confaarrs',
        "select" => 'confselects',
        "num"    => 'confnum',
    ];

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

        return parent::render();
    }

    /**
     * @return string
     */
    public function getTrackingScriptMessageIfEnabled()
    {
        $message = '';
        if ($this->factory->getFileSystem()->isFilePresent($this->factory->getJsFileLocator()->getJsFileLocation())) {
            $message = sprintf(Registry::getLang()->translateString("OEPERSONALIZATION_MESSAGE_FILE_IS_PRESENT"), $this->factory->getJsFileLocator()->getFileName());
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
            $message = sprintf(Registry::getLang()->translateString("OEPERSONALIZATION_MESSAGE_FILE_IS_NOT_PRESENT"), $this->factory->getJsFileLocator()->getFileName());
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
        return 'module:oepersonalization';
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

    /**
     * Saves shop configuration variables
     */
    public function saveConfVars()
    {
        $myConfig = $this->getConfig();

        $this->resetContentCache();

        $sShopId = $this->getEditObjectId();
        $sModule = $this->_getModuleForConfigVars();

        $configValidator = oxNew(\OxidEsales\Eshop\Core\NoJsValidator::class);
        foreach ($this->_aConfParams as $sType => $sParam) {
            $aConfVars = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter($sParam, true);
            if (is_array($aConfVars)) {
                foreach ($aConfVars as $sName => $sValue) {
                    $oldValue = $myConfig->getConfigParam($sName);
                    if ($sValue !== $oldValue) {
                        $sValueToValidate = is_array($sValue) ? join(', ', $sValue) : $sValue;
                        if (!$configValidator->isValid($sValueToValidate)) {
                            $error = oxNew(\OxidEsales\Eshop\Core\DisplayError::class);
                            $error->setFormatParameters(htmlspecialchars($sValueToValidate));
                            $error->setMessage("SHOP_CONFIG_ERROR_INVALID_VALUE");
                            \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($error);
                            continue;
                        }
                        $myConfig->saveShopConfVar(
                            $sType,
                            $sName,
                            $this->_serializeConfVar($sType, $sName, $sValue),
                            $sShopId,
                            $sModule
                        );
                    }
                }
            }
        }
    }

    /**
     * Saves changed shop configuration parameters.
     */
    public function save()
    {
        // saving config params
        $this->saveConfVars();

        //saving additional fields ("oxshops__oxdefcat"") that goes directly to shop (not config)
        /** @var \OxidEsales\Eshop\Application\Model\Shop $oShop */
        $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        if ($oShop->load($this->getEditObjectId())) {
            $oShop->assign(\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("editval"));
            $oShop->save();
        }
    }

    /**
     * Load and parse config vars from db.
     * Return value is a map:
     *      'vars'        => config variable values as array[type][name] = value
     *      'constraints' => constraints list as array[name] = constraint
     *      'grouping'    => grouping info as array[name] = grouping
     *
     * @param string $sShopId Shop id
     * @param string $sModule module to load (empty string is for base values)
     *
     * @return array
     */
    public function loadConfVars($sShopId, $sModule)
    {
        $myConfig = $this->getConfig();
        $aConfVars = [
            "bool"   => [],
            "str"    => [],
            "arr"    => [],
            "aarr"   => [],
            "select" => [],
        ];
        $aVarConstraints = [];
        $aGrouping = [];
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $rs = $oDb->select(
            "select cfg.oxvarname,
                    cfg.oxvartype,
                    DECODE( cfg.oxvarvalue, " . $oDb->quote($myConfig->getConfigParam('sConfigKey')) . ") as oxvarvalue,
                        disp.oxvarconstraint,
                        disp.oxgrouping
                from oxconfig as cfg
                    left join oxconfigdisplay as disp
                        on cfg.oxmodule=disp.oxcfgmodule and cfg.oxvarname=disp.oxcfgvarname
                where cfg.oxshopid = '$sShopId'
                    and cfg.oxmodule=" . $oDb->quote($sModule) . "
                order by disp.oxpos, cfg.oxvarname"
        );

        if ($rs != false && $rs->count() > 0) {
            while (!$rs->EOF) {
                list($sName, $sType, $sValue, $sConstraint, $sGrouping) = $rs->fields;
                $aConfVars[$sType][$sName] = $this->_unserializeConfVar($sType, $sName, $sValue);
                $aVarConstraints[$sName] = $this->_parseConstraint($sType, $sConstraint);
                if ($sGrouping) {
                    if (!isset($aGrouping[$sGrouping])) {
                        $aGrouping[$sGrouping] = [$sName => $sType];
                    } else {
                        $aGrouping[$sGrouping][$sName] = $sType;
                    }
                }
                $rs->fetchRow();
            }
        }

        return [
            'vars'        => $aConfVars,
            'constraints' => $aVarConstraints,
            'grouping'    => $aGrouping,
        ];
    }

    /**
     * parse constraint from type and serialized values
     *
     * @param string $sType       variable type
     * @param string $sConstraint serialized constraint
     *
     * @return mixed
     */
    protected function _parseConstraint($sType, $sConstraint)
    {
        switch ($sType) {
            case "select":
                return array_map('trim', explode('|', $sConstraint));
                break;
        }
        return null;
    }

    /**
     * serialize constraint from type and value
     *
     * @param string $sType       variable type
     * @param mixed  $sConstraint constraint value
     *
     * @return string
     */
    protected function _serializeConstraint($sType, $sConstraint)
    {
        switch ($sType) {
            case "select":
                return implode('|', array_map('trim', $sConstraint));
                break;
        }
        return '';
    }

    /**
     * Unserialize config var depending on it's type
     *
     * @param string $sType  var type
     * @param string $sName  var name
     * @param string $sValue var value
     *
     * @return mixed
     */
    public function _unserializeConfVar($sType, $sName, $sValue)
    {
        $oStr = getStr();
        $mData = null;

        switch ($sType) {
            case "bool":
                $mData = ($sValue == "true" || $sValue == "1");
                break;

            case "str":
            case "select":
            case "num":
            case "int":
                $mData = $oStr->htmlentities($sValue);
                if (in_array($sName, $this->_aParseFloat)) {
                    $mData = str_replace(',', '.', $mData);
                }
                break;

            case "arr":
                if (in_array($sName, $this->_aSkipMultiline)) {
                    $mData = unserialize($sValue);
                } else {
                    $mData = $oStr->htmlentities($this->_arrayToMultiline(unserialize($sValue)));
                }
                break;

            case "aarr":
                if (in_array($sName, $this->_aSkipMultiline)) {
                    $mData = unserialize($sValue);
                } else {
                    $mData = $oStr->htmlentities($this->_aarrayToMultiline(unserialize($sValue)));
                }
                break;
        }

        return $mData;
    }

    /**
     * Prepares data for storing to database.
     * Example: $sType='aarr', $sName='aModules', $mValue='key1=>val1\nkey2=>val2'
     *
     * @param string $sType  var type
     * @param string $sName  var name
     * @param mixed  $mValue var value
     *
     * @return string
     */
    public function _serializeConfVar($sType, $sName, $mValue)
    {
        $sData = $mValue;

        switch ($sType) {
            case "bool":
                break;

            case "str":
            case "select":
            case "int":
                if (in_array($sName, $this->_aParseFloat)) {
                    $sData = str_replace(',', '.', $sData);
                }
                break;

            case "arr":
                if (!is_array($mValue)) {
                    $sData = $this->_multilineToArray($mValue);
                }
                break;

            case "aarr":
                $sData = $this->_multilineToAarray($mValue);
                break;
        }

        return $sData;
    }

    /**
     * Converts simple array to multiline text. Returns this text.
     *
     * @param array $aInput Array with text
     *
     * @return string
     */
    protected function _arrayToMultiline($aInput)
    {
        return implode("\n", (array) $aInput);
    }

    /**
     * Converts Multiline text to simple array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToArray($sMultiline)
    {
        $aArr = explode("\n", $sMultiline);
        if (is_array($aArr)) {
            foreach ($aArr as $sKey => $sVal) {
                $aArr[$sKey] = trim($sVal);
                if ($aArr[$sKey] == "") {
                    unset($aArr[$sKey]);
                }
            }

            return $aArr;
        }
    }

    /**
     * Converts associative array to multiline text. Returns this text.
     *
     * @param array $aInput Array to convert
     *
     * @return string
     */
    protected function _aarrayToMultiline($aInput)
    {
        if (is_array($aInput)) {
            $sMultiline = '';
            foreach ($aInput as $sKey => $sVal) {
                if ($sMultiline) {
                    $sMultiline .= "\n";
                }
                $sMultiline .= $sKey . " => " . $sVal;
            }

            return $sMultiline;
        }
    }

    /**
     * Converts Multiline text to associative array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToAarray($sMultiline)
    {
        $oStr = getStr();
        $aArr = [];
        $aLines = explode("\n", $sMultiline);
        foreach ($aLines as $sLine) {
            $sLine = trim($sLine);
            if ($sLine != "" && $oStr->preg_match("/(.+)=>(.+)/", $sLine, $aRegs)) {
                $sKey = trim($aRegs[1]);
                $sVal = trim($aRegs[2]);
                if ($sKey != "" && $sVal != "") {
                    $aArr[$sKey] = $sVal;
                }
            }
        }

        return $aArr;
    }

    /**
     * Returns active/editable object id
     *
     * @return string
     */
    public function getEditObjectId()
    {
        $sEditId = parent::getEditObjectId();
        if (!$sEditId) {
            return $this->getConfig()->getShopId();
        }

        return $sEditId;
    }
}
