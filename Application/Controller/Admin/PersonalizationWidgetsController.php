<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

class PersonalizationWidgetsController extends PersonalizationBaseController
{
    protected $_sThisTemplate = 'oepersonalizationwidgets.tpl';

    public function __construct()
    {
        $this->_aViewData['sClassMain'] = PersonalizationWidgetsController::class;
        parent::__construct();
    }
}
