<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Acceptance;

use OxidEsales\TestingLibrary\TestConfig;

abstract class BaseAcceptanceTestCase extends \OxidEsales\TestingLibrary\AcceptanceTestCase
{
    const BARGAIN_ARTICLES_WIDGET_ID = 'oePersonalizationBargainArticles';

    const TOP_ARTICLES_WIDGET_ID = 'oePersonalizationTopArticles';

    const LIST_WIDGET_ID = 'oePersonalizationListHead';

    const CROSS_SELLING_WIDGET_ID = 'oePersonalizationRelatedProductsCrossSelling';

    const THANK_YOU_INFO_WIDGET_ID = 'oePersonalizationThankYouInfo';

    protected function activateDemoMode()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOePersonalizationUseDemoAccount' => [
                'type' => 'bool',
                'value' => true,
                'module' => 'module:oepersonalization'
            ]
        ]);
    }

    protected function deactivateDemoMode()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOePersonalizationUseDemoAccount' => [
                'type' => 'bool',
                'value' => false,
                'module' => 'module:oepersonalization'
            ]
        ]);
    }

    protected function checkIfProductExistsInWidget($widgetSelectorId, $productNumberInRow)
    {
        $locator = "//div[@id='$widgetSelectorId']//div[@class='row gridView']/div[$productNumberInRow]";
        $this->waitForItemAppear($locator, 3);
        $this->assertElementVisible($locator);
    }

    /**
     * Login in Frontend.
     *
     * @param string $userName     User name (email).
     * @param string $userPass     User password.
     * @param bool   $waitForLogin
     */
    public function loginInFrontend($userName, $userPass, $waitForLogin = true)
    {
        $this->click("//div[contains(@class, 'showLogin')]/button");
        $this->waitForItemAppear("loginBox");
        $this->type("loginEmail", $userName);
        $this->type("loginPasword", $userPass);
        $this->clickAndWait("//div[@id='loginBox']/button");
        if ($waitForLogin) {
            $this->waitForTextDisappear('%LOGIN%');
        }
    }

    public function logoutFrontend()
    {
        $this->open((new TestConfig)->getShopUrl() . '/index.php?cl=start&fnc=logout&redirect=1');
    }

    protected function enableWidgets()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOePersonalizationEnableWidgets' => [
                'type' => 'bool',
                'value' => true,
                'module' => 'module:oepersonalization'
            ]
        ]);
    }

    protected function disableWidgets()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOePersonalizationEnableWidgets' => [
                'type' => 'bool',
                'value' => false,
                'module' => 'module:oepersonalization'
            ]
        ]);
    }

    protected function setWidgetsIds()
    {
        $this->callShopSC('oxConfig', null, null, [
            'sOePersonalizationWidgetIdThankYouPage' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oepersonalization'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOePersonalizationWidgetIdDetailsPage' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oepersonalization'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOePersonalizationWidgetIdListPage' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oepersonalization'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOePersonalizationWidgetIdStartPageBargainArticles' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oepersonalization'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOePersonalizationWidgetIdStartPageTopArticles' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oepersonalization'
            ]
        ]);
    }

    protected function enableTracking()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOePersonalizationTracking' => [
                'type' => 'bool',
                'value' => true,
                'module' => 'module:oepersonalization'
            ]
        ]);
    }
}
