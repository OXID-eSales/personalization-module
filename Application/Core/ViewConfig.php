<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Core;

use OxidEsales\EcondaModule\Component\DemoAccountData;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;

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

    public function oeEcondaEnableWidgets()
    {
        return $this->getConfig()->getConfigParam('blOeEcondaEnableWidgets');
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

    public function oeEcondaGetExportPath()
    {
        return $this->getConfig()->getConfigParam('sOeEcondaExportPath');
    }

    /**
     * Returns if user just logged in.
     *
     * @return bool
     */
    public function oeEcondaIsLoginAction()
    {
        $isLoginAction = false;
        if ('login_noredirect' == $this->oeEcondaGetRequestActiveFunctionName()) {
            $isLoginAction = true;
        }

        return $isLoginAction;
    }

    /**
     * Returns if user just logged out.
     *
     * @return bool
     */
    public function oeEcondaIsLogoutAction()
    {
        $isLogoutAction = false;
        if ('logout' == $this->oeEcondaGetRequestActiveFunctionName()) {
            $isLogoutAction = true;
        }

        return $isLogoutAction;
    }

    /**
     * Returns user hashed ID.
     *
     * @return string
     */
    public function oeEcondaGetLoggedInUserHashedId()
    {
        $activeUser = oxNew(User::class);
        $activeUser->loadActiveUser();

        return md5($activeUser->oxuser__oxid->value);
    }

    /**
     * Returns user hashed email.
     *
     * @return string
     */
    public function oeEcondaGetLoggedInUserHashedEmail()
    {
        $activeUser = oxNew(User::class);
        $activeUser->loadActiveUser();

        return md5($activeUser->oxuser__oxusername->value);
    }

    /**
     * @return string
     */
    private function oeEcondaGetRequestActiveFunctionName()
    {
        $currentView = Registry::getConfig()->getActiveView();
        $functionName = $currentView->getFncName();

        return $functionName;
    }
}
