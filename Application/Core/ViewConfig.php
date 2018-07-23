<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Core;

use OxidEsales\PersonalizationModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\PersonalizationModule\Component\DemoAccountData;
use OxidEsales\Eshop\Core\Registry;

/**
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    public function oePersonalizationGetAccountId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getAccountId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationAccountId');
    }

    public function oePersonalizationEnableWidgets()
    {
        return $this->getConfig()->getConfigParam('blOePersonalizationEnableWidgets');
    }

    public function oePersonalizationGetListPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getListPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdListPage');
    }

    public function oePersonalizationGetListPageTemplate()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateListPage');
    }

    public function oePersonalizationGetDetailsPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getDetailsPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdDetailsPage');
    }

    public function oePersonalizationGetDetailsPageTemplate()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateDetailsPage');
    }

    public function oePersonalizationGetThankYouPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getThankYouPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdThankYouPage');
    }

    public function oePersonalizationGetThankYouPageTemplate()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateThankYouPage');
    }

    public function oePersonalizationGetStartPageBargainArticlesWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getStartPageBestOffersWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdStartPageBargainArticles');
    }

    public function oePersonalizationGetStartPageBargainArticlesTemplate()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateStartPageBargainArticles');
    }

    public function oePersonalizationGetStartPageTopArticlesWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getStartPageBestSellerWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdStartPageTopArticles');
    }

    public function oePersonalizationGetStartPageTopArticlesTemplate()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateStartPageTopArticles');
    }

    public function oePersonalizationShowTrackingNote()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationTrackingShowNote');
    }

    public function oePersonalizationGetExportPath()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationExportPath');
    }

    /**
     * Returns if user just logged in.
     *
     * @return bool
     */
    public function oePersonalizationIsLoginAction()
    {
        $isLoginAction = false;
        if ('login_noredirect' == $this->oePersonalizationGetRequestActiveFunctionName()) {
            $isLoginAction = true;
        }

        return $isLoginAction;
    }

    /**
     * Returns if user just logged out.
     *
     * @return bool
     */
    public function oePersonalizationIsLogoutAction()
    {
        $isLogoutAction = false;
        if ('logout' == $this->oePersonalizationGetRequestActiveFunctionName()) {
            $isLogoutAction = true;
        }

        return $isLogoutAction;
    }

    /**
     * Returns user hashed ID.
     *
     * @return string
     */
    public function oePersonalizationGetLoggedInUserHashedId()
    {
        return $this->getActiveUserDataProvider()->getActiveUserHashedId();
    }

    /**
     * Returns user hashed email.
     *
     * @return string
     */
    public function oePersonalizationGetLoggedInUserHashedEmail()
    {
        return $this->getActiveUserDataProvider()->getActiveUserHashedEmail();
    }

    /**
     * @return string
     */
    private function oePersonalizationGetRequestActiveFunctionName()
    {
        $currentView = Registry::getConfig()->getActiveView();
        $functionName = $currentView->getFncName();

        return $functionName;
    }

    /**
     * @return ActiveUserDataProvider
     */
    private function getActiveUserDataProvider()
    {
        return oxNew(ActiveUserDataProvider::class);
    }
}
