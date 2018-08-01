<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

class PersonalizationGeneralController extends PersonalizationBaseController
{
    protected $_sThisTemplate = 'oepersonalizationgeneral.tpl';

    public function __construct()
    {
        $this->_aViewData['sClassMain'] = PersonalizationGeneralController::class;
        parent::__construct();
    }
}
