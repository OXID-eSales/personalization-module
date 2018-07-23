<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

class PersonalizationAdminController extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    /**
     * @return string
     */
    public function render()
    {
        $this->_aViewData['sClass'] = \OxidEsales\PersonalizationModule\Application\Feed\GenerateCSVExports::class;
        $this->_aViewData['sClassDo'] = \OxidEsales\PersonalizationModule\Application\Feed\GenerateCSVExportsDo::class;
        $this->_aViewData['sClassMain'] = \OxidEsales\PersonalizationModule\Application\Feed\GenerateCSVExportsMain::class;

        $this->_aViewData["cattree"] = oxNew(\OxidEsales\Eshop\Application\Model\CategoryList::class);
        $this->_aViewData["cattree"]->loadList();

        return 'dynexportbase.tpl';
    }
}
