<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

/**
 * Trait used for some of the controllers to provide configuration variables functionality.
 */
trait ConfigurationTrait
{
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
