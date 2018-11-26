<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Core;

use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\PersonalizationModule\Component\DemoAccountData;
use OxidEsales\Eshop\Core\Registry;

/**
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    /**
     * @var Factory
     */
    private $oePersonalizationfactory;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->oePersonalizationfactory = oxNew(Factory::class);
    }

    /**
     * @return string
     */
    public function oePersonalizationGetAccountId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getAccountId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationAccountId');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetClientKey()
    {
        $accountId = $this->oePersonalizationGetAccountId();
        $clientKey = $accountId;

        $accountIdInBlocks = explode('-', $accountId);
        $lastBlock = end($accountIdInBlocks);
        $lastBlockKey  = key($accountIdInBlocks);
        if (ctype_digit($lastBlock)) {
            unset($accountIdInBlocks[$lastBlockKey]);
            $clientKey = implode('-', $accountIdInBlocks);
        }

        return $clientKey;
    }

    /**
     * @return bool
     */
    public function oePersonalizationEnableWidgets()
    {
        return $this->getConfig()->getConfigParam('blOePersonalizationEnableWidgets');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetListPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getListPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdListPage');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetListPageTemplateUrl()
    {
        return Registry::getUtils()->checkUrlEndingSlash(Registry::getConfig()->getShopUrl()) . $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateListPage');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetDetailsPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getDetailsPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdDetailsPage');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetDetailsPageTemplateUrl()
    {
        return Registry::getUtils()->checkUrlEndingSlash(Registry::getConfig()->getShopUrl()) . $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateDetailsPage');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetThankYouPageWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getThankYouPageWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdThankYouPage');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetThankYouPageTemplateUrl()
    {
        return Registry::getUtils()->checkUrlEndingSlash(Registry::getConfig()->getShopUrl()) . $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateThankYouPage');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetStartPageBargainArticlesWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getStartPageBestOffersWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdStartPageBargainArticles');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetStartPageBargainArticlesTemplateUrl()
    {
        return Registry::getUtils()->checkUrlEndingSlash(Registry::getConfig()->getShopUrl()) . $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateStartPageBargainArticles');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetStartPageTopArticlesWidgetId()
    {
        if ($this->getConfig()->getConfigParam('blOePersonalizationUseDemoAccount')) {
            return DemoAccountData::getStartPageBestSellerWidgetId();
        }
        return $this->getConfig()->getConfigParam('sOePersonalizationWidgetIdStartPageTopArticles');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetStartPageTopArticlesTemplateUrl()
    {
        return Registry::getUtils()->checkUrlEndingSlash(Registry::getConfig()->getShopUrl()) . $this->getConfig()->getConfigParam('sOePersonalizationWidgetTemplateStartPageTopArticles');
    }

    /**
     * @return string
     */
    public function oePersonalizationShowTrackingNote()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationTrackingShowNote');
    }

    /**
     * @return string
     */
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
        $userActionIdentifier = $this->oePersonalizationfactory->makeUserActionIdentifier();
        if ($userActionIdentifier->isSuccessfulLogin() || $userActionIdentifier->isSuccessfulRegister()) {
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
        $userActionIdentifier = $this->oePersonalizationfactory->makeUserActionIdentifier();

        return $userActionIdentifier->isSuccessfulLogout();
    }

    /**
     * @return bool
     */
    public function isStartPage()
    {
        $userIdentifier = $this->oePersonalizationfactory->makeUserActionIdentifier();

        return $userIdentifier->isInStartPage();
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
    public function oePersonlalizationGetExportPath()
    {
        return $this->getConfig()->getConfigParam('sOePersonalizationExportPath');
    }

    /**
     * @return bool
     */
    public function oePersonalizationIsTagManagerActive()
    {
        return (bool) $this->getConfig()->getConfigParam('blOePersonalizationTagManager');
    }

    /**
     * @return string
     */
    public function oePersonalizationGetTagManagerJsFileUrl()
    {
        $fileLocator = $this->oePersonalizationfactory->makeTagManagerJsFileLocator();
        return $fileLocator->getJsFileUrl();
    }

    /**
     * @return bool
     */
    public function oePersonalizationIsTrackingEnabled(): bool
    {
        return (bool) Registry::getConfig()->getConfigParam('blOePersonalizationTracking');
    }

    /**
     * @return ActiveUserDataProvider
     */
    private function getActiveUserDataProvider()
    {
        return oxNew(ActiveUserDataProvider::class);
    }
}
