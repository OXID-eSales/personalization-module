<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Controller\Admin;

class EcondaAdminController extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    /**
     * @return string
     */
    public function render()
    {
        $this->_aViewData['sClass'] = \OxidEsales\EcondaModule\Application\Feed\GenerateCSVExports::class;
        $this->_aViewData['sClassDo'] = \OxidEsales\EcondaModule\Application\Feed\GenerateCSVExportsDo::class;
        $this->_aViewData['sClassMain'] = \OxidEsales\EcondaModule\Application\Feed\GenerateCSVExportsMain::class;

        $this->_aViewData["cattree"] = oxNew(\OxidEsales\Eshop\Application\Model\CategoryList::class);
        $this->_aViewData["cattree"]->loadList();

        return 'dynexportbase.tpl';
    }
}
