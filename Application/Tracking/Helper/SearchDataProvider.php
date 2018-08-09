<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Tracking\Helper;

use Smarty;

/**
 * Provides search data for tracking.
 */
class SearchDataProvider
{
    /**
     * @var Smarty
     */
    private $templateEngine;

    /**
     * @param Smarty $templateEngine
     */
    public function __construct(Smarty $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * @return int
     */
    public function getProductsCount()
    {
        $searchCount = 0;
        if (($this->templateEngine->_tpl_vars['oView']) && $this->templateEngine->_tpl_vars['oView']->getArticleCount()) {
            $searchCount = $this->templateEngine->_tpl_vars['oView']->getArticleCount();
        }

        return $searchCount;
    }
}
