<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\Container;

use OxidEsales\Eshop\Application\Controller\Admin\AdminListController;

/**
 * @inheritdoc
 */
class TabsListController extends AdminListController
{
    protected $_sThisTemplate = '@oepersonalization/admin/container/general';

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->_aViewData['sClassMain'] = __CLASS__;
        parent::__construct();
    }
}
