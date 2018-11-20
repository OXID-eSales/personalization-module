<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

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
        $configurationVariables = $this->getConfVarsFromDatabase();
        foreach ($this->_aConfParams as $type => $parameter) {
            if (is_array($configurationVariables[$type])) {
                foreach ($configurationVariables[$type] as $name => $value) {
                    $this->_aViewData[$name] = $value;
                }
            }
        }

        return parent::render();
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
        $shopId = Registry::getConfig()->getShopId();
        $databaseVariables = $this->loadConfVars($shopId, $this->_getModuleForConfigVars());

        return $databaseVariables['vars'];
    }
}
