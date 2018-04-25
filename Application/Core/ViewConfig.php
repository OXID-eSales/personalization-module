<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Core;

use OxidEsales\EcondaModule\Component\DemoAccountData;

/**
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    public function oeEcondaGetAccountId()
    {
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            return DemoAccountData::getAccountId();
        }
        return $this->getConfig()->getConfigParam('sOeEcondaAccountId');
    }

    public function oeEcondaGetListPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            return DemoAccountData::getListPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetIdListPage');
    }

    public function oeEcondaGetListPageTemplate()
    {
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetTemplateListPage');
    }

    public function oeEcondaGetDetailsPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            return DemoAccountData::getDetailsPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetIdDetailsPage');
    }

    public function oeEcondaGetDetailsPageTemplate()
    {
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetTemplateDetailsPage');
    }

    public function oeEcondaGetThankYouPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            return DemoAccountData::getThankYouPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetIdThankYouPage');
    }

    public function oeEcondaGetThankYouPageTemplate()
    {
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetTemplateThankYouPage');
    }

    public function oeEcondaGetStartPageBargainArticlesWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            return DemoAccountData::getStartPageBestOffersWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetIdStartPageBargainArticles');
    }

    public function oeEcondaGetStartPageBargainArticlesTemplate()
    {
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetTemplateStartPageBargainArticles');
    }

    public function oeEcondaGetStartPageTopArticlesWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOeEcondaUseDemoAccount')) {
            return DemoAccountData::getStartPageBestSellerWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetIdStartPageTopArticles');
    }

    public function oeEcondaGetStartPageTopArticlesTemplate()
    {
        return $this->getConfig()->getConfigParam('sOeEcondaWidgetTemplateStartPageTopArticles');
    }
}
