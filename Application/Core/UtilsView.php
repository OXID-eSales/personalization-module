<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Core;

use OxidEsales\Eshop\Core\Registry;

class UtilsView extends UtilsView_parent
{
    /**
     * @inheritdoc
     */
    protected function _fillCommonSmartyProperties($smarty)
    {
        parent::_fillCommonSmartyProperties($smarty);
        array_unshift($smarty->plugins_dir, $this->getSmartyPluginsDirectory());
    }

    /**
     * Method forms path to corresponding smarty plugins directory.
     *
     * @return string
     */
    public function getSmartyPluginsDirectory()
    {
        return Registry::getConfig()->getActiveView()->getViewConfig()->getModulePath('oeeconda') . 'Application/Core/Smarty/Plugin/';
    }
}
