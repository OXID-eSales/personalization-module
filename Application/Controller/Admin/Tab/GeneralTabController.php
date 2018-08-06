<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\ConfigurationTrait;

class GeneralTabController extends ShopConfiguration
{
    use ConfigurationTrait;

    protected $_sThisTemplate = 'oepersonalization_general_tab.tpl';

    public function __construct()
    {
        $this->_aViewData['sClassMain'] = __CLASS__;
        parent::__construct();
    }
}
