<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class used for export functionality.
 */
class GenerateCSVExportsMainController extends \OxidEsales\Eshop\Application\Controller\Admin\GenericExportMain
{
    /**
     * @inheritdoc
     */
    public $sClassDo = GenerateCSVExportsDoController::class;

    /**
     * @inheritdoc
     */
    public $sClassMain = self::class;

    /**
     * @inheritdoc
     */
    protected $_sThisTemplate = "oepersonalization_export_tab.tpl";

    /**
     * Saves export directory path.
     */
    public function oePersonalizationSave()
    {
        $exportPath = Registry::getRequest()->getRequestEscapedParameter('sOePersonalizationExportPath');
        if (!is_null($exportPath)) {
            $this->getConfig()->saveShopConfVar('string', 'sOePersonalizationExportPath', $exportPath);
        }
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
}
