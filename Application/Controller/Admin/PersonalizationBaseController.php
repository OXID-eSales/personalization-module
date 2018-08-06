<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

/**
 * Class used to provide basic functionality to other personalization related controllers except export.
 */
class PersonalizationBaseController extends \OxidEsales\Eshop\Application\Controller\Admin\AdminListController
{
    use ConfigurationTrait;

    protected $_sThisTemplate = 'oepersonalizationgeneral.tpl';
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
     * Return theme filter for config variables.
     *
     * @return string
     */
    protected function _getModuleForConfigVars()
    {
        return 'module:oepersonalization';
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
